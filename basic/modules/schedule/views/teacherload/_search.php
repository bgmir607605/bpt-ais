<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TeacherloadSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacherload-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'groupId') ?>

    <?= $form->field($model, 'disciplineId') ?>

    <?= $form->field($model, 'total') ?>

    <?php // echo $form->field($model, 'fSub') ?>

    <?php // echo $form->field($model, 'sSub') ?>

    <?php // echo $form->field($model, 'cons') ?>

    <?php // echo $form->field($model, 'fSubKP') ?>

    <?php // echo $form->field($model, 'sSubKP') ?>

    <?php // echo $form->field($model, 'sr') ?>

    <?php // echo $form->field($model, 'exam') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
