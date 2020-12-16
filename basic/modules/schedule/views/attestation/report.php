<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TeacherloadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчёт по нагрузке '. $teacherload->user->lName. ' '. $teacherload->discipline->fullName;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <details>
        <summary>Информация о нагрузке</summary>
        <?= DetailView::widget([
        'model' => $teacherload,
        'attributes' => [
            'id',
            'userId',
            'groupId',
            'disciplineId',
            'total',
            'fSub',
            'sSub',
            'cons',
            'fSubKP',
            'sSubKP',
            'sr',
            'exam',
        ],
    ]) ?>
    </details>
    <details open>
        <summary>Сводная информация по ВСЕМ выданным часам</summary>
    
    <?php
//    Общие занятия
    $total = 0;
    $firstSubGroup = 0;
    $secondSubGroup = 0;
    $firstKP = 0;
    $secondKP = 0;
    $schedules = $searchModel->searchForTeacherload($teacherload->id, [])->query->all();
    foreach ($schedules as $item){
        if($item->type == ''){
            $total += $item->hours;
        }
        if($item->type == 'I'){
            $firstSubGroup += $item->hours;
            if($item->kp == 1){
                $firstKP += $item->hours;
            }
        }
        if($item->type == 'II'){
            $secondSubGroup += $item->hours;
            if($item->kp == 1){
                $secondKP += $item->hours;
            }
        }
    }
//    var_dump($schedules);
    echo 'Общие занятия: '.$total.' ч.<br>';
    echo 'Первая п/г: '.$firstSubGroup.' ч. (из них '.$firstKP.' ч. КП)<br>';
    echo 'Первая п/г: '.$secondSubGroup.' ч. (из них '.$secondKP.' ч. КП)<br>';
    ?>
    </details>
    <details open>
        <summary>Фильр выданных занятий</summary>
        <?php echo $this->render('_searchReport', ['model' => $searchModel, 'teacherload' => $teacherload]); ?>
    </details>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
             ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'date',
            'type',
//            'number',
//            'teacherLoadId',
            'hours',
            [
                'attribute' => 'cons',
                'format' => 'boolean',
            ],
            [
                'attribute' => 'forTeach',
                'format' => 'boolean',
            ],
            [
                'attribute' => 'kp',
                'format' => 'boolean',
            ],
            [
                'attribute' => 'sr',
                'format' => 'boolean',
            ],
            [
                'attribute' => 'replaceTeacherId',
                'value' => 'replaceTeacher.lName',
            ],

        ],
    ]); ?>


</div>
