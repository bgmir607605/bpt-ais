<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Смена пароля';
?>
<div class="site-index">

  

    <div class="body-content">
        <?php
        //$form = ActiveForm::begin(); // Инициализация формы с настройками по-умолчанию
        $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'options' => [
                'class' => 'form-horizontal col-lg-11',
                'enctype' => 'multipart/form-data'
            ],
        ]);
        echo Html::input('password', 'curPass', null,['placeholder' => 'текущий пароль']).'<br>';
        echo Html::input('password', 'newPass', null,['placeholder' => 'новый пароль']).'<br>';
        echo Html::input('password', 'confNewPass', null,['placeholder' => 'ещё раз новый пароль']).'<br>';
        echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);
        // Содержимое формы
        ActiveForm::end();
        ?>
    </div>
</div>
