<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Teacherload */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teacherload-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->dropDownlist($teachers) ?>

    <?= $form->field($model, 'groupId')->hiddenInput(['value' => $group->id])->label(false) ?>

    <?= $form->field($model, 'disciplineId')->dropDownlist($disciplines) ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'fSub')->textInput() ?>

    <?= $form->field($model, 'sSub')->textInput() ?>

    <?= $form->field($model, 'cons')->textInput() ?>

    <?= $form->field($model, 'fSubKP')->textInput() ?>

    <?= $form->field($model, 'sSubKP')->textInput() ?>

    <?= $form->field($model, 'sr')->textInput() ?>

    <?= $form->field($model, 'exam')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
