<div class="admin-default-index">
    <h1>Дисциплины</h1>
    <ul>
    <?php
        use yii\helpers\Html;
        foreach($container as $record){
            echo '<p>'.$record['teacherload']->discipline->shortName.'</p>';
            foreach($record['marks'] as $mark){
                echo '<span style="min-width: 15px; cursor: pointer; display: inline-block;" title="'.$mark->schedule->date.'">'.$mark->value.'</span>';
            }
        }
    ?>
    </ul>
</div>
