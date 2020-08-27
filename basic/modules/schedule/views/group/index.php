<?php

use yii\helpers\{Html, Url};
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учебные группы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            // 'directId',
            [
                'attribute' => 'directId',
                'value' => 'direct.code',
            ],
            [
                'attribute' => 'directId',
                'value' => 'direct.name',
            ],
            'course',

            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {certificatons} {report} {delete}',
                'buttons' => [
                    'report' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-list"></span>',
//                            $url);
                        Url::toRoute(['teacherload/list-for-group', 'groupId' => $model->id]));
                    },
                    'certificatons' => function ($url,$model) {
                        return Html::a(
                            '<span class="">Аттестация</span>',
//                            $url);
                        Url::toRoute(['certification/list-for-group', 'groupId' => $model->id]));
                    },
                ],
            ],
        ],
    ]); ?>


</div>
