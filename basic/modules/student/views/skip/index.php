<?php
use app\widgets\CalendarWidget;
?><div class="admin-default-index">
    <h1>Пропуски занятий</h1>
    <ul>
    <?php
        foreach($skips as $skip){
            echo '<p>'.$skip->schedule->date.' ('.$skip->schedule->number.' пара) '.$skip->schedule->hours.' ч.</p>';
        }
    ?>
    <?php $calend = CalendarWidget::begin(); ?>
    <?php $calend->addDay(25, 'Test'); ?>
    <?php $calend->addDay(28, 'Test'); ?>
    <?= $calend->run(); ?>
    </ul>
</div>
