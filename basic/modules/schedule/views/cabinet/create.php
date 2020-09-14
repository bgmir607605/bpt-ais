<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cabinet */

$this->title = 'Create Cabinet';
$this->params['breadcrumbs'][] = ['label' => 'Cabinets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
