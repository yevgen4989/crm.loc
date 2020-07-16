<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ManagerDashboardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manager-dashboard-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_manager') ?>

    <?= $form->field($model, 'services_id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'status_order_id') ?>

    <?php // echo $form->field($model, 'account_name') ?>

    <?php // echo $form->field($model, 'bool_fixed_or_individ') ?>

    <?php // echo $form->field($model, 'tax') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
