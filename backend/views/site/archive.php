<?php

use common\models\ManagerDashboard;
use common\models\PriceOrder;
use frontend\assets\InputMaskPhoneAsset;
use kartik\dynagrid\DynaGrid;
use kartik\export\ExportMenu;
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

$this->title = 'Архив';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="manager-dashboard-index">

    <?php
    $script2 = <<< JS
	$("a#deal-delete").on('click',function(e){
		e.preventDefault;
		var keys = $('.grid-view').yiiGridView('getSelectedRows');
		if(keys.length == 0){
		    alert("Ничего не выбрано.");
		}else {
		    $.post('/admin/site/mass-delete-finally', {keylist : keys}, function(data) {});
		    return false;   
		}
	});
    
JS;
    $this->registerJs($script2, \yii\web\View::POS_END);
    ?>

    <?
    $columns = [
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'order' => DynaGrid::ORDER_FIX_LEFT,
            'rowSelectedClass' => GridView::TYPE_INFO,
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'noWrap' => true,
            'order' => DynaGrid::ORDER_FIX_LEFT,
            'viewOptions' => ['title' => 'Детальная страница сделки', 'class' => 'btn btn-success btn-xs'],
            'updateOptions' => ['title' => 'Редактирование сделки', 'class' => 'btn btn-info btn-xs'],
            'deleteOptions' => ['title' => 'В корзину', 'class' => 'btn btn-danger btn-xs'],

            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::to(['site/view-deals', 'id' => $model->id]),
                        [
                            'title' => 'Детальная страница сделки',
                            'data-pjax' => '0',
                            'class' => 'btn btn-success btn-xs',
                            'target' => '_blank'
                        ]
                    );
                },

                'update' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['site/update-deals', 'id' => $model->id]),
                        [
                            'title' => 'Редактирование сделки',
                            'data-pjax' => '0',
                            'class' => 'btn btn-info btn-xs',
                            'target' => '_blank'
                        ]
                    );
                },
            ],
            'template' => '{view} {update} {return} {delete}',
        ],
        [
            'attribute' => 'id',
            'filterType' => GridView::FILTER_NUMBER,
            'format' => 'integer',
            'pageSummary' => false,
        ],
        [
            'attribute' => 'date',
            'label' => 'Дата',
            'pageSummary' => false,
            'width' => '11%',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'format'=>['DateTime','dd.MM.Y HH:mm:ss'],
            'filterWidgetOptions' => ([
                'includeMonthsFilter'=>true,
                'attribute' => 'date',
                'presetDropdown' => true,
                'convertFormat' => false,
                'pickerIcon' => '',
                'pluginOptions' => [
                    'separator' => ' - ',
                    'format' => 'DD.MM.YYYY',
                    'locale' => [
                        'format' => 'DD.MM.YYYY'
                    ]
                ],
            ]),
        ],
        [
            'attribute' => 'status_order_id',
            'vAlign' => 'middle',
            'label' => 'Стадия сделки',
            'width' => '180px',
            'value' => function ($model, $key, $index, $widget) {
                if($model->status_order_id == 1){
                    return '<span class="label label-success">'.$model->statusOrder->name.'</span>';
                }elseif ($model->status_order_id == 2){
                    return '<span class="label label-warning">'.$model->statusOrder->name.'</span>';
                }elseif ($model->status_order_id == 3){
                    return '<span class="label label-warning">'.$model->statusOrder->name.'</span>';
                }
                elseif ($model->status_order_id == 4){
                    return '<span class="label label-info">'.$model->statusOrder->name.'</span>';
                }
                elseif ($model->status_order_id == 5){
                    return '<span class="label label-light">'.$model->statusOrder->name.'</span>';
                }
                elseif ($model->status_order_id == 6){
                    return '<span class="label label-primary">'.$model->statusOrder->name.'</span>';
                }
                elseif ($model->status_order_id == 7){
                    return '<span class="label label-danger">'.$model->statusOrder->name.'</span>';
                }
                elseif ($model->status_order_id == 8){
                    return '<span class="label label-dark">'.$model->statusOrder->name.'</span>';
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(\common\models\StatusOrder::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Стадия сделки', 'multiple' => false],
            'format' => 'raw'

        ],
        [
            'attribute' => 'text',
            'label' => 'Комментарий',
            'filter' => true,
            'pageSummary' => false,
            'value' => function (ManagerDashboard $dashboard) {
                return $dashboard->commentDeals[count($dashboard->commentDeals) - 1]->text;
            },
        ],
        [
            'attribute' => 'services_id',
            'label' => 'Услуга',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(\common\models\Services::find()->asArray()->all(), 'id', 'name'),
            'pageSummary' => false,
            'value' => function (ManagerDashboard $dashboard) {
                return $dashboard->services->name;
            },
        ],
        [
            'attribute' => 'name',
            'label' => 'Имя',
            'filter' => true,
            'pageSummary' => false,
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id'=>$dashboard->id_manager, 'deal_id'=>$dashboard->id, 'lpr_bool'=>1])->asArray()->all();
                foreach ($contacts as $record){
                    return $record['name'];
                }

            },
        ],
        [
            'attribute' => 'type_contact_id',
            'label' => 'Тип контакта',
            'pageSummary' => false,
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id' => $dashboard->id_manager, 'deal_id' => $dashboard->id, 'lpr_bool' => 1])->asArray()->all();
                foreach ($contacts as $record) {
                    if ($record['type_contact_id'] == 1) {
                        return '<span class="label label-primary">' . \common\models\TypeContact::findOne($record['type_contact_id'])->name . '</span>';

                    } elseif ($record['type_contact_id'] == 2) {
                        return '<span class="label label-success">' . \common\models\TypeContact::findOne($record['type_contact_id'])->name . '</span>';
                    }
                }
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map(\common\models\TypeContact::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Тип контакта', 'multiple' => false],
            'format' => 'raw'

        ],
        [
            'attribute' => 'phone',
            'label' => 'Телефон',
            'pageSummary' => false,
            'format' => 'raw',
            'filter'=> MaskedInput::widget([
                'name' => 'phone',
                'clientOptions' => [
                    'alias' =>  'phone',
                ],
            ]),
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id'=>$dashboard->id_manager, 'deal_id'=>$dashboard->id, 'lpr_bool'=>1])->asArray()->all();
                foreach ($contacts as $record){
                    return '<a href="tel:'.$record['phone'].'">'.$record['phone'].'</a>';
                }
            },
        ],
        [
            'attribute' => 'email',
            'label' => 'Email',
            'filter'=> MaskedInput::widget([
                'name' => 'email',
                'clientOptions' => [
                    'alias' =>  'email',
                ],
            ]),
            'pageSummary' => false,
            'format' => 'raw',
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id'=>$dashboard->id_manager, 'deal_id'=>$dashboard->id, 'lpr_bool'=>1])->asArray()->all();
                foreach ($contacts as $record){
                    return '<a href="mailto:'.$record['email'].'">'.$record['email'].'</a>';
                }
            },
        ],
        [
            'attribute' => 'price',
            'label' => 'Сумма сделки',
            'pageSummary' => false,
            'format' => 'raw',
            'value' => function (\common\models\ManagerDashboard $dashboard) {
                $priceOrder = PriceOrder::find()->where(['order_id' => $dashboard->id])->asArray()->all();
                $priceSum = 0;

                foreach ($priceOrder as $value) {
                    $priceSum += $value['price'];
                }
                return '<b>' . Yii::$app->formatter->asCurrency($priceSum) . '</b>';
            }
        ],
        [
            'attribute' => 'bool_fixed_or_individ',
            'label' => 'Тип коммисии',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => ArrayHelper::map([
                ['name'=> 'Фиксированная', 'id' => 0],
                ['name'=> 'Индивидуальная', 'id' => 1]
            ], 'id', 'name'),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => 'Тип коммисии', 'multiple' => false],
            'format' => 'raw',
            'value' => function($model){
                if($model->bool_fixed_or_individ == 0){
                    return 'Фиксированная';
                }
                else if($model->bool_fixed_or_individ == 1){
                    return 'Индивидуальная';
                }
            }
        ],
        [
            'attribute' => 'tax',
            'label' => 'Коммисия',
            'filterType' => GridView::FILTER_NUMBER,
            'pageSummary' => true,
            'format' => 'raw',
            'value' => function (\common\models\ManagerDashboard $dashboard) {
                return "<b>" . Yii::$app->formatter->asCurrency($dashboard->tax). "</b>";
            }

        ]
    ];
    Pjax::begin(['id' => 'deals']);
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
                    Html::a('<i class="glyphicon glyphicon-trash"></i> В корзину', 'javascript:void(0)', ['data-pjax' => 1, 'class' => 'btn btn-danger', 'title' => 'В корзину', 'id'=>'deal-delete',]) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 1, 'class' => 'btn btn-primary', 'title' => 'Reset Grid'])
                ],
                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
                //'{export}',
            ],
        ],
        'options' => ['id' => 'deal-table'],

    ]);
    if (substr($dynaGrid->theme, 0, 6) == 'simple') {
        $dynaGrid->gridOptions['panel'] = false;
    }
    DynaGrid::end();
    Pjax::end();
    ?>



</div>