<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = $semestrNumber. ' семестр';
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Семестровые оценки', 'url' => ['/teacher/attestation']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-index">


    <div class="body-content">
            <?php
//                var_dump($attestations);
                foreach($attestations as $item){
                    $linkText = $item->name;
                    echo Html::a($linkText, ['/teacher/attestation/mark', 'id' => $item->id], ['class' => 'profile-link']) .'<br>';
                }
                ?>

    </div>
</div>
