<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Журнал '.$group->name;
$this->params['breadcrumbs'][] = ['label' => 'Кл. рук.'];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-index">


    <div class="body-content">
        <h2><?= $this->title ;?></h2>
            <?php
                foreach($group->teacherloads as $teacherload){
                    $linkText = $teacherload->discipline->shortName.' ('.$teacherload->user->initials.')';
                    echo Html::a($linkText, ['/group-manager/journal/teacherload', 'id' => $teacherload->id], ['class' => 'profile-link']) .'<br>';
                }
                ?>

    </div>
</div>
