<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Direct */

$this->title = 'Добавление направления подготовки';
$this->params['breadcrumbs'][] = ['label' => 'Directs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="direct-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
