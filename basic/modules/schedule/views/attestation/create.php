<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Teacherload */

$this->title = 'Добавление учебной нагрузки для '. $group->name;
$this->params['breadcrumbs'][] = ['label' => 'Teacherloads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherload-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'teachers' => $teachers,
        'group' => $group,
        'disciplines' => $disciplines,
    ]) ?>
</div>
