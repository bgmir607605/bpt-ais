<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = $attestation->name;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Семестровые оценки', 'url' => ['/teacher/attestation']];
$this->params['breadcrumbs'][] = ['label' => $attestation->semestrNumber.' семестр', 'url' => ['/teacher/attestation/for-semestr', 'semestrNumber' => $attestation->semestrNumber]];
$this->params['breadcrumbs'][] = ['label' => $this->title];


?>

<div class="site-index">


    <div class="body-content">

    <p>Оценки - только цифры.</p>
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

    $url = Url::toRoute(['/teacher/attestation/save']);
    $form = ActiveForm::begin([
        'id' => 'form-input-example',
        'method' => 'post',
        'action' => $url,
        'enableClientValidation' => false,
        'enableAjaxValidation' => false
    ]);
    echo '<input type="hidden" name="attestationId" id="" value="'.$attestation->id.'">';
    echo '<table><tr><th>Студент</th><th>Оценка</th></tr>';
    foreach($attestation->group->students as $student){
        echo '<tr><td>'.$student->lName.' '.$student->fName.'</td>';
        echo '<td><input type="number" name="studentId'.$student->id.'" value="'.getMark($attestation, $student->id).'"></td></tr>';
    }
    echo '</table>';
    echo Html::submitButton('Сохранить', ['class' => 'btn btn-success']);
    ActiveForm::end();

     ?>
    </div>
</div>
