<?php
namespace app\modules\student\controllers;

use Yii;
use app\models\StudentInGroup;
use app\models\AttestationSearch;

class AttestationController extends DefaultController{
    public function actionIndex()
    {
        $student = Yii::$app->user->identity;
        $group = StudentInGroup::find()->where(['userId' => $student->id])->one()->group;
        $searchModel = new AttestationSearch();
        $dataProvider = $searchModel->forGroup($group, Yii::$app->request->queryParams);

        return $this->render('index', [
            'student' => $student,
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}