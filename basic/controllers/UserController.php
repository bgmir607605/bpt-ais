<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use app\models\Log;
use yii\web\ForbiddenHttpException;
use Yii;

/**
 * Default controller for the `admin` module
 */
class UserController extends Controller
{

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
		if (!parent::beforeAction($action)) {
			$log->userId = Yii::$app->user->identity->id.': '.Yii::$app->user->identity->username;
            $log->save();
			return false;
		}
		$log->userId = Yii::$app->user->identity->id.': '.Yii::$app->user->identity->username;
		$log->save();
		return true;
    }

    public function actionIndex()
{
	return $this->render('index');
}

	public function actionChangePass()
	{
		$model  = User::findOne(Yii::$app->user->identity->id);
		$curPass = Yii::$app->request->post('curPass');
		$newPass = Yii::$app->request->post('newPass');
		$confNewPass = Yii::$app->request->post('confNewPass');
		if (!empty($curPass)){
			if(Yii::$app->getSecurity()->validatePassword($curPass, $model->password)){
				if(!empty($newPass)){
					if($newPass == $confNewPass){
						$hash = Yii::$app->getSecurity()->generatePasswordHash($newPass, 10);
						$model->password = $hash;
						if($model->save()){
							Yii::$app->session->setFlash('success', "Пароль изменён");
						} else {
							Yii::$app->session->setFlash('danger', "Ошибка при сохранении");
						}
					} else {
						Yii::$app->session->setFlash('danger', "Новый пароль не совпадает с подтверждением");
					}
				} else {
					Yii::$app->session->setFlash('danger', "Новый пароль не может быть пустым");
				}
			} else {
				// Текущий пароль не верен
				Yii::$app->session->setFlash('danger', "Не верно указан текущий пароль");
			}
		}
		return $this->render('changePass');
	}
	public function actionAlive()
	{
		return ;
	}
}
