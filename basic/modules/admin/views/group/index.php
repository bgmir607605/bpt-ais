<div class="admin-default-index">
    <h1>Группы</h1>
    <ul>
    <?php
        use yii\helpers\Html;
        echo '<ul>';
        foreach($groups as $group){
            echo '<li>'.Html::a($group->name, ['/admin/group/students', 'groupId' => $group->id], ['class' => 'profile-link']) .'</li>';
        }
        echo '</ul>';
    ?>
    </ul>
</div>
