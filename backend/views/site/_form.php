<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ManagerDashboard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manager-dashboard-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_manager')->textInput() ?>

    <?= $form->field($model, 'services_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'status_order_id')->textInput() ?>

    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bool_fixed_or_individ')->textInput() ?>

    <?= $form->field($model, 'tax')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>