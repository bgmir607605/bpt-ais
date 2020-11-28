<?php
// TODO интерфейс
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Посещаемость';
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
        <?= Html::a('Скачать Excel файл', ['/inspector/skip/excel', 'start' => $start->format('Y-m-d')], ['class' => 'btn btn-info']) ?>
        <hr>
        <?php
        $url = Url::toRoute(['/inspector/skip/index']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <select name="groupId" id="">
                <?php
                    foreach($groups as $item){
                        if($item->id == $group->id){
                            echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                        } else {
                            echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                        }
                    }
                ?>
        </select>
        <select name="start" id="">
            <?php
                foreach($months as $k => $v){
                    if($k == $start->format('Y-m-d')){
                        echo '<option value="'.$k.'" selected>'.$v.'</option>';
                    } else {
                        echo '<option value="'.$k.'">'.$v.'</option>';
                    }
                }
            ?>
        </select>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-success']) ?>
        <?php
        ActiveForm::end();
        ?>
        
        <hr>
        
        <table border="solid">
            <tr>
                <th>Студент</th>
                <?php
                foreach ($period as $date) {
                    echo '<th>'.$date->format('d').'</th>';
                }
                echo '<th>Итого</th>';
                ?>
            </tr>
        <?php
        $totalSum = 0;
        foreach ($group->students as $student){
            echo '<tr><td>'.$student->lName;
            $sumForMonth = 0;
            foreach ($period as $date) {
                $skipsOnDate = getSumForStudentByDay($skips, $student->id, $date->format('Y-m-d'));
                $sumForMonth += $skipsOnDate;
                echo '<td>';
                if($skipsOnDate > 0){
                    echo $skipsOnDate;
                }
                echo '</td>';
            }
            echo '<th>'.$sumForMonth.'</th>';
            $totalSum += $sumForMonth;
            echo '</tr>';
        }
        ?>
        </table>
        <p>Итого: <?= $totalSum ?></p>
        <?php
        
        function getSumForStudentByDay($skips, $studentId, $date){
            $sum = 0;
            foreach ($skips as $skip){
                if($skip->studentId == $studentId && $skip->schedule->date == $date){
                    $sum += $skip->schedule->hours;
                }
            }
            return $sum;
        }
        ?>
    </div>
</div>
