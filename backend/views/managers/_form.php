<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="site-signup">
    <div class="row">
        <div class="col-lg-12">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'username')->textInput() ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'status')->dropDownList([
                '10' => 'Активный',
                '9' => 'Отключен',

            ], $params = ['prompt' => 'Выберите статус...']); ?>
            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'kpi_day_deals')->input('number', ['min'=>0])?>
            <?= $form->field($model, 'kpi_day_contacts')->input('number', ['min'=>0])?>
            <?= $form->field($model, 'kpi_day_kp')->input('number', ['min'=>0])?>
            <?= $form->field($model, 'kpi_day_sale')->input('number', ['min'=>0])?>

            <div class="form-group">
                <?= Html::submitButton('Создать и сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

