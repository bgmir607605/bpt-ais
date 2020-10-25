<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = $group->name;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Мониторинг', 'url' => ['/teacher/monitoring']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="site-index">


    <div class="body-content">
        <h2>Мои основные нагрузки</h2>
            <?php
                foreach($teacherloads as $teacherload){
                    $linkText = $teacherload->group->name. ' '. $teacherload->discipline->shortName;
                    echo Html::a($linkText, ['/teacher/monitoring/teacherload', 'id' => $teacherload->id], ['class' => 'profile-link']) .'<br>';
                }
                ?>
        <h2>Я заменяю</h2>
            <?php
                foreach($replaceTeacherloads as $teacherload){
                    $linkText = $teacherload->group->name. ' '. $teacherload->discipline->shortName;
                    echo Html::a($linkText, ['/teacher/monitoring/teacherload', 'id' => $teacherload->id], ['class' => 'profile-link']) .'<br>';
                }
            ?>


    </div>
</div>
