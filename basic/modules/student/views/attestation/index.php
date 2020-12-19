<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Семестровые аттестации '.$group->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'date',
            'semestrNumber',
            [
                'attribute' => 'Оценка',
                'value'     => function($data) use ($student) {
                    return $data->getValueOfMarkForStudent($student->id);
                },
            ]
        ],
    ]); ?>


</div>
