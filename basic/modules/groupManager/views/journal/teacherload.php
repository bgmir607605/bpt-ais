<?php

/* @var $this yii\web\View */
$this->title = $teacherload->discipline->fullName. ' ('. $teacherload->user->initials.')';
$this->params['breadcrumbs'][] = ['label' => 'Кл. рук.'];
$this->params['breadcrumbs'][] = ['label' => 'Журнал', 'url' => ['/group-manager/journal']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

// TODO Вынести
function getMonthName($monthNumber = '')
{
    switch($monthNumber){
        case 1:
            return 'Январь';
        case 2:
            return 'Февраль';
        case 3:
            return 'Март';
        case 4:
            return 'Апрель';
        case 5:
            return 'Май';
        case 6:
            return 'Июнь';
        case 7:
            return 'Июль';
        case 8:
            return 'Август';
        case 9:
            return 'Сентябрь';
        case 10:
            return 'Октябрь';
        case 11:
            return 'Ноябрь';
        case 12:
            return 'Декабрь';
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
    }
    .tableWrap {
        overflow-x: scroll;
        margin-left: 15em;
    }
</style>
<div class="site-index">


    <div class="body-content">

        <h1><?php  // echo $teacherload->name ;?></h1>

        <?php
        if(count($schedules) == 0){
            echo "<p>За указанный диапазон ($dateFrom - $dateTo) занятий не найдено.</p>";
        } else {
            echo '
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
                echo '<th class="schedule">'.$sweetDate.'</th>';
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
            foreach($teacherload->group->students as $student){
                echo '<tr>';
                echo '<td class="firstColumn">'.$student->lName.' '.$student->fName.'</td>';
                foreach($schedules as $schedule){
                    echo '<td contenteditable class="marks">'.getCellContent($schedule, $student).'</td>';
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
// TODO оптимизировать этот позор
function getCellContent($schedule, $student){
    $result = '';
    foreach($schedule->marks as $mark){
        if($mark->studentId == $student->id){
            $result .= $mark->value;
        }
    }
    foreach($schedule->skips as $skip){
        if($skip->studentId == $student->id){
            $result .= 'н';
            break;
        }
    }
    return $result;
}
?>