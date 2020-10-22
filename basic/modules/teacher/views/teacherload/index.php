<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учебные нагрузки ';
$this->params['breadcrumbs'][] = ['label' => 'Преподаватель'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            [
                'attribute' => 'groupId',
                'value' => 'group.name',
            ],
            [
                'attribute' => 'disciplineId',
                'value' => 'discipline.shortName',
            ],
            'total',
            'fSub',
            'sSub',
            'cons',
            'fSubKP',
            'sSubKP',
            'sr',
            'exam',

        ],
    ]); ?>


</div>
