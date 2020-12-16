<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Teacherload */

$this->title = 'Редактирование : ' . $model->name;
//$this->params['breadcrumbs'][] = ['label' => 'Teacherloads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Аттестации '.$model->group->name, 'url' => ['/schedule/attestation/for-group', 'groupId' => $model->group->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="teacherload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="attestation-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'type')->dropDownlist(['Дифференцированный зачёт' => 'Дифференцированный зачёт', 'ДКР' => 'ДКР', 'Квалификационный экзамен' => 'Квалификационный экзамен', 'Экзамен' => 'Экзамен' ] ) ?>
        <?= $form->field($model, 'date')->input('date') ?>
        <?= $form->field($model, 'semestrNumber')->input('number') ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div class="attestation-form">

            <?php $form2 = ActiveForm::begin([
                'method' => 'post',
                'action' => ['/schedule/attestation/add-teacherload'],
            ]); ?>

                <br>
                <select name="teacherloadId">
                    <?php
                    foreach ($model->group->teacherloads as $item){
                        // TODO sort by name
                        echo '<option value="'.$item->id.'">'.$item->discipline->shortName.' '.$item->user->initials.'</option>';
                    }
                    ?>
                </select>

                <input type="hidden" value="<?= $model->id ; ?>" name="attestationId">

            <div class="form-group">
                <?= Html::submitButton('Добавить нагрузку', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    <hr>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $data) {
                        return Html::a('<span class="glyphicon glyphicon-trash" title="Удалить"></span>', ['/schedule/attestation/remove-teacherload', 'teacherloadInAttestationId' => $data->id], 
                                [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'В аттестации должна оставаться минимум одна нагрузка, иначе сама аттестация будет удалена! Вы действительно хотите убрать нагрузку из аттестации?'),
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
