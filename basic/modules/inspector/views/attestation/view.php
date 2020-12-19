<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = $attestation->name;
$this->params['breadcrumbs'][] = ['label' => 'Кл. руководитель'];
$this->params['breadcrumbs'][] = ['label' => 'Семестровые оценки', 'url' => ['/group-manager/attestation']];
$this->params['breadcrumbs'][] = ['label' => $this->title];


?>

<div class="site-index">


    <div class="body-content">

    <?php
    function getMark($attestation, $userId){
        $res = '';
        foreach($attestation->attestationMarks as $item){
            if($item->studentId == $userId){
                return $item->value;
            }
        }
        return $res;

    }

    echo '<table border="solid"><tr><th>Студент</th><th>Оценка</th></tr>';
    foreach($attestation->group->students as $student){
        echo '<tr><td>'.$student->lName.' '.$student->fName.'</td>';
        echo '<td>'.getMark($attestation, $student->id).'</td></tr>';
    }
    echo '</table>';

     ?>
    </div>
</div>
