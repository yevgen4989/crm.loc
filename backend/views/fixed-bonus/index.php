<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\FixedBonusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fixed Bonuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixed-bonuses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fixed Bonuses', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'bonuses',
            'min_count_deal',
            'max_count_deal',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
