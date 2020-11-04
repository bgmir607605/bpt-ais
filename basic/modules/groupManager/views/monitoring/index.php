<?php
// TODO интерфейс
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Мониторинг';
?>
<style>
    th, td {
        text-align: center;
    }
    tr:nth-child(odd) { 
        background-color: #daffc3; 
    }
</style>
<div class="site-index">

  

    <div class="body-content">
        <?= Html::a('Скачать Excel файл', ['/group-manager/monitoring/excel'], ['class' => 'btn btn-info']) ?>
        <hr>
        <?php
        echo '<table border="solid">';
        echo '<tr>';
        echo '<th></th>';
        foreach($teacherloads as $teacherload){
            echo '<th>'.$teacherload->discipline->shortName.'<br>('.$teacherload->user->lName.')</th>';
        }
        echo '</tr>';
        foreach($group->students as $student){
            echo '<tr>';
            echo '<td>'.$student->lName.' '.$student->fName.'</td>';
            foreach($teacherloads as $teacherload){
                echo '<td>';
                echo getContentOfCell($teacherload->id, $student->id, $monitoringMarks);
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';

        function getContentOfCell($teacherloadId, $studentId, $marks){
            $result = '';
            foreach($marks as $mark){
                if($mark->userId == $studentId && $mark->teacherLoadId == $teacherloadId){
                    $result .= $mark->mark;
                break;
                }
            }
            return $result;
        }
        ?>
    </div>
</div>
