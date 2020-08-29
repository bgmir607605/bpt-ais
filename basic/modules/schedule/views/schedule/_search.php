<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="schedule-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'teacherLoadId') ?>

    <?php // echo $form->field($model, 'cons') ?>

    <?php // echo $form->field($model, 'forTeach') ?>

    <?php // echo $form->field($model, 'hours') ?>

    <?php // echo $form->field($model, 'kp') ?>

    <?php // echo $form->field($model, 'sr') ?>

    <?php // echo $form->field($model, 'replaceTeacherId') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
