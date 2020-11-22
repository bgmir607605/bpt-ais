<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = $teacherload->discipline->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Журналы', 'url' => ['/teacher/journal']];
$this->params['breadcrumbs'][] = ['label' => $teacherload->group->name, 'url' => ['/teacher/journal/for-group', 'groupId' => $teacherload->groupId]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

// TODO Вынести
function getMonthName($monthNumber = '')
{
    switch($monthNumber){
        case 1:
            return 'Январь';
        break;
        case 2:
            return 'Февраль';
        break;
        case 3:
            return 'Март';
        break;
        case 4:
            return 'Апрель';
        break;
        case 5:
            return 'Май';
        break;
        case 6:
            return 'Июнь';
        break;
        case 7:
            return 'Июль';
        break;
        case 8:
            return 'Август';
        break;
        case 9:
            return 'Сентябрь';
        break;
        case 10:
            return 'Октябрь';
        break;
        case 11:
            return 'Ноябрь';
        break;
        case 12:
            return 'Декабрь';
        break;
    }
}
?>
<style>
    th, td {
        text-align: center;
        min-width: 30px;
    }
    .firstColumn {
        word-break: break-all;
        position: absolute;
        width: 15em;
        margin-left: -15em;
        /* left: 0em; */
        /* top: auto; */
    }
    .tableWrap {
        overflow-x: scroll;
        margin-left: 15em;
        /* overflow-y: visible;
        padding: 0; */
    }
</style>
<div class="site-index">


    <div class="body-content">

        <?php
            // var_dump($teacherload);
            // var_dump($shedules);
            // var_dump($students);
            // var_dump($marks);
        ?>
        <h1><?php  // echo $teacherload->name ;?></h1>
        
        <!-- Форма выбора даты -->
        <?php
        $url = Url::toRoute(['/teacher/journal/teacherload']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <input type="hidden" name="id" id="" value="<?= $teacherload->id ; ?>">
        <input type="date" name="dateFrom" id="" value="<?= $dateFrom ; ?>"> - 
        <input type="date" name="dateTo" id="" value="<?= $dateTo ; ?>">
        <input type="hidden" name="all" id="" value="0">
        <?= Html::submitButton('Показать занятия за указанный период', ['class' => 'btn btn-info']) ?>
        <?php
        ActiveForm::end();
        ?>
        <?php
        $url = Url::toRoute(['/teacher/journal/teacherload']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <input type="hidden" name="id" id="" value="<?= $teacherload->id ; ?>">
        <input type="hidden" name="all" id="" value="1">
        <?= Html::submitButton('Показать все занятия', ['class' => 'btn btn-info']) ?>
        <?php
        ActiveForm::end();
        ?>
        <!-- Форма выбора даты -->
        <br>
        <p>В клетку можно ставить: 2, 3, 4, 5, н</p>
        <?php
        if(count($schedules) == 0){
            echo "<p>За указанный диапазон ($dateFrom - $dateTo) занятий не найдено.</p>";
        } else {
            echo '
                <input class="btn btn-success" type="button" value="Сохранить изменения" id="saveMarks">
                <br>
                <div class="tableWrap">
                    <table border="solid" id="mytable" width="100%">
                    <tr>
                    <th class="firstColumn"></th>';
            $month = '';
            $months = [];
                foreach($schedules as $schedule){
                    $sweetDate = strtotime($schedule->date);
                    $sweetDate = date('m', $sweetDate);
                    if($sweetDate != $month){
                        $months[$sweetDate] = 1;
                        $month = $sweetDate;
                    } else {
                        $months[$sweetDate] += 1;
                    }
                }
                foreach($months as $k => $v){
                    echo '<th colspan="'.$v.'">'.getMonthName($k).'</th>';
                }
            echo '</tr>
            <tr>
            <th class="firstColumn"></th>
            ';
            foreach($schedules as $schedule){
                $sweetDate = strtotime($schedule->date);
                $sweetDate = date('d', $sweetDate);
                echo '<th class="schedule" id="s'.$schedule->id.'">'.$sweetDate.'</th>';
            }
            echo '
            </tr>
            <tr>
            <th class="firstColumn"></th>
            ';
            foreach($schedules as $schedule){
                echo '<th>'.$schedule->type.'</th>';
            }
            echo '</tr>';
            foreach($students as $student){
                echo '<tr>';
                echo '<td class="firstColumn">'.$student->lName.' '.$student->fName.'</td>';
                foreach($schedules as $schedule){
                    echo '<td contenteditable class="marks" id="l'.$schedule->id.'s'.$student->id.'">'.getCellContent($schedule->id, $student->id, $marks, $skips).'</td>';
                }
                
                echo '</tr>';
            }
            echo '
                </table>
            </div>
            ';
        }
        ?>

    </div>
</div>

<?php
$saveURL = Url::to(['/teacher/journal/savemarks-and-skips']);
// TODO оптимизировать этот позор
function getCellContent($scheduleId, $studentId, $marks, $skips){
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

$this->registerJsFile('/js/file.js', [
    'depends' => [
        'yii\web\YiiAsset'
    ]
]);

$js = <<<JS
    $("#saveMarks").click(function() {
        var error = false;
        var marks = {};
        var skips = {};
        $('.schedule').each(function(i,elem) {
            idSchedule = elem.id.split('s')[1];
            marks[idSchedule] = [];
            skips[idSchedule] = [];
        });

        $('.marks').each(function(i,elem) {
            let mark = $(elem).html();
            mark = mark.split('<br>').join('');
            if(mark != ''){
                let idStudent = elem.id.split('s')[1];
                let idLesson = elem.id.split('s')[0].split('l')[1];
                // В цикле перебрать каждый символ строки с оценкой
                for(var i = 0; i < mark.length; i++){
                    // Если символ 0 2 3 4 5 - создать отдельную запись
                    if('02345'.indexOf(mark[i]) !== -1){
                        let markEnt = {
                            "studentId" : idStudent,
                            "scheduleId" : idLesson,
                            "value" : mark[i]
                        }
                        marks[markEnt["scheduleId"]].push(markEnt);
                    } else{
                        if(mark[i] == 'н' || mark[i] == 'Н'){
                            let skipEnt = {
                                "studentId" : idStudent,
                                "scheduleId" : idLesson
                            }
                            console.log(skipEnt);
                            console.log(skipEnt["scheduleId"]);
                            skips[skipEnt["scheduleId"]].push(skipEnt);
                        } else {
                            alert('Ошибка! Недопустимое значение оценки: ' + mark[i]);
                            invalidCell = document.getElementById('l' + idLesson + 's' + idStudent);
                            console.log(invalidCell);
                            invalidCell.style.backgroundColor = 'red';
                            error = true;
                            return;
                        }
                    }
                }
            }
        });
        if(error){
            return false;
        }
        JSONmarks = JSON.stringify(marks);
        JSONskips = JSON.stringify(skips);
        console.log(JSONmarks);
        console.log(JSONskips);
        $.ajax({
            async: false,			
            type: "POST",
            url: "$saveURL",
            data: 'marks=' + JSONmarks + '&skips=' + JSONskips,
            dataType:'text',
            error: function () {	
                alert('error');
            },
            success: function (response) {
                alert('Изменения успешно сохранены!');
                // window.location.href = window.location.href;
                console.log(response);
                
                
            }
        });
    });
    window.addEventListener('beforeunload', function(e) {
                e.returnValue = "";
                var message ="Все не сохранённые изменения будут утеряны. Действительно хотите выйти?"
                if(!confirm(message)) {
                    e.preventDefault();
                }
                return message;
            });
JS;
$this->registerJs($js);

?>