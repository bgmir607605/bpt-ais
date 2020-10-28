<?php
use app\widgets\CalendarWidget;
use yii\helpers\Html;

$skips_group = [];

$cur_month = date('m'); $cur_year = date('Y');
foreach($skips as $skip) {
    list($year, $month, $day) = explode('-', $skip->schedule->date);
    if($year == $cur_year && $month == $cur_month) {
        if(!array_key_exists($day, $skips_group))
            $skips_group[$day] = 0;
        $skips_group[$day] += $skip->schedule->hours;
    }
}

?><div class="admin-default-index">
    <h1>Пропуски занятий</h1>
    <ul>
    <?php
    $calend = CalendarWidget::begin();
    foreach($skips_group as $day => $hours) {
        $calend->addDay($day, sprintf('Часов пропущено: %s', $hours));
    }
    echo $calend->run();
    ?>
    <details>
        <summary>Список пропусков</summary>
        <table class="table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Дисциплина</th>
                    <th>№ пары</th>
                    <th>Часов</th>
                </tr>
            </thead>
            <tbody><?php foreach($skips as $skip) {
                echo Html::beginTag('tr');
                echo Html::tag('td', $skip->schedule->date);
                echo Html::tag('td', $skip->schedule->teacherLoad->discipline->shortName);
                echo Html::tag('td', $skip->schedule->number);
                echo Html::tag('td', $skip->schedule->hours);
                echo Html::endTag('tr');
            } ?></tbody>
        </table>
    </details>
    </ul>
</div>
