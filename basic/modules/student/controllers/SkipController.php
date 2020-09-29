<?php
namespace app\modules\student\controllers;

use Yii;
use app\models\Skip;


class SkipController extends DefaultController{
    public function actionIndex()
    {
        $student = Yii::$app->user->identity;
        $skips = Skip::find()->where(['studentId' => $student->id])->all();
        return $this->render('index', [
            'skips' => $skips,
        ]);
    }
}