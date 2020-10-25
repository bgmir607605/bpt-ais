<?php
namespace app\modules\groupManager\controllers;

use Yii;
use app\models\GroupManager;
use app\models\Group;


class StudentController extends DefaultController {

    public function actionIndex()
    {
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['deleted' => '0'])->one()->groupId;
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        return $this->render('index', [
            'group' => $group,
        ]);
    }

}