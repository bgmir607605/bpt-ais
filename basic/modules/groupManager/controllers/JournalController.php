<?php
namespace app\modules\groupManager\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\User;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;
use app\models\GroupManager;
use yii\helpers\ArrayHelper;

class JournalController extends DefaultController {

    public function actionGroupOnDate($date = null)
    {
        $date = !empty($date)? $date : date('Y-m-d');
        $students = [];
        $schedules = [];
        $marks = [];
        $skips = [];
        // Находим запрашиваемую группу
        // TODO При необходимости вешать на одного препода несколько групп будем ковырять здесь
        // needRefactoring user->group
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['deleted' => '0'])->one()->groupId;
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        // Если и группа и дата есть - ищем студентов, занятия, оценки и пропуски
        if(!empty($date) && !empty($group)){
            // Студенты
            $students = $group->students;
            $studentsIds = ArrayHelper::getColumn($students, 'id');
            // Занятия
            // needRefactoring новый класс ScheduleSet
            $schedules = Schedule::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($group->Teacherloads, 'id')])->andWhere(['date' => $date])->andWhere(['deleted' => '0'])->orderBy('number')->all();
            $schedulesIds = ArrayHelper::getColumn($schedules, 'id');
            // Оценки
            // needRefactoring новый класс MarkSet
            $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['in', 'studentId', $studentsIds])->andWhere(['deleted' => '0'])->all();
            // Пропуски
            // needRefactoring новый класс SkipSet
            $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['in', 'studentId', $studentsIds])->andWhere(['deleted' => '0'])->all();
        }
        return $this->render('groupOnDate', [
            'date' => $date,
            'group' => $group,
            'students' => $students,
            'schedules' => $schedules,
            'marks' => $marks,
            'skips' => $skips,

        ]);
    }

}