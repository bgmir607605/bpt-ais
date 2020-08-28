<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DirectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Направления подготовки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direct-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'code',
            'name',
            'type',
            'forApplicant',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
