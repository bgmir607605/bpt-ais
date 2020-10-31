<?php
namespace app\modules\student\controllers;

use Yii;
use app\models\StudentInGroup;
use app\models\Teacherload;
use app\models\Schedule;
use app\models\Mark;

class MonitoringController extends DefaultController{
    public function actionIndex()
    {
//        TODO Сделать по человечески
        $student = Yii::$app->user->identity;
        $monitoringMarks = \app\models\MonitoringMark::find()->where(['userId' => $student->id])->all();
        return $this->render('index', [
            'monitoringMarks' => $monitoringMarks,
        ]);
    }
}