<?php
namespace app\modules\student\controllers;

use Yii;
use yii\helpers\Json;
use app\models\Skip;
use app\widgets\CalendarWidget;


class SkipController extends DefaultController{
    public function actionIndex($date=NULL)
    {
        $q_month = date('m'); $q_year = date('Y');
        if(!is_null($date)) {
            list($q_year, $q_month) = explode('-', $date);
            if(!is_numeric($q_year) || !is_numeric($q_month)) {
                $q_month = date('m'); $q_year = date('Y');
            }
            $q_year *= 1; $q_month *= 1;
        }
        $student = Yii::$app->user->identity;
        $skips_raw = Skip::find()->where(['studentId' => $student->id])->all();

        $skips_group = [];
        $skips = [];
        $skips_mapping = [];
        
        $skips_hours = 0; $skips_hours_total = 0;
        foreach($skips_raw as $skip) {
            $schedule = $skip->schedule;
            list($year, $month, $day) = explode('-', $schedule->date);
            if($year == $q_year && $month == $q_month) {
                if(!array_key_exists($day, $skips_group))
                    $skips_group[$day] = 0;
                if(!array_key_exists($schedule->date, $skips_mapping))
                    $skips_mapping[$schedule->date] = [];

                $skips_group[$day] += $schedule->hours;
                $skips_hours += $schedule->hours;
                
                $skips_mapping[$schedule->date][] = [
                    'discipline' => $schedule->teacherLoad->discipline->shortName,
                    'teacher' => $schedule->teacherLoad->user->getInitials(),
                    'number' => $schedule->number,
                    'hours' => $schedule->hours
                ];
            }
            
            $skips[] = [
                'date' => $schedule->date,
                'discipline' => $schedule->teacherLoad->discipline->shortName,
                'teacher' => $schedule->teacherLoad->user->getInitials(),
                'number' => $schedule->number,
                'hours' => $schedule->hours
            ];
            $skips_hours_total += $schedule->hours;
        }

        $display_date = CalendarWidget::$calend_months[$q_month - 1] . ' ' . $q_year;

        return $this->render('index', [
            'skips' => $skips,
            'skips_merged' => $skips_group,
            'hours_monthly' => $skips_hours,
            'skips_mapping' => $skips_mapping,
            'hours_total' => $skips_hours_total,
            'display_date' => $display_date,
            'month' => $q_month,
            'year' => $q_year,
        ]);
    }
}
