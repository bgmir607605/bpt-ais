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
</style>
<div class="site-index">

  

    <div class="body-content">

    <?php
        if(!empty($group)){
            echo '<h1>Мониторинг '.$group->name.'</h1>';
        } else{
            echo '<h1>Мониторинг </h1>';
        }
        ?>
        <?= Html::a('Скачать Excel файл со всеми группами', ['/inspector/monitoring/excel'], ['class' => 'btn btn-info']) ?>
        <span><?php
        if(!empty($logLastUpdate)){
            echo '('.$logLastUpdate->datetime.' '. $logLastUpdate->userId.')';
        }
        ?></span>
        <hr>
        <!-- Форма выбора даты -->
        <?php
        $url = Url::toRoute(['/inspector/monitoring']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
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

        
        <?php
        if(!empty($group)){
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
        }


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
