<?php

use common\models\ManagerDashboardSearch;
use kartik\dynagrid\DynaGrid;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountSearch */
/* @var $dataProvider \yii2mod\query\ArrayQuery */

$this->title = 'База аккаунтов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kpi-manager-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?
    $columns = [
        [
            'columnKey' => 1,
            'order' => DynaGrid::ORDER_FIX_LEFT,
            'class' => 'kartik\grid\ExpandRowColumn',
            'label' => 'Сделки Аккаунта',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                $searchModel = new ManagerDashboardSearch();
                $searchModel->account_name = $model->account_name;
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                return Yii::$app->controller->renderPartial('_expand-row-details', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            },
        ],
        [
            'attribute' => 'account_name',
            'label' => 'Аккаунт',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(\common\models\ManagerDashboard::find()->select(['id','account_name'])->orderBy('account_name')->all(), 'id', 'account_name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw'
        ]
    ];

    Pjax::begin(['id' => 'kpi']);
    $dynaGrid = DynaGrid::begin([
        'theme' => 'panel-primary',
        'columns' => $columns,
        'storage' => DynaGrid::TYPE_DB,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => false,
            'pjax' => true,
            'condensed' => true,
            'bordered' => true,
            'responsive' => true,
            'hover' => true,
            'panel' => [
                'heading' => '',
                'before' => '',
                'after' => '',
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 1, 'class' => 'btn btn-primary', 'title' => 'Reset Grid'])
                ],
                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
                //'{export}',
            ],
        ],
        'options' => ['id' => 'kpi-table-admin'],

    ]);
    if (substr($dynaGrid->theme, 0, 6) == 'simple') {
        $dynaGrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();
    Pjax::end();
    ?>






</div>
