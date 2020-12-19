<?php
namespace app\modules\inspector\controllers;

use Yii;
use app\models\Log;
use app\models\Group;
use app\models\AttestationSearch;

class AttestationController extends DefaultController {

    public function actionIndex($groupId = NULL)
    {
        // // needRefactoring Log::findLastOnAction
        $logLastUpdate = Log::find()->where(['action' => 'teacher/attestation/save'])->orderBy(['datetime' => SORT_DESC  ])->one();
        // TODO При необходимости вешать на одного препода несколько групп будем ковырять здесь
        $group = Group::findOne($groupId);
        $searchModel = new AttestationSearch();
        $dataProvider = $searchModel->forGroup($group, Yii::$app->request->queryParams);
        $groups = Group::find()->orderBy('name')->all();
        return $this->render('index', [
            'logLastUpdate' => $logLastUpdate,
            'group' => $group,
            'groups' => $groups,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionExcel(){
        $content = Yii::$app->toExcel->getAttestations();
        Yii::$app->response->sendContentAsFile($content, 'Семестровые.xlsx');
    }
    
    public function actionView($id)
    {
        $attestation = \app\models\Attestation::findOne($id);

        return $this->render('view', [
            'attestation' => $attestation,
        ]);
    }

}