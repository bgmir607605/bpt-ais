<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Журналы';
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-index">


    <div class="body-content">
    <h1><?= $this->title; ?></h1>
        <?php
            foreach($teacher->groupsWhereIamWorking as $group){
                echo Html::a($group->name, ['/teacher/journal/for-group', 'groupId' => $group->id], ['class' => 'profile-link']) .'<br>';
            }
        ?>
        <hr>
        <?php
            foreach($teacher->GroupsWhereIamReplacer as $group){
                echo Html::a($group->name, ['/teacher/journal/for-group', 'groupId' => $group->id], ['class' => 'profile-link']) .'<br>';
            }
        ?>
    </div>
</div>
