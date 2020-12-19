<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Студенты';
?>
<div class="site-index">

  

    <div class="body-content">
        <p>По умолчанию пароль 0000. </p>
        <p>Студент может авторизоваться в системе и сменить пароль.
        
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'lName',
                    'fName',
                    'mName',
                    'username',
                    'lastDateTime',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{reset-password}',
                        'buttons' => [
                            'reset-password' => function ($url, $data) {
                                return Html::a('<span class="glyphicon glyphicon-erase" title="Сбросить пароль"></span>', ['/group-manager/student/reset-password', 'id' => $data->id], 
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
</div>
