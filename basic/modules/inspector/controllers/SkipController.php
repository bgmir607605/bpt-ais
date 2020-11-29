<?php
namespace app\modules\inspector\controllers;

use app\models\Group;
use app\models\GroupManager;
use Yii;

class SkipController extends DefaultController {
    
    function actionIndex($groupId = null, $start = null) {
        // Список групп
        $groups = Group::find()->where(['deleted' => '0'])->orderBy('name')->all();
        
        if(empty($start)){
            return $this->redirect(['/inspector/skip/index', 'start' =>date('Y-m-').'01' ]);
        }
        $start = new \DateTime($start);
        $year = $start->format('Y');
        $month = $start->format('m');
        $group = Group::findOne($groupId);
        $group = empty($group) ? $groups[0] : $group;
        $studentsIds = \yii\helpers\ArrayHelper::getColumn($group->students, 'id');
        $interval = new \DateInterval('P1D');
        $recurrences = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
        $period = new \DatePeriod($start, $interval, $recurrences);
        $months = [
            '2020-09-01' => 'Сентябрь 2020',
            '2020-10-01' => 'Октябрь 2020',
            '2020-11-01' => 'Ноябрь 2020',
            '2020-12-01' => 'Декабрь 2020',
            '2020-01-01' => 'Январь 2021',
            '2020-02-01' => 'Февраль 2021',
            '2020-03-01' => 'Март 2021',
            '2020-04-01' => 'Апрель 2021',
            '2020-05-01' => 'Май 2021',
            '2020-06-01' => 'Июнь 2021',
            '2020-07-01' => 'Июль 2021',
        ];
        
        return $this->render('index', [
            'group' => $group,
            'groups' => $groups,
            'months' => $months,
            'period' => $period,
            'start' => $start,
        ]);
    }
    
    public function actionExcel($start = null){
//        TODO Передавать на вход набор данных
        // needRefactoring user->group
        $start = new \DateTime($start);
        $year = $start->format('Y');
        $month = $start->format('m');
        
        $interval = new \DateInterval('P1D');
        $recurrences = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
        $period = new \DatePeriod($start, $interval, $recurrences);
        $content = Yii::$app->toExcel->getSkipsForInspector($period, $start);
        Yii::$app->response->sendContentAsFile($content, 'Посещаемость.xlsx');
    }
    
}