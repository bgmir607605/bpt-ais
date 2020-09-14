<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
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
                    ['label' => 'Кабинеты', 'url' => ['/schedule/cabinet']],
                    ['label' => 'Нагрузки', 'url' => ['/schedule/teacherload']],
                    ['label' => 'Расписание', 'url' => ['/schedule/schedule']],
                ]
            ];
        }
        // $itemsMenu[] = ['label' => 'Приёмная ком.', 'url' => ['/applicant-manager']];
        // $itemsMenu[] = ['label' => 'Кл.рук', 'url' => ['/group-manager']];
        // $itemsMenu[] = ['label' => 'Инспектор', 'url' => ['/inspector']];
        // $itemsMenu[] = ['label' => 'Студент', 'url' => ['/student']];
        // $itemsMenu[] = ['label' => 'Преподаватель', 'url' => ['/teacher']];
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
