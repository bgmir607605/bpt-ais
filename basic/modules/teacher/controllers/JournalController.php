<?php
namespace app\modules\teacher\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\User;
use app\models\Mark;
use app\models\Skip;

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
        $replaceTeacherloads = array();
        // $replaceTeacherloads = $teacher->teacherloadsWhereIReplace;
        // возвращаем вид
        return $this->render('index',[
            'teacherloads' => $teacherloads,
            'replaceTeacherloads' => $replaceTeacherloads,
        ]);
    }
    public function actionForGroup($groupId = 0)
    {
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        $teacherloads = Teacherload::find()->where(['userId' => $teacher->id])->andWhere(['groupId' => $groupId])->all();
        // Замены
        $replaceTeacherloads = array();
        // $replaceTeacherloads = $teacher->teacherloadsWhereIReplace;
        // возвращаем вид
        return $this->render('forGroup',[
            'teacherloads' => $teacherloads,
            'replaceTeacherloads' => $replaceTeacherloads,
        ]);
    }

    // Страница редактирования оценок по нагрузке
    public function actionTeacherload($id = null)
    {
        // Найти эту нагрузку
        $teacherload = Teacherload::find()->where(['id' => $id])->one();
        // Получить список занятий по данной нагрузке
        $schedules = Schedule::find()->where(['teacherLoadId' => $id])->orderBy('date')->all();
        // Найти студентов, относящихся к группе нагрузки
        
        $usersIds = StudentInGroup::find()->select('userId')->where(['groupId' => $teacherload->groupId]);
        $students = User::find()->where(['in', 'id', $usersIds])->orderBy('lName')->all();
        // // Найти оценки по найденным занятиям
        // // TODO переписать
        $marks = array();
        $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $id])->orderBy('date');
        $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->all();
        $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->all();
        // $marks = Yii::$app->db->createCommand('SELECT * FROM mark WHERE sheduleId IN (select id FROM shedule where teacherLoadId = :teacherLoadId order by date)')
        //     ->bindValue(':teacherLoadId', $id)
        //     ->queryAll();
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
        // var_dump($skips);
        foreach($skips as $scheduleId => $schedule){
            // При заходе в каждую группу - удалять имеющиеся лценки с эти sheduleId
            Yii::$app->db->createCommand('delete FROM skip WHERE scheduleId = :scheduleId')
            ->bindValue(':scheduleId', $scheduleId)
            ->execute();
            // var_dump($schedulesIds);
            foreach($schedule as $skip){
                    Yii::$app->db->createCommand()->insert('skip', [
                        'studentId' => $skip["studentId"],
                        'scheduleId' => $skip["scheduleId"],
                        ])->execute();
            }
        }
    }
}