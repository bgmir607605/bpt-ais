<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['/schedule/teacherload/report'],
        'method' => 'get',
    ]); ?>



    <?= $form->field($model, 'dateFrom')->input('date') ?>
    <?= $form->field($model, 'dateTo')->input('date') ?>

    <?php
    echo $form->field($model, 'type')
    ->checkboxList([
        '0' => 'Общ',
        'I' => 'I подгруппа',
        'II' => 'II подгруппа',
    ]);
    ?>
    
    <?php
    echo $form->field($model, 'cons')
    ->checkboxList([
        '0' => 'Не консультация',
        '1' => 'Консультация',
    ]);
    ?>
    <?php
    echo $form->field($model, 'kp')
    ->checkboxList([
        '0' => 'Не КП',
        '1' => 'КП',
    ]);
    ?>
    <?php
    echo $form->field($model, 'sr')
    ->checkboxList([
        '0' => 'Не СР',
        '1' => 'СР',
    ]);
    ?>

    <input type="hidden" name="teacherloadId" value="<?= $teacherload->id;?>">
    

    <?php // echo $form->field($model, 'cons') ?>

    <?php // echo $form->field($model, 'forTeach') ?>

    <?php // echo $form->field($model, 'hours') ?>

    <?php // echo $form->field($model, 'kp') ?>

    <?php // echo $form->field($model, 'sr') ?>

    <?php // echo $form->field($model, 'replaceTeacherId') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Показать всё', ['/schedule/teacherload/report', 'teacherloadId' => $teacherload->id], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
