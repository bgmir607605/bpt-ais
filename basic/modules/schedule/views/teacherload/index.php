<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Учебные нагрузки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            // 'id',
            [
                'attribute' => 'userId',
                'value' => 'user.fio',
                'filter' => Html::activeDropDownList(
                    $searchModel, 'userId', 
                    $teachersList, 
                    ['prompt' => '', 'class' => 'form-control form-control-sm']
                ),
            ],
            [
                'attribute' => 'groupId',
                'value' => 'group.name',
                'filter' => Html::activeDropDownList(
                    $searchModel, 'groupId', 
                    $groupsList, 
                    ['prompt' => '', 'class' => 'form-control form-control-sm']
                ),
            ],
            [
                'attribute' => 'disciplineId',
                'value' => 'discipline.fullName',
                'filter' => Html::activeDropDownList(
                    $searchModel, 'disciplineId', 
                    $disciplinesList, 
                    ['prompt' => '', 'class' => 'form-control form-control-sm']
                ),
            ],
            
            // 'total',
            //'fSub',
            //'sSub',
            //'cons',
            //'fSubKP',
            //'sSubKP',
            //'sr',
            //'exam',

            // ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {report} {delete}',
                'buttons' => [
                    'report' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-list" title="Отчёт о выдаче"></span>',
//                            $url);
                        Url::toRoute(['/schedule/teacherload/report', 'teacherloadId' => $model->id]));
                    },
                ],
            ],
        ],
    ]); ?>


</div>
