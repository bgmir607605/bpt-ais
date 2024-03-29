<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учебные нагрузки '.$group->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить', ['create', 'groupId' => $group->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            // 'id',
            [
                'attribute' => 'userId',
                'value' => 'user.fio',
            ],
            [
                'attribute' => 'groupId',
                'value' => 'group.name',
            ],
            [
                'attribute' => 'disciplineId',
                'value' => 'discipline.shortName',
            ],
            // 'total',
            //'fSub',
            //'sSub',
            //'cons',
            //'fSubKP',
            //'sSubKP',
            //'sr',
            //'exam',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
