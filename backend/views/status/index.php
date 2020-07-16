<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StatusOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Status Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Status Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'active',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
