<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use Yii;
use app\models\Log;
use app\models\User;

/**
 * Default controller for the `admin` module
 */
class AccessController extends Controller
{
    public function getRoleName(){
        return 'anyRole';
    }

    public function beforeAction($action)
	{
        $log = new Log();
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->action = $this->route;

		if(Yii::$app->user->isGuest){
            $log->userId = 'guest';
            $log->save();
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        if(Yii::$app->user->identity->{$this->getRoleName()} != 1){
            User::findOne(Yii::$app->user->identity->id)->updateLastDateTime();
            $log->userId = Yii::$app->user->identity->id.': '.Yii::$app->user->identity->username;
            $log->save();
            throw new ForbiddenHttpException('Доступ запрещён');
        }
		if (!parent::beforeAction($action)) {
            $log->save();
            return false;
		}
        User::findOne(Yii::$app->user->identity->id)->updateLastDateTime();        
        $log->userId = Yii::$app->user->identity->id.': '.Yii::$app->user->identity->username;
        $log->save();
		return true;
    }
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    // При удалении любой сущности она остаётся и помечается как удалённая
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->markAsDeleted();

        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
