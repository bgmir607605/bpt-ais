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
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        $teacherloads = Teacherload::find()->select('groupId')->distinct()->where(['userId' => $teacher->id])->all();
        // Замены
        $replaceTeacherloadsIds = Schedule::find()->select('teacherLoadId')->distinct()->where(['replaceTeacherId' => $teacher->id]); 
        $replaceTeacherloads = Teacherload::find()->select('groupId')->distinct()->where(['in', 'id', $replaceTeacherloadsIds])->all();
        // возвращаем вид
        return $this->render('index',[
            'teacherloads' => $teacherloads,
            'replaceTeacherloads' => $replaceTeacherloads,
        ]);
    }
    public function actionForGroup($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        $teacherloads = Teacherload::find()->where(['userId' => $teacher->id])->andWhere(['groupId' => $groupId])->all();
        // Замены
        $replaceTeacherloads = array();
        // $replaceTeacherloads = $teacher->teacherloadsWhereIReplace;
        $replaceTeacherloadsIds = Schedule::find()->select('teacherLoadId')->distinct()->where(['replaceTeacherId' => $teacher->id]);
        $replaceTeacherloads = Teacherload::find()->where(['in', 'id', $replaceTeacherloadsIds])->andWhere(['groupId' => $groupId])->all();
        // возвращаем вид
        return $this->render('forGroup',[
            'group' => $group,
            'teacherloads' => $teacherloads,
            'replaceTeacherloads' => $replaceTeacherloads,
        ]);
    }

    // Страница редактирования оценок по нагрузке
    public function actionTeacherload($id = null, $all = 0)
    {
        $teacher = Yii::$app->user->identity;
        // Найти эту нагрузку
        $teacherload = Teacherload::find()->where(['id' => $id])->one();
        // TODO Далее всё зависит от отношения пользователя к нагрузке
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
        // Если за последние 2 недели
        if($all != 1){
            $date = date("Y-m-d", mktime(0, 0, 0, date('m'), date('d') - 14, date('Y')));
            $schedules = $schedules->andWhere(['>', 'date', $date]);
        }
        $schedules = $schedules->orderBy('date')->all();
        // Найти студентов, относящихся к группе нагрузки
        
        $students = $teacherload->group->students;
        // // Найти оценки по найденным занятиям
        // // TODO переписать
        $marks = array();
        $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $id])->orderBy('date');
        $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->all();
        $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->all();
        return $this->render('teacherload', [
            'teacherload' => $teacherload,
            'schedules' => $schedules,
            'students' => $students,
            'marks' => $marks,
            'skips' => $skips,
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
            Yii::$app->db->createCommand('delete FROM mark WHERE scheduleId = :scheduleId')
            ->bindValue(':scheduleId', $scheduleId)
            ->execute();
            foreach($schedule as $mark){
                    Yii::$app->db->createCommand()->insert('mark', [
                        'value' => $mark["value"],
                        'studentId' => $mark["studentId"],
                        'scheduleId' => $mark["scheduleId"],
                        ])->execute();
            }
        }
        // ПРОПУСКИ занятий
        // теперь будем перебирать занятия сгруппированные по sheduleId
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