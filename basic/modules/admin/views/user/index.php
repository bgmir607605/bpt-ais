<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'fName',
            'mName',
            'lName',
            'username',
            'lastDateTime',
            //'password',
            //'admin',
            //'schedule',
            //'inspector',
            //'teacher',
            //'groupManager',
            //'applicantManager',
            //'student',
            //'deleted',

//            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {login-as} {reset-password} {delete}',
                'buttons' => [
                    'login-as' => function ($url,$model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-user" title="Залогиниться"></span>',
//                            $url);
                        Url::toRoute(['/admin/user/login-as', 'userId' => $model->id]));
                    },
                    'reset-password' => function ($url, $data) {
                        return Html::a('<span class="glyphicon glyphicon-erase" title="Сбросить пароль"></span>', ['/admin/user/reset-password', 'id' => $data->id], 
                                [
                                    'title' => Yii::t('yii', 'Сбросить пароль'),
                                    'aria-label' => Yii::t('yii', 'Сбросить пароль'),
                                    'data-confirm' => Yii::t('yii', 'Сбросить пароль для этого пользователя?'),
                                    'data-method' => 'post',
                                    'data-pjax' => '0',
                                ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
