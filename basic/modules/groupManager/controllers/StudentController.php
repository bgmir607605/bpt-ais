<?php
namespace app\modules\groupManager\controllers;

use Yii;
use app\models\GroupManager;
use app\models\Group;
use app\models\UserSearch;
use app\models\User;


class StudentController extends DefaultController {
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'reset-password' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        // needRefactoring user->group
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->one()->groupId;
        $group = Group::findOne($groupId);
    
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->searchForGroup($group, Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 30;

        return $this->render('index', [
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionResetPassword() {
        $model = User::findOne(Yii::$app->request->get('id'));
        $model->resetPassword();
        Yii::$app->session->setFlash('success', 'Пароль сброшен');
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    

}