<?php
namespace app\modules\inspector\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\MonitoringMark;
use app\models\Log;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;
use app\models\GroupManager;
use yii\helpers\ArrayHelper;

class MonitoringController extends DefaultController {

    public function actionIndex($groupId = NULL)
    {
        // // needRefactoring Log::findLastOnAction
        $logLastUpdate = Log::find()->where(['action' => 'teacher/monitoring/save'])->orderBy(['datetime' => SORT_DESC  ])->one();
        // TODO При необходимости вешать на одного препода несколько групп будем ковырять здесь
        // needRefactoring Group::findOneNotDeleted
        $group = Group::find()->where(['id' => $groupId])->andWhere(['deleted' => '0'])->one();
        // needRefactoring group->teacherloads (notDeleted)
        $teacherloads = Teacherload::find()->where(['groupId' => $groupId])->andWhere(['deleted' => '0'])->all();
        // needRefactoring MonitoringMarkSet
        $monitoringMarks = MonitoringMark::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($teacherloads, 'id')])->all();
        // needRefactoring Group::findAllNotDeleted
        $groups = Group::find()->where(['deleted' => '0'])->orderBy('name')->all();
        return $this->render('index', [
            'logLastUpdate' => $logLastUpdate,
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