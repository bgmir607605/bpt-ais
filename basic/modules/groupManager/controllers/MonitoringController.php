<?php
namespace app\modules\groupManager\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\MonitoringMark;
use app\models\User;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;
use app\models\GroupManager;
use yii\helpers\ArrayHelper;

class MonitoringController extends DefaultController {

    public function actionIndex()
    {
        // TODO При необходимости вешать на одного препода несколько групп будем ковырять здесь
        $groupId = GroupManager::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['deleted' => '0'])->one()->groupId;
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        $teacherloads = Teacherload::find()->where(['groupId' => $groupId])->andWhere(['deleted' => '0'])->all();
        $monitoringMarks = MonitoringMark::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($teacherloads, 'id')])->all();

         
        
        
        return $this->render('index', [
            'group' => $group,
            'teacherloads' => $teacherloads,
            'monitoringMarks' => $monitoringMarks,
        ]);
    }

}