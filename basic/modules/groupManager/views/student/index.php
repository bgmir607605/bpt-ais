<?php

/* @var $this yii\web\View */

$this->title = 'Студенты';
?>
<div class="site-index">

  

    <div class="body-content">
        <p>Для каждого студента в скобках указан его username. По умолчанию пароль 0000. </p>
        <p>Студент может авторизоваться в системе, сменить пароль, ознакомиться с оценками и информацией о прогулах. </p>
        <ol>
        <?php
        foreach($group->students as $student){
            echo '<li>'.$student->lName.' '.$student->fName.' '.$student->mName.' ('.$student->username.') '.$student->lastDateTime.'</li>';
        }
        ?>
        </ol>
    </div>
</div>
