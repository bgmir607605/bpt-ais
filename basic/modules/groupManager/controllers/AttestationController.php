<?php
namespace app\modules\groupManager\controllers;

use Yii;
use app\models\Group;
use app\models\AttestationSearch;
use app\models\GroupManager;


class AttestationController extends DefaultController {

    public function actionIndex()
    {
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->one()->groupId;
        $group = Group::findOne($groupId);
        $searchModel = new AttestationSearch();
        $dataProvider = $searchModel->forGroup($group, Yii::$app->request->queryParams);

        return $this->render('index', [
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        $attestation = \app\models\Attestation::findOne($id);

        return $this->render('view', [
            'attestation' => $attestation,
        ]);
    }
    
    public function actionExcel(){
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->one()->groupId;
        $group = Group::findOne($groupId);
        $content = Yii::$app->toExcel->getAttestationsForGroup($group);
        Yii::$app->response->sendContentAsFile($content, 'Семестровые.xlsx');
    }

}