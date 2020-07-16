<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TypeContact */

$this->title = 'Create Type Contact';
$this->params['breadcrumbs'][] = ['label' => 'Type Contacts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="type-contact-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
