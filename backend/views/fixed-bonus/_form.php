<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FixedBonuses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fixed-bonuses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bonuses')->textInput() ?>

    <?= $form->field($model, 'min_count_deal')->textInput() ?>

    <?= $form->field($model, 'max_count_deal')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
