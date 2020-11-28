<?php
use yii\helpers\Html;

$this->title = "Оценки";
// По возрастанию:
function my_cmp_function($a, $b){
    return ($a['date'] > $b['date']);
}
?>
<style>
    .my-mark {
        cursor: pointer;
        margin-left: 1px;
        margin-right: 1px;
    }
</style>
<div class="admin-default-index">
    <h1>Дисциплины</h1>
    <table class="table table-hover table-stripped table-bordered">
        <thead>
            <tr>
                <th scope="col" class="col-md-3">Дисциплина</th>
                <th scope="col" class="col-md-3">Преподаватель</th>
                <th scope="col" class="col-md-5">Оценки</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($container as $data) {?><tr>
                <?=Html::tag('td', $data['teacherload']->discipline->shortName, [ 'title' => $data['teacherload']->discipline->fullName ])?>
                <?=Html::tag('td', $data['teacherload']->user->getInitials(), [ 'title' => $data['teacherload']->user->getFullName() ])?>
                <td><?php
                    
                    uasort($data['items'], 'my_cmp_function');
                    
                    foreach($data['items'] as $item) {
                        echo Html::tag('span', $item['value'], [ 'title' => $item['date'], 'class' => 'my-mark', 'onClick' => 'hello(\''.$item['date'].'\')' ]);
                    }
                ?></td>
            </tr><?php } ?>
        </tbody>
    </table>
</div>
<script>
function hello(dateSchedule){
    alert(dateSchedule);
}
</script>