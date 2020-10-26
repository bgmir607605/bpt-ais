<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Дневные оценки';
?>
<style>
    th, td {
    text-align: center;
}
</style>
<div class="site-index">

  

    <div class="body-content">
        <?php
        if(!empty($group)){
            echo '<h1>Оценки '.$group->name. ' ' .$date.'</h1>';
        } else{
            echo '<h1>Оценки '.$date.'</h1>';
        }
        ?>
        <!-- Форма выбора даты -->
        <?php
        $url = Url::toRoute(['/inspector/journal/group-on-date']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <input type="date" name="date" id="" value="<?= $date ; ?>">
        <select name="groupId" id="">
        <option value="">----</option>
                <?php
                    foreach($groups as $item){
                        if(!empty($group) && $item->id == $group->id){
                            echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                        } else {
                            echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                        }
                    }
                ?>
        </select>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-success']) ?>
        <?php
        ActiveForm::end();
        ?>
        <!-- Форма выбора даты -->
        <?php
        echo '<table border="solid">';
        echo '<tr>';
        echo '<th></th>';
        foreach($schedules as $schedule){
            echo '<th>'.$schedule->number.' пара <br>'.$schedule->teacherLoad->discipline->shortName.' '.$schedule->type.
            '<br>('.$schedule->teacherLoad->user->lName;
            if(!empty($schedule->replaceTeacher)){
                echo '->'.$schedule->replaceTeacher->lName;
            }
            echo ')</th>';
        }
        echo '</tr>';
        foreach($students as $student){
            echo '<tr>';
            echo '<td>'.$student->lName.' '.$student->fName.'</td>';
            foreach($schedules as $schedule){
                echo '<td>';
                echo getContentOfCell($schedule->id, $student->id, $marks, $skips);
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        function getContentOfCell($scheduleId, $studentId, $marks, $skips){
            $result = '';
            foreach($marks as $mark){
                if($mark["studentId"] == $studentId && $mark["scheduleId"] == $scheduleId){
                    $result .= $mark["value"];
                }
            }
            foreach($skips as $skip){
                if($skip["studentId"] == $studentId && $skip["scheduleId"] == $scheduleId){
                    $result .= 'н';
                }
            }
            return $result;
        }
    ?>
    </div>
</div>
