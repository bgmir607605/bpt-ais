<?php

namespace app\controllers;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use Yii;

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
		if(Yii::$app->user->isGuest){
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        if(Yii::$app->user->identity->{$this->getRoleName()} != 1){
            throw new ForbiddenHttpException('Доступ запрещён');
        }
		if (!parent::beforeAction($action)) {
            return false;
		}
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
        $model->deleted = 1;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
