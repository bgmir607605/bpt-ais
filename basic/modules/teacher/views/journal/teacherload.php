<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $teacherload->discipline->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = ['label' => 'Журналы', 'url' => ['/teacher/journal']];
$this->params['breadcrumbs'][] = ['label' => $teacherload->group->name, 'url' => ['/teacher/journal/for-group', 'groupId' => $teacherload->groupId]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<style>
    th, td {
        text-align: center;
    }
    .firstColumn {
        position: absolute;
        width: 10em;
        margin-left: -10em;
        /* left: 0em; */
        /* top: auto; */
    }
    .tableWrap {
        overflow-x: scroll;
        margin-left: 10em;
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
        <h2>В клетку можно ставить: 2, 3, 4, 5, н</h2>
        <input type="button" value="Сохранить изменения" id="saveMarks">
        <div class="tableWrap">
            <table border="solid" id="mytable" width="100%">
            <tr>
            <th></th>
            <?php
                foreach($schedules as $schedule){
                    $sweetDate = strtotime($schedule->date);
                    $sweetDate = date('d.m', $sweetDate);
                    echo '<th class="schedule" id="s'.$schedule->id.'">'.$sweetDate.'</th>';
                }
            ?>
            </tr>
            <tr>
            <th class="firstColumn"></th>
            <?php
                foreach($schedules as $schedule){
                    echo '<th>'.$schedule->type.'</th>';
                }
                ?>
            </tr>
            <?php
                foreach($students as $student){
                    echo '<tr>';
                    echo '<td class="firstColumn">'.$student->lName.' '.$student->fName.'</td>';
                    foreach($schedules as $schedule){
                        echo '<td contenteditable class="marks" id="l'.$schedule->id.'s'.$student->id.'">'.getCellContent($schedule->id, $student->id, $marks, $skips).'</td>';
                    }
                    
                    echo '</tr>';
                }
            ?>
            </table>
        </div>

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
                    // Если символ 2 3 4 5 - создать отдельную запись
                    if('2345'.indexOf(mark[i]) !== -1){
                        let markEnt = {
                            "studentId" : idStudent,
                            "scheduleId" : idLesson,
                            "value" : mark[i]
                        }
                        marks[markEnt["scheduleId"]].push(markEnt);
                    } else{
                        if(mark[i] == 'н'){
                            let skipEnt = {
                                "studentId" : idStudent,
                                "scheduleId" : idLesson
                            }
                            console.log(skipEnt);
                            console.log(skipEnt["scheduleId"]);
                            skips[skipEnt["scheduleId"]].push(skipEnt);
                        } else {
                            alert('Ошибка! Недопустимое значение оценки: ' + mark[i]);
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