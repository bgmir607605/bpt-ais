<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = $teacherload->discipline->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Мониторинг', 'url' => ['/teacher/monitoring']];
$this->params['breadcrumbs'][] = ['label' => $teacherload->group->name, 'url' => ['/teacher/monitoring/for-group', 'groupId' => $teacherload->groupId]];
$this->params['breadcrumbs'][] = ['label' => $this->title];


?>
<style>
input {
    color: red;
}
table, td, tr, th {
    border: outset;
}
</style>
<div class="site-index">


    <div class="body-content">

    <p>Подсказки в текстовых полях соответствуют среденму арифметическому всех оценок студента по данной нагрузке.</p>
    <p>Вы можете воспользоваться подсказками или ввести собственные значения.</p>
    <p>Чтобы не спутаться: подсказки чёрного цвета, оценки - красного.</p>
    <p>В базе данных будут храниться только те значения, которые Вы введёте (красные).</p>
    <?php
    function getMonitoringMark($monitoringMarks, $userId){
        $res = '';
        foreach($monitoringMarks as $item){
            if($item->userId == $userId){
                return $item->mark;
            }
        }
        return $res;

    }

    $url = Url::toRoute(['/teacher/monitoring/save']);
    $form = ActiveForm::begin([
        'id' => 'form-input-example',
        'method' => 'post',
        'action' => $url,
        'enableClientValidation' => false,
        'enableAjaxValidation' => false
    ]);
    echo '<input type="hidden" name="teacherloadId" id="" value="'.$teacherload->id.'">';
    echo '<table><tr><th>Студент</th><th>Оценки</th><th>Мониторинг</th></tr>';
    foreach($students as $student){
        echo '<tr><td>'.$student->lName.' '.$student->fName.'</td><td>';
        $sum = 0;
        $num = 0;
        for($i = 0; $i < count($marks); $i++){
            if($marks[$i]->studentId == $student->id){
                echo $marks[$i]->value;
                $sum += $marks[$i]->value;
                $num++;
            };
        }
        $avg = $sum / $num;
        echo '</td><td><input type="text" name="studentId'.$student->id.'" placeholder="'.round($avg, 2).'" value="'.getMonitoringMark($monitoringMarks, $student->id).'"></td></tr>';
    }
    echo '</table>';
    echo Html::submitButton('Сохранить', ['class' => 'btn btn-success']);
    ActiveForm::end();

     ?>
    </div>
</div>
