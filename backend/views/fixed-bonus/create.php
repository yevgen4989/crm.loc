<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FixedBonuses */

$this->title = 'Create Fixed Bonuses';
$this->params['breadcrumbs'][] = ['label' => 'Fixed Bonuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixed-bonuses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
