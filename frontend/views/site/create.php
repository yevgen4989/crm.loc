<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ManagerDashboard */

$this->title = 'Создание новой сделки';
$this->params['breadcrumbs'][] = ['label' => 'Manager Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-dashboard-create container">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>


</div>
