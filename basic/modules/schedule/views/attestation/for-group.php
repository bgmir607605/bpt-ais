<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Семестровые аттестации '.$group->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <details>
        <summary>Добавить</summary>
        <div class="attestation-form">

            <?php $form = ActiveForm::begin([
                'method' => 'post',
                'action' => ['/schedule/attestation/create'],
            ]); ?>

                <br>
                <select name="teacherloadId">
                    <?php
                    foreach ($group->teacherloads as $item){
                        // TODO sort by name
                        echo '<option value="'.$item->id.'">'.$item->discipline->shortName.' '.$item->user->initials.'</option>';
                    }
                    ?>
                </select>

            <?= $form->field($model, 'type')->dropDownlist(['Дифференцированный зачёт' => 'Дифференцированный зачёт', 'ДКР' => 'ДКР', 'Квалификационный экзамен' => 'Квалификационный экзамен', 'Экзамен' => 'Экзамен' ] ) ?>
            <?= $form->field($model, 'date')->input('date') ?>
            <?= $form->field($model, 'semestrNumber')->input('number') ?>


            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
        
    </details>
    



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'date',
            'type',
            'semestrNumber',
            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
            ],
        ],
    ]); ?>


</div>
