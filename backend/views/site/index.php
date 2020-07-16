<?php

use common\models\ManagerDashboard;
use common\models\PriceOrder;
use backend\assets\InputMaskPhoneAsset;
use kartik\dynagrid\DynaGrid;
use kartik\export\ExportMenu;
use kartik\field\FieldRange;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\data\Pagination;

InputMaskPhoneAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel common\models\ManagerDashboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Админ-панель';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="manager-dashboard-index">

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
    
    $kpiCurMonth = \common\models\KpiManager::find()->where("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();
    $managerArray = array();
    foreach ($managers as $key=>$manager){
        $managerArray[$key] = array(
            'id' => $manager['id'],
            'user_id'=> $manager['user_id'],
            'name' => $manager['name']
        );
    }

    $btnKPI = array();
    foreach ($managerArray as $key => $manager){
        foreach ($kpiCurMonth as $kpiManager){
            $btnKPI[$key] = $manager;
            if($manager['user_id'] == $kpiManager['manager_id']){
                $btnKPI[$key]['btn'] = $kpiManager;
            }
        }
    }?>
    <?$columns = [
        [
            'class' => 'kartik\grid\ActionColumn',
            'header'=>"Данные менеджера",
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'hAlign' => GridView::ALIGN_CENTER,
            'noWrap' => false,
            'width' => '5%',
            'buttons' => [
                'deals_manager' => function ($url, $model, $key) {
                    return Html::a (
                        '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span> ',
                        ['site/deals-manager', 'id' => $model->manager_id],
                        ['target'=>'_blank', 'data-pjax'=>"0", 'class' => 'btn btn-success btn-xs', 'title' => 'Сделки менеджера '.$model->manager_id,]
                    );
                },
                'kpi' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', '/admin/site/kpi-manager/?id='.$model->manager_id,
                        [
                            'title' => 'KPI Менеджера '.$model->manager_id,
                            'data-pjax' => '0',
                            'class' => 'btn btn-success btn-xs',
                            'target' => '_blank'
                        ]
                    );
                },
            ],
            'template' => '{kpi} {deals_manager}',
        ],
        [
            'columnKey' =>1,
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'class' => 'kartik\grid\ExpandRowColumn',
            'label' => 'Статистика и KPI',
            'value' => function ($model, $key, $index, $column) {
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function ($model, $key, $index, $column) {
                return Yii::$app->controller->renderPartial('_expand-row-details-manager', ['id' => $model->manager_id]);
            },
        ],
        [
            'attribute'=>'manager_id',
            'order'=>DynaGrid::ORDER_FIX_LEFT,
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map($managers, 'user_id', 'name'),
            'label'=>'Менеджер',
            'value' => function($model, $key, $index, $widget){
                return $model->manager_name.' ('.$model->manager_id.')';
            },
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => '', 'multiple' => false],
            'format' => 'raw'
        ],
        [
            'attribute' => 'month_commission',
            'label' => 'Сумма продаж за месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return Yii::$app->formatter->asCurrency($model->month_commission);
            }
        ],
        [
            'attribute' => 'month_prev_commission',
            'label' => 'Сумма продаж за предыдущий месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return Yii::$app->formatter->asCurrency($model->month_prev_commission);
            }
        ],
        [
            'attribute' => 'kpi_deals_month_result',
            'label' => 'KPI Сделки за месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return $model->kpi_deals_month_result.' / '.$model->kpi_deals_month;
            }
        ],
        [
            'attribute' => 'kpi_contacts_month_result',
            'label' => 'KPI Звонки за месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return $model->kpi_contacts_month_result.' / '.$model->kpi_contacts_month;
            }
        ],
        [
            'attribute' => 'kpi_kp_month_result',
            'label' => 'KPI КП за месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return $model->kpi_kp_month_result.' / '.$model->kpi_kp_month;
            }
        ],
        [
            'attribute' => 'kpi_sale_month_result',
            'label' => 'KPI Продаж за месяц',
            'filter' => false,
            'pageSummary' => false,
            'value' => function($model, $key, $index, $widget){
                return $model->kpi_sale_month_result.' / '.$model->kpi_sale_month;
            }
        ]


    ];
    Pjax::begin(['id' => 'deals-admin']);
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
        'options' => ['id' => 'deal-table-admin'],

    ]);
    if (substr($dynaGrid->theme, 0, 6) == 'simple') {
        $dynaGrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();
    Pjax::end();
    ?>


</div>
