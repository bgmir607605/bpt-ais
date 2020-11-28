<?php
use yii\helpers\Html;

$this->title = "Оценки";
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
                    foreach($data['marks'] as $mark) {
                        echo Html::tag('span', $mark->value, [ 'title' => $mark->schedule->date, 'class' => 'my-mark' ]);
                    }
                ?></td>
            </tr><?php } ?>
        </tbody>
    </table>
</div>
