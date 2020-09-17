<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="admin-default-index">
    <h1><?= $group->name ?></h1>
    <ol>
    <hr>
    <?php
        foreach($students as $student){
            echo '<li>'.$student->lName. ' '. $student->fName.' '.$student->mName .'</li>';
        }
    ?>
    </ol>
    <hr>
    <?php
        $url = Url::toRoute(['/admin/group/add-students']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'post',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <?php echo Html::input('hidden', 'groupId', $group->id); ?>
        <label>prefix (is17, tm20, etc):</label><br>
        <?php echo Html::input('text', 'prefix'); ?><br>
        <label for="">Список студентов(Фамилия Имя Отчество;)</label><br>
        <?php echo Html::textarea('students', '', ['rows' => 10, 'cols' => 100] ); ?>
        <br>
            <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
        <?php
        ActiveForm::end();
        ?>
    
    </div>