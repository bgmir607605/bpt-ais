<?php
namespace app\modules\inspector\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\User;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;
use yii\helpers\ArrayHelper;

class JournalController extends DefaultController {

    public function actionGroupOnDate($date = null, $groupId=null)
    {
        $students = [];
        $schedules = [];
        $marks = [];
        $skips = [];
        // Список групп
        $groups = Group::find()->where(['deleted' => '0'])->orderBy('name')->all();
        // Находим запрашиваемую группу
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        // Если и группа и дата есть - ищем студентов, занятия, оценки и пропуски
        if(!empty($date) && !empty($group)){
            // Студенты
            $students = $group->students;
            $studentsIds = ArrayHelper::getColumn($students, 'id');
            // Занятия
            $schedules = Schedule::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($group->Teacherloads, 'id')])->andWhere(['date' => $date])->andWhere(['deleted' => '0'])->orderBy('number')->all();
            $schedulesIds = ArrayHelper::getColumn($schedules, 'id');
            // Оценки
            $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['in', 'studentId', $studentsIds])->andWhere(['deleted' => '0'])->all();
            // Пропуски
            $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['in', 'studentId', $studentsIds])->andWhere(['deleted' => '0'])->all();
        }
        return $this->render('groupOnDate', [
            'date' => $date,
            'group' => $group,
            'groups' => $groups,
            'students' => $students,
            'schedules' => $schedules,
            'marks' => $marks,
            'skips' => $skips,

        ]);
    }

}