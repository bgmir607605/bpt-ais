<?php
use yii\helpers\Html;

$this->title = "Оценки";
?><div class="admin-default-index">
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
                        Html::tag('span', $mark->value, [ 'title' => $mark->schedule->date ]);
                    }
                ?></td>
            </tr><?php } ?>
        </tbody>
    </table>
</div>
