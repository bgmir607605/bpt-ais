<?php
namespace app\modules\student\controllers;

use Yii;
use app\models\StudentInGroup;
use app\models\Teacherload;
use app\models\Schedule;
use app\models\Mark;

class MarkController extends DefaultController{
    public function actionIndex()
    {
        $student = Yii::$app->user->identity;
        $group = StudentInGroup::find()->where(['userId' => $student->id])->one()->group;
        // Получить оценки по группе
        $teacherloads = Teacherload::find()->where(['groupId' => $group->id])->all();

        $container = array();
        foreach($teacherloads as $teacherload){
            $tmp = array();
            $tmp['teacherload'] = $teacherload;
            $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $teacherload->id]);
            $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['studentId' => $student->id])->all();
            $tmp['marks'] = $marks;
            $container[] = $tmp;
        }
        return $this->render('index', [
            'container' => $container,
        ]);
    }
}