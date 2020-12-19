<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Семестровые оценки';
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-index">


    <div class="body-content">
    <h1><?= $this->title; ?></h1>
        <?php
//        var_dump($semesters);
            foreach($semesters as $item){
                echo Html::a($item.' семестр', ['/teacher/attestation/for-semestr', 'semestrNumber' => $item], ['class' => 'profile-link']) .'<br>';
            }
        ?>

    </div>
</div>
