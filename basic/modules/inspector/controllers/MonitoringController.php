<?php
namespace app\modules\inspector\controllers;

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

    public function actionIndex($groupId = NULL)
    {
        // TODO При необходимости вешать на одного препода несколько групп будем ковырять здесь
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        $teacherloads = Teacherload::find()->where(['groupId' => $groupId])->andWhere(['deleted' => '0'])->all();
        $monitoringMarks = MonitoringMark::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($teacherloads, 'id')])->all();
        $groups = Group::find()->where(['deleted' => '0'])->orderBy('name')->all();
        return $this->render('index', [
            'group' => $group,
            'groups' => $groups,
            'teacherloads' => $teacherloads,
            'monitoringMarks' => $monitoringMarks,
        ]);
    }
    public function actionExcel(){
//        TODO Передавать на вход набор данных
        $content = Yii::$app->toExcel->getMonitoring();
        Yii::$app->response->sendContentAsFile($content, 'Мониторинг.xlsx');
    }

}