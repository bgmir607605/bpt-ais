<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        <?php
            if(!Yii::$app->user->isGuest){
                $url = Url::toRoute(['/user/alive']);
                echo "let timerId = setInterval(() => fetch('$url'), 300000);";
            }
        ?>
    </script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php

    $items = array();
    if(Yii::$app->user->isGuest){
        $itemsMenu[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        if(Yii::$app->user->identity->admin == 1){
            $itemsMenu[] = [
                'label' => 'Админ', 
                'items' => [
                    ['label' => 'Пользователи', 'url' => ['/admin/user']],
                    ['label' => 'Группы', 'url' => ['/admin/group']],
                ]
                ];
        }
        if(Yii::$app->user->identity->teacher == 1){
            $itemsMenu[] = [
                'label' => 'Преподаватель', 
                'items' => [
                    ['label' => 'Журналы', 'url' => ['/teacher/journal']],
                ]
                ];
        }
        if(Yii::$app->user->identity->student == 1){
            $itemsMenu[] = [
                'label' => 'Студент', 
                'items' => [
                    ['label' => 'Оценки', 'url' => ['/student/mark']],
                    ['label' => 'Пропуски', 'url' => ['/student/skip']],
                ]
                ];
        }
        if(Yii::$app->user->identity->schedule == 1){
            $itemsMenu[] = [
                'label' => 'Расписание', 
                'items' => [
                    ['label' => 'Направления', 'url' => ['/schedule/direct']],
                    ['label' => 'Группы', 'url' => ['/schedule/group']],
                    ['label' => 'Дисциплины', 'url' => ['/schedule/discipline']],
                    ['label' => 'Нагрузки', 'url' => ['/schedule/teacherload']],
                    ['label' => 'Расписание', 'url' => ['/schedule/schedule']],
                ]
            ];
        }
        if(Yii::$app->user->identity->inspector == 1){
            $itemsMenu[] = [
                'label' => 'Инспектор', 
                'items' => [
                    ['label' => 'Дн. оценки', 'url' => ['/inspector/journal/group-on-date']],
                ]
            ];
        }
        if(Yii::$app->user->identity->groupManager == 1){
            $itemsMenu[] = [
                'label' => 'Кл. рук.', 
                'items' => [
                    ['label' => 'Дн. оценки', 'url' => ['/group-manager/journal/group-on-date']],
                ]
            ];
        }
        // $itemsMenu[] = ['label' => 'Приёмная ком.', 'url' => ['/applicant-manager']];
        // $itemsMenu[] = ['label' => 'Кл.рук', 'url' => ['/group-manager']];
        $itemsMenu[] = ['label' => '<span class="glyphicon glyphicon-cog" title="Настройки"></span>', 'encode' => false, 'url' => ['/user/index']];
        $itemsMenu[] = (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            );
    }

    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $itemsMenu,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
