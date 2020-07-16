<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\StatusOrder */

$this->title = 'Create Status Order';
$this->params['breadcrumbs'][] = ['label' => 'Status Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
