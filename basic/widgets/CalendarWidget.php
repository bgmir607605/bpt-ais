<?php
namespace app\widgets;
use yii\base\Widget;
use yii\helpers\Html;
use app\assets\CalendarAsset;

class CalendarWidget extends Widget {
    public static $calend_months = [
        'Январь', 'Февраль', 'Март', 'Апрель', 'Май',
        'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь',
        'Ноябрь', 'Декабрь'
    ];
    public static $calend_weekdays = [
        'Пн.', 'Вт.', 'Ср.', 'Чт.', 'Пт.', 'Сб.', 'Вс.'
    ];
    
    public $month;
    public $year;
    public $highlights;
    public $dateChangeCallback;
    public $dateSelectCallback;
    
    public function init()
    {
        CalendarAsset::register( $this->getView() );
        parent::init();

        $this->month = is_null($this->month) ? date('m') : $this->month;
        $this->year = is_null($this->year) ? date('Y') : $this->year;
        
        $this->highlights = is_null($this->highlights) ? [] : $this->highlights;
    }
    
    private function _renderHead()
    {
        echo Html::beginTag('thead');
        
        echo Html::beginTag('tr');
        $heading = $this::$calend_months[$this->month - 1] . ' ' . $this->year;
        echo Html::tag('th', '<', [
            'x-action' => 'date-backward',
            'class' => 'c-heading',
            'onclick' => $this->dateChangeCallback
        ]);
        echo Html::tag('th', $heading, [
            'colspan' => 5,
            'class' => 'c-heading'
        ]);
        echo Html::tag('th', '>', [
            'x-action' => 'date-forward',
            'class' => 'c-heading',
            'onclick' => $this->dateChangeCallback
        ]);
        echo Html::endTag('tr');

        echo Html::beginTag('tr');
        foreach($this::$calend_weekdays as $wdname) {
            echo Html::tag('th', $wdname, [
                'class' => 'c-week-names'
            ]);
        }
        echo Html::endTag('tr');
        echo Html::endTag('thead');
    }
    
    private function _generateCalendar()
    {
        $days = [];
        $month_start = mktime(0, 0, 0, $this->month, 1, $this->year);
        $month_start_wday = date('w', $month_start);
        $days_in_month = date('t', $month_start);
        
        $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        for($j = 0; $j < $month_start_wday - 1; $j++) {
            $days[] = [
                'number' => '',
                'weekpos' => $j,
                'title' => NULL,
                'today' => false,
                'date' => NULL
            ];
        }
        
        for($i = $j, $j = 1; $j <= $days_in_month; $i++, $j++) {
            $title = NULL;
            if(array_key_exists($j, $this->highlights)) {
                $title = $this->highlights[$j];
            }
            
            $current_day = mktime(0, 0, 0, $this->month, $j, $this->year);

            $days[] = [
                'number' => $j,
                'weekpos' => $i % 7,
                'title' => $title,
                'today' => $current_day == $today,
                'date' => sprintf(
                    '%04d-%02d-%02d',
                    $this->year, $this->month, $j
                )
            ];
        }
        
        while($i % 7) {
            $days[] = [
                'number' => '',
                'weekpos' => $i % 7,
                'title' => NULL,
                'today' => false,
                'date' => NULL
            ];
            $i++;
        }

        return $days;
    }

    private function _renderBody()
    {
        echo Html::beginTag('tbody');
        
        echo Html::beginTag('tr');
        $calend = $this->_generateCalendar();
        for($i = 0; $i < count($calend); $i++) {
            if($i > 0 && ($i % 7) == 0) {
                echo Html::beginTag('tr');
                echo Html::endTag('tr');
            }
            
            $day = $calend[$i];
            $classes = [ 'c-single-day '];
            if($day['number'] == '')
                $classes[] = 'c-empty-day';

            if($day['weekpos'] > 5)
                $classes[] = 'c-weekend';
                
            if(!is_null($day['title']))
                $classes[] = 'c-active';
            
            if($day['today'])
                 $classes[] = 'c-today';
            

            echo Html::tag('td', $day['number'], [
                'class' => implode(' ', $classes),
                'title' => $day['title'],
                'x-date' => $day['date'],
                'onclick' => $day['number'] ? $this->dateSelectCallback : NULL
            ]);
        }
        echo Html::endTag('tr');

        echo Html::endTag('tbody');
    }

    public function run()
    {
        ob_start();

        echo Html::beginTag('table', [ 'class' => 'w-hkcalendar table' ]);
        $this->_renderHead();
        $this->_renderBody();
        echo Html::endTag('table');

        return ob_get_clean();
    }
    
    public function addDay($day, $title)
    {
        $this->highlights[$day] = $title;
    }

    public function setDateChangeCallback($callback)
    {
        $this->dateChangeCallback = $callback;
    }
    
    public function setDateSelectCallback($callback)
    {
        $this->dateSelectCallback = $callback;
    }
    
}
