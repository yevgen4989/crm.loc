<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use kartik\icons\FontAwesomeAsset;

FontAwesomeAsset::register($this);

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
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);

    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'Доска и Рейтинг', 'url' => ['/site/rating']],
    ];
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menuItems,
    ]);

    $countArchive = \common\models\ManagerDashboard::find()
        ->where(['id_manager'=>\Yii::$app->user->id, 'for_trash' => 0])
        ->andWhere('status_order_id = 6 OR status_order_id = 7 OR status_order_id = 8')
        ->asArray()
        ->count();

    $countTrash = \common\models\ManagerDashboard::find()
        ->where(['id_manager'=>\Yii::$app->user->id])
        ->andWhere(['for_trash' => 1])
        ->asArray()
        ->count();


    $menuItems = [
        '<li><a href="/site/trash/">Корзина <span class="badge">'.$countTrash.'</span></a></li>',
        '<li><a href="/site/archive/">Архив <span class="badge">'.$countArchive.'</span></a></li>',
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Авторизоваться', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
