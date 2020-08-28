<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'Настройки';
?>
<div class="site-index">

  

    <div class="body-content">
    <h1><?= $this->title ?></h1>
    <?= Html::a('Сменить пароль ', ['/user/change-pass'], ['class' => 'profile-link']) ?>
    </div>
</div>
