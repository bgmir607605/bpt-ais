<?php
/* @var $this yii\web\View */
$this->title = 'Мониторинг';
?>
<div class="admin-default-index">
    <div class="body-content">
    <h1>Мониторинг</h1>
    <?php
        
        echo '<table border="solid">';
        echo '<tr><th>Предмет</th><th>Оценка</th></tr>';
        foreach($monitoringMarks as $item){
            echo '<tr>';
            echo '<td>';
            echo $item->teacherLoad->discipline->fullName;
            echo '</td>';
            echo '<td>';
            echo $item->mark;
            echo '</td>';
            
            echo '</tr>';
        }
        echo '</table>';
    ?>
    </div>
</div>
