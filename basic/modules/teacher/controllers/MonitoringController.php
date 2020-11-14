<?php
namespace app\modules\teacher\controllers;

use Yii;
use app\models\Teacherload;
use app\models\Schedule;
use app\models\Group;
use app\models\Mark;
use app\models\MonitoringMark;

class MonitoringController extends DefaultController {

    public function actionIndex()
    {
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        // needRefactoring user->teacherloads
        $teacherloads = Teacherload::find()->select('groupId')->distinct()->where(['userId' => $teacher->id])->all();
        // Замены
        // needRefactoring user->teacherloadsWhereIamReplacer
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
        // needRefactoringGroup::findOneNotDeleted
        $group = Group::findOne($groupId);
        $teacher = Yii::$app->user->identity;
        // Свои нагрузки
        // needRefactoring user->teacherloads
        $teacherloads = Teacherload::find()->where(['userId' => $teacher->id])->andWhere(['groupId' => $groupId])->all();
        // Замены
        // needRefactoring user->teacherloadsWhereIamReplacer
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
    public function actionTeacherload($id = NULL)
    {
        // TODO расхардкодить мониторинг ид
        $monitoringId = 1;
        $teacher = Yii::$app->user->identity;
        // Найти эту нагрузку
        // needRefactoring Teacherload::findOneNotDeleted
        $teacherload = Teacherload::find()->where(['id' => $id])->one();
        // TODO Далее всё зависит от отношения пользователя к нагрузке
        // Если пользователь вобще никак не относится - ничего не давать
        // needRefactoring вынести логику
        $schedules = Schedule::find()->where(['1' => '0']);
        // Если это основная нагрузка пользователя - дать все занятия
        if($teacherload->userId == $teacher->id){
            // Получить список занятий по данной нагрузке
            $schedules = Schedule::find()->where(['teacherLoadId' => $id]);
        } else {
            // Если пользователь кого то заменял - дать только замены
            $schedules = Schedule::find()->where(['teacherLoadId' => $id])->andWhere(['replaceTeacherId' => $teacher->id]);
        }



        $schedules = $schedules->orderBy('date')->all();
        if(count($schedules) == 0){
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        // Найти студентов, относящихся к группе нагрузки
        
        $students = $teacherload->group->students;
        // // Найти оценки по найденным занятиям
        // // TODO переписать
        // needRefactoring MarkSet, MonitoringMarkSet
        $marks = array();
        $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $id])->orderBy('date');
        $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['deleted' => '0'])->all();
        $monitoringMarks = MonitoringMark::find()->where(['teacherLoadId' => $teacherload->id])->andWhere(['monitoringId' => $monitoringId])->all();
        return $this->render('teacherload', [
            'teacherload' => $teacherload,
            'students' => $students,
            'marks' => $marks,
            'monitoringMarks' => $monitoringMarks,

        ]);
    }

    public function actionSave()
    {
        // TODO id мониторинга
        // needRefactoring
        $monitoringId = 1;
        $teacherloadId = Yii::$app->request->post('teacherloadId');
        $post = Yii::$app->request->post();
        foreach($post as $k => $v){
            if($k != 'csrf' && $k != 'teacherloadId'){
                $userId = str_replace('studentId', '', $k);
                $model = MonitoringMark::find()->where(['teacherLoadId' => $teacherloadId])->andWhere(['userId' => $userId])->andWhere(['monitoringId' => $monitoringId])->one();
                if(!empty($model)){
                    $model->delete();
                }
                $model = new MonitoringMark();
                $model->userId = $userId;
                $model->teacherLoadId = $teacherloadId;
                $model->monitoringId = $monitoringId;
                $model->mark = $v;
                $model->save();
            }
        }
        Yii::$app->session->setFlash('success', "Данные сохранены");
        return $this->redirect( ['/teacher/monitoring/teacherload', 'id' => $teacherloadId]);
    }

}