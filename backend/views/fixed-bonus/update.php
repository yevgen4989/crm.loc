<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FixedBonuses */

$this->title = 'Update Fixed Bonuses: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fixed Bonuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fixed-bonuses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
