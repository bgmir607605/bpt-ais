<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Teacherload */

$this->title = 'Редактирование : ' . $model->group->name.' '.$model->user->lName.' '.$model->discipline->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Teacherloads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="teacherload-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
        'group' => $model->group,
        'disciplines' => $disciplines,
    ]) ?>

</div>
