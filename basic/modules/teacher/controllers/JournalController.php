<?php
namespace app\modules\teacher\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\User;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;

class JournalController extends DefaultController {
    /**
     * Возвращает группы, в которых ведёт препод
     */
    public function actionIndex()
    {
        return $this->render('index',[
            'teacher' => Yii::$app->user->identity,
        ]);
    }
    public function actionForGroup($groupId = 0)
    {
        // needRefactoring Group::findOneNotDeleted
        $group = Group::findOne($groupId);
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        // needRefactoring user->teacherloads
        $teacherloads = Teacherload::find()->where(['userId' => $teacher->id])->andWhere(['groupId' => $groupId])->all();
        // Замены
        // needRefactoring user->teacherloadsWhereIamReplacer
        $replaceTeacherloads = array();
        // $replaceTeacherloads = $teacher->teacherloadsWhereIReplace;
        // $replaceTeacherloadsIds = Schedule::find()->select('teacherLoadId')->distinct()->where(['replaceTeacherId' => $teacher->id]);
        $replaceTeacherloadsIds = Schedule::find()->select('teacherLoadId')->distinct()->where(['replaceTeacherId' => $teacher->id])->andWhere(['deleted' => '0']);
        $replaceTeacherloads = Teacherload::find()->where(['in', 'id', $replaceTeacherloadsIds])->andWhere(['groupId' => $groupId])->all();
        // возвращаем вид
        return $this->render('forGroup',[
            
            'teacher' => $teacher,
            'group' => $group,
            'teacherloads' => $teacherloads,
            'replaceTeacherloads' => $replaceTeacherloads,
        ]);
    }

    // Страница редактирования оценок по нагрузке
    public function actionTeacherload($id = null, $all = 0, $dateFrom = NULL, $dateTo = NULL)
    {
        $teacher = Yii::$app->user->identity;
        // Найти эту нагрузку
        // needRefactoring Teacherload::findOneNotDeleted
        $teacherload = Teacherload::find()->where(['id' => $id])->one();
        // TODO Далее всё зависит от отношения пользователя к нагрузке
        // needRefactoring вынести эту логику
        // Если пользователь вобще никак не относится - ничего не давать
        $schedules = Schedule::find()->where(['1' => '0']);
        // Если это основная нагрузка пользователя - дать все занятия
        if($teacherload->userId == $teacher->id){
            // Получить список занятий по данной нагрузке
            $schedules = Schedule::find()->where(['teacherLoadId' => $id]);
        } else {
            // Если пользователь кого то заменял - дать только замены
            $schedules = Schedule::find()->where(['teacherLoadId' => $id])->andWhere(['replaceTeacherId' => $teacher->id]);
        }

        // Махинации с временным диапазоном
        // Если не все
        if($all != 1){
            if(!empty($dateFrom) && !empty($dateTo)){
                // Если указаны начало и конец
                $schedules = $schedules->andWhere(['>=', 'date', $dateFrom]);
                $schedules = $schedules->andWhere(['<=', 'date', $dateTo]);

            } else {
                // Иначе по умолчанию берём последние 2 недели
                $dateFrom = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));
                $dateTo = date('Y-m-d');
                $schedules = $schedules->andWhere(['>=', 'date', $dateFrom]);
            }
        } else {
            $dateFrom = '2020-09-01';
            $dateTo = date('Y-m-d');

        }


        $schedules = $schedules->andWhere(['deleted' => '0'])->orderBy('date')->all();
        // Найти студентов, относящихся к группе нагрузки
        
        $students = $teacherload->group->students;
        // // Найти оценки по найденным занятиям
        // // TODO переписать
        $marks = array();
        // needRefactoring MarkSet SkipSet
        $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $id])->orderBy('date');
        $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['deleted' => '0'])->all();
        $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['deleted' => '0'])->all();
        return $this->render('teacherload', [
            'teacherload' => $teacherload,
            'schedules' => $schedules,
            'students' => $students,
            'marks' => $marks,
            'skips' => $skips,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,

        ]);
    }
 
    // Сохранение оценок
    public function actionSavemarksAndSkips()
    {
        $marks = Yii::$app->request->post('marks');
        $marks = json_decode($marks, true);
        $skips = Yii::$app->request->post('skips');
        $skips = json_decode($skips, true);
        // теперь будем перебирать занятия сгруппированные по sheduleId
        foreach($marks as $scheduleId => $schedule){
            // При заходе в каждую группу - удалять имеющиеся лценки с эти sheduleId
            // needRefactoring переписать на построитель
            Yii::$app->db->createCommand('delete FROM mark WHERE scheduleId = :scheduleId')
            ->bindValue(':scheduleId', $scheduleId)
            ->execute();
            foreach($schedule as $mark){
                    // needRefactoring переписать на построитель
                    Yii::$app->db->createCommand()->insert('mark', [
                        'value' => $mark["value"],
                        'studentId' => $mark["studentId"],
                        'scheduleId' => $mark["scheduleId"],
                        ])->execute();
            }
        }
        // ПРОПУСКИ занятий
        // теперь будем перебирать занятия сгруппированные по sheduleId
        // needRefactoring переписать на построители
        foreach($skips as $scheduleId => $schedule){
            // При заходе в каждую группу - удалять имеющиеся лценки с эти sheduleId
            Yii::$app->db->createCommand('delete FROM skip WHERE scheduleId = :scheduleId')
            ->bindValue(':scheduleId', $scheduleId)
            ->execute();
            foreach($schedule as $skip){
                    Yii::$app->db->createCommand()->insert('skip', [
                        'studentId' => $skip["studentId"],
                        'scheduleId' => $skip["scheduleId"],
                        ])->execute();
            }
        }
    }
}