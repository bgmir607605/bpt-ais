<div class="admin-default-index">
    <h1>Пропуски занятий</h1>
    <ul>
    <?php
        use yii\helpers\Html;
        foreach($skips as $skip){
            echo '<p>'.$skip->schedule->date.' ('.$skip->schedule->number.' пара) '.$skip->schedule->hours.' ч.</p>';
        }
    ?>
    </ul>
</div>
