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
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->goHome();
    }
}
