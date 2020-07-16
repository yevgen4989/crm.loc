<?php

use kartik\dynagrid\DynaGrid;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\KpiManagerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'KPI Менеджеров';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kpi-manager-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?

    $managers = \common\models\UserPersonalInfo::find()->all();
    foreach ($managers as $key=>$item){
        if(!\Yii::$app->authManager->getAssignment('manager', $item['user_id'])){
            unset($managers[$key]);
        }
    }
    foreach ($managers as $key=>$item){
        $managers[$key]['name'] = $item['name'].' ('.$item['user_id'].')';
    }

    $columns = [
        [
            'class' => 'kartik\grid\ActionColumn',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'hAlign' => GridView::ALIGN_CENTER,
            'noWrap' => false,
            'width' => '7%',
            'viewOptions' => ['title' => 'Детальная страница', 'class' => 'btn btn-success btn-xs'],
            'updateOptions' => ['title' => 'Редактирование', 'class' => 'btn btn-info btn-xs'],
            'deleteOptions' => ['title' => 'Удаление', 'class' => 'btn btn-danger btn-xs'],
        ],
        [
            'attribute'=>'manager_id',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map($managers, 'user_id', 'name'),
            'label'=>'Менеджер',
            'value' => function($model){
                //return $model->manager->userPersonalInfo->name.' ('.$model->manager_id.')';
                return $model->manager->username.' ('.$model->manager_id.')';
            },
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw'
        ],
        [
            'attribute' => 'kpi_deals_day',
            'label' => 'KPI Сделок на день',
            'filter' => false
        ],
        [
            'attribute' => 'kpi_contacts_day',
            'label' => 'KPI Звонков на день',
            'filter' => false
        ],
        [
            'attribute' => 'kpi_kp_day',
            'label' => 'KPI КП на день',
            'filter' => false
        ],
        [
            'attribute' => 'kpi_sale_day',
            'label' => 'KPI Сделок на день',
            'filter' => false
        ],
        [
            'attribute' => 'date',
            'label' => 'Дата',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'pageSummary' => false,
            'width' => '11%',
            'filterType' => GridView::FILTER_DATE,
            'format'=>['DateTime','dd.MM.Y'],
            'filterWidgetOptions' => ([
                'attribute' => 'date',
                'convertFormat' => false,
                'pluginOptions' => [
                    'minViewMode' => 1,
                    'todayHighlight' => true,
                    'separator' => ' - ',
                    'format' => 'mm.yyyy',
                    'locale' => [
                        'format' => 'mm.yyyy'
                    ]
                ],
            ]),
        ],
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
