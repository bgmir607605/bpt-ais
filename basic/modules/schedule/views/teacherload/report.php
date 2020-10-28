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
        <summary>Фильр выданных занятий</summary>
        <?php echo $this->render('_searchReport', ['model' => $searchModel, 'teacherload' => $teacherload]); ?>
    </details>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'type',
//            'number',
//            'teacherLoadId',
            'cons',
            'forTeach',
            'hours',
            'kp',
            'sr',
            'replaceTeacherId',
            [
                'attribute' => 'replaceTeacherId',
                'value' => 'replaceTeacher.lName',
            ],

        ],
    ]); ?>


</div>
