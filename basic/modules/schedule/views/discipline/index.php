<?php

use yii\helpers\{Html, Url};
use yii\grid\GridView;
use Yii;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DisciplineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учебные дисциплины';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discipline-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'directs' => $directsListForForm,
        'formUrl' => Url::to(['/schedule/discipline/create',]),
    ]) ?>



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'shortName',
            'fullName',
            [
                'attribute' => 'directId',
                'value' => 'direct.name',
                'filter' => Html::activeDropDownList(
                    $searchModel, 'directId', 
                    $directsList, 
                    ['prompt' => '', 'class' => 'form-control form-control-sm']
                ),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
