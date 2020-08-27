<?php

use yii\helpers\{Html, Url};

/* @var $this yii\web\View */
/* @var $model app\models\Discipline */

$this->title = 'Редактирование: ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Disciplines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="discipline-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'directs' => $directsListForForm,
        'formUrl' => Url::to(['/schedule/discipline/update', 'id' => $model->id]),
    ]) ?>

</div>
