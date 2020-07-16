<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KpiManager */

$this->title = 'Редактирование KPI Менеджера: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Kpi Managers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="kpi-manager-update container">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
