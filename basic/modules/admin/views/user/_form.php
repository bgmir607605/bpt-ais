<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'admin')->textInput() ?>

    <?= $form->field($model, 'schedule')->textInput() ?>

    <?= $form->field($model, 'inspector')->textInput() ?>

    <?= $form->field($model, 'teacher')->textInput() ?>

    <?= $form->field($model, 'groupManager')->textInput() ?>

    <?= $form->field($model, 'applicantManager')->textInput() ?>

    <?= $form->field($model, 'student')->textInput() ?>

    <?= $form->field($model, 'deleted')->hiddenInput(['value' => '0'])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
