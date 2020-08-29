<?php
namespace app\components;

use yii\helpers\Html;

use Yii;

class HoursReportTable extends \yii\base\Widget
{
   public $report;
   public $html;

   public function init()
   {
        parent::init();
        if ($this->report === null) {
            $this->html = 'Данные не переданы';
        }
   }

   public function run()
    {
        $this->html .= '<table border="solid">';
        $this->html .= '<tr><th></th><th>Всего по первой подгруппе</th><th>Лекции</th><th>Практические 1 пг</th><th>Практические 2 пг</th><th>КП 1 пг</th><th>КП 2 пг</th><th>Консультации</th><th>СР</th><th>Экз</th><th>Итого</th>';
        $this->html .= '<tr><td>По плану</td><td>'.$this->report->planTotalForFirstSub.'</td><td>'.$this->report->planLecture.'</td><td>'.$this->report->planPracticeForFirstSub.'</td><td>'.$this->report->planPracticeForSecondSub.'</td><td>'.$this->report->planKPForFirstSub.'</td><td>'.$this->report->planKPForSecondSub.'</td><td>'.$this->report->planConsultation.'</td><td>'.$this->report->planSr.'</td><td>'.$this->report->planExam.'</td><td>'.$this->report->planSummaryTotal.'</td>';
        $this->html .= '<tr><td>Выдано</td><td>'.$this->report->doneTotalForFirstSub.'</td><td>'.$this->report->doneLecture.'</td><td>'.$this->report->donePracticeForFirstSub.'</td><td>'.$this->report->donePracticeForSecondSub.'</td><td>'.$this->report->doneKPForFirstSub.'</td><td>'.$this->report->doneKPForSecondSub.'</td><td>'.$this->report->doneConsultation.'</td><td>'.$this->report->doneSr.'</td><td>'.$this->report->doneExam.'</td><td>'.$this->report->doneSummaryTotal.'</td>';
        $this->html .= '<tr><td>Осталось</td><td>'.$this->report->remainsTotalForFirstSub.'</td><td>'.$this->report->remainsLecture.'</td><td>'.$this->report->remainsPracticeForFirstSub.'</td><td>'.$this->report->remainsPracticeForSecondSub.'</td><td>'.$this->report->remainsKPForFirstSub.'</td><td>'.$this->report->remainsKPForSecondSub.'</td><td>'.$this->report->remainsConsultation.'</td><td>'.$this->report->remainsSr.'</td><td>'.$this->report->remainsExam.'</td><td>'.$this->report->remainsSummaryTotal.'</td>';
        $this->html .= '</table>';
        return $this->html;
        // return Html::encode(var_dump($this->report));
    }

}
