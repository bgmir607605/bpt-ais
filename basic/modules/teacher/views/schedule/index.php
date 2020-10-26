<?php

/* @var $this yii\web\View */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Моё расписание';
?>
<div class="site-index">


    <div class="body-content">
        <h1><?= $date ?></h1>
        <!-- Форма выбора даты -->
        <?php
        $url = Url::toRoute(['/teacher/schedule']);
        // TODO Вынести в компонент для других мест
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <?php echo Html::input('date', 'date', $date); ?>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-success']) ?>
        <?php
        ActiveForm::end();
        ?>
        <!-- Форма выбора даты -->
        <br>
        <?php
            foreach($schedules as $schedule){
                $linkText = $schedule->number.'. '. $schedule->teacherLoad->group->name. ' '
                . $schedule->teacherLoad->discipline->shortName . ' '. $schedule->type;
                echo Html::a($linkText, ['/teacher/journal/teacherload', 'id' => $schedule->teacherLoadId, 'dateFrom' => $date, 'dateTo' => $date], ['class' => 'profile-link']) .'<br>';
            }

        ?>

    </div>
</div>
