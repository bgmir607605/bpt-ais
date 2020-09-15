<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'Журналы групп';
?>
<div class="site-index">


    <div class="body-content">
        <h2>Мои основные нагрузки</h2>
            <?php
                foreach($teacherloads as $teacherload){
                    $linkText = $teacherload->group->name. ' '. $teacherload->discipline->shortName;
                    echo Html::a($linkText, ['/teacher/journal/teacherload', 'id' => $teacherload->id], ['class' => 'profile-link']) .'<br>';
                }
            ?>
        <h2>Я заменяю</h2>
            <?php
                // foreach($replaceTeacherloads as $teacherload){
                //     $linkText = $teacherload->group->name. ' '. $teacherload->discipline->shortName;
                //     echo Html::a($linkText, ['teacher/teacherload', 'id' => $teacherload->id], ['class' => 'profile-link']) .'<br>';
                // }
            ?>


    </div>
</div>
