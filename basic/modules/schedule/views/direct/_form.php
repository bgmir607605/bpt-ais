<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Direct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direct-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropdownList([
        'СПО' => 'СПО',
        'НПО' => 'НПО',
    ]) ?>

<?= $form->field($model, 'forApplicant')->dropdownList([
        '0' => 'Нет',
        '1' => 'Да',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
