<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KpiManager */

$this->title = 'Создание KPI Менеджера';
$this->params['breadcrumbs'][] = ['label' => 'Kpi Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kpi-manager-create container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
