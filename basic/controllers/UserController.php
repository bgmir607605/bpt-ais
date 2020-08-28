<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use yii\web\ForbiddenHttpException;
use Yii;

/**
 * Default controller for the `admin` module
 */
class UserController extends Controller
{

    public function beforeAction($action)
	{
		if(Yii::$app->user->isGuest){
            throw new ForbiddenHttpException('Доступ запрещён');
        }
		if (!parent::beforeAction($action)) {
            return false;
		}
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
}
