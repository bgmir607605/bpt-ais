<?php
namespace app\modules\student\controllers;

use Yii;
use app\models\StudentInGroup;
use app\models\Teacherload;
use app\models\Schedule;
use app\models\Mark;
use app\models\Skip;

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
            $tmp['items'] = [];
            $schedulesIds = Schedule::find()->select('id')->where(['teacherloadId' => $teacherload->id]);
            $marks = Mark::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['studentId' => $student->id])->all();
            foreach($marks as $mark){
                $m = [];
                $m['date'] = $mark->schedule->date;
                $m['value'] = $mark->value;
                $tmp['items'][] = $m;
                
            }
            $skips = Skip::find()->where(['in', 'scheduleId', $schedulesIds])->andWhere(['studentId' => $student->id])->all();
            foreach($skips as $skip){
                $m = [];
                $m['date'] = $skip->schedule->date;
                $m['value'] = 'Н';
                $tmp['items'][] = $m;
                
            }
            $container[] = $tmp;
        }
        Yii::$app->session->setFlash('info', 'Чтобы узнать дату оценки, нужно навести на неё кусор и дождаться вспывающей подсказки. Так же можно нажать на неё');
        return $this->render('index', [
            'container' => $container,
        ]);
    }
}