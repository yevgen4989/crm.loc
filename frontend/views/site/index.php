<?php

use common\models\ManagerDashboard;
use common\models\PriceOrder;
use frontend\assets\InputMaskPhoneAsset;
use kartik\dynagrid\DynaGrid;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\data\Pagination;

InputMaskPhoneAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel common\models\ManagerDashboardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Главная';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="manager-dashboard-index">

    <div class="row" style="margin-top: 4rem">
        <div class="col-lg-5 col-md-5 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Сводная статистика</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Период</th>
                                <th scope="col">Сделки</th>
                                <th scope="col">Звонки</th>
                                <th scope="col">В работе</th>
                                <th scope="col">Отказы</th>
                                <th scope="col">Продажи</th>
                            </tr>
                        </thead>
                        <tbody>
                        <? $arStatistics = \common\models\Statistics::getStatistics(\Yii::$app->user->id);
                        foreach ($arStatistics as $key => $statisticItem) {
                            ?>
                            <tr <? if ($key == 'all'){
                                ?>class="info"<? } ?>>
                                <td><b><?= $statisticItem['text'] ?></b></td>
                                <td><?= $statisticItem['deal'] ?></td>
                                <td><?= $statisticItem['contact'] ?></td>
                                <td><?= $statisticItem['in_work'] ?></td>
                                <td><?= $statisticItem['failure'] ?></td>
                                <td><?= $statisticItem['sale'] ?></td>
                            </tr>
                        <? } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Личные KPI</div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th scope="col" width="20%">Период</th>
                            <th scope="col" width="20%">KPI сделки</th>
                            <th scope="col" width="20%">KPI звонки</th>
                            <th scope="col" width="20%">KPI КП</th>
                            <th scope="col" width="20%">KPI продаж</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        $kpiManager = \common\models\KpiManager::generateArrayResult(\Yii::$app->user->id);

                            if($kpiManager['today']['today_kpi_deals'] == 0 ||
                                $kpiManager['today']['today_kpi_contacts'] == 0 ||
                                $kpiManager['today']['today_kpi_kp'] == 0 ||
                                $kpiManager['today']['today_kpi_sale'] == 0){?>
                                <tr>
                                    <td colspan="5">
                                        <p class="text-center text-danger">
                                            <b>Личный KPI не установлен!<br>
                                                Обратитесь к администратору!
                                            </b>
                                        </p>
                                    </td>
                                </tr>
                            <?}else{?>
                                <tr>
                                    <td>На сегодня:</td>
                                    <td><b><?=$kpiManager['kpi']['today']['today_kpi_deals']?></b> / <b><?= $kpiManager['today']['today_kpi_deals'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['today']['today_kpi_contacts']?></b> / <b><?= $kpiManager['today']['today_kpi_contacts'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['today']['today_kpi_sale']?></b> / <b><?=$kpiManager['today']['today_kpi_kp'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['today']['today_kpi_sale']?></b> / <b><?=$kpiManager['today']['today_kpi_sale'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>На неделю:</td>
                                    <td><b><?=$kpiManager['kpi']['week']['week_kpi_deals']?></b> / <b><?= $kpiManager['week']['week_kpi_deals'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['week']['week_kpi_contacts']?></b> / <b><?= $kpiManager['week']['week_kpi_contacts'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['week']['week_kpi_kp']?></b> / <b><?= $kpiManager['week']['week_kpi_kp'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['week']['week_kpi_sale']?></b> / <b><?= $kpiManager['week']['week_kpi_sale'] ?></b></td>
                                </tr>
                                <tr>
                                    <td>На текущий месяц:</td>
                                    <td><b><?=$kpiManager['kpi']['month']['month_kpi_deals']?></b> / <b><?= $kpiManager['month']['month_kpi_deals'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['month']['month_kpi_contacts']?></b> / <b><?= $kpiManager['month']['month_kpi_contacts'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['month']['month_kpi_kp']?></b> / <b><?=$kpiManager['month']['month_kpi_kp'] ?></b></td>
                                    <td><b><?=$kpiManager['kpi']['month']['month_kpi_sale']?></b> / <b><?=$kpiManager['month']['month_kpi_sale'] ?></b></td>
                                </tr>
                            <?}

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading">Мотивация</div>
                <table class="table table-sm table-bordered table-hover table-striped">
                    <tbody>
                    <? $fixedBonuses = \common\models\FixedBonuses::find()->asArray()->all();
                    foreach ($fixedBonuses as $item) {
                        ?>
                        <tr>
                            <td><small><?= $item['name'] ?></small></td>
                            <td><small><?= $item['bonuses'] ?>%</small></td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel panel-success">
                <div class="panel-heading">Агентская комиссия</div>
                <table class="table table-sm table-bordered table-hover table-striped">
                    <tbody>
                    <tr>
                        <td>В процессе:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()->where(['id_manager'=>\Yii::$app->user->id])->andWhere('status_order_id < 6')->select(['tax'])->asArray()->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                        ?></td>
                    </tr>
                    <tr>
                        <td>За сегодня:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()
                                ->joinWith('historyDeals')
                                ->where(['id_manager'=>\Yii::$app->user->id, 'status_order_id' => 6])
                                ->andWhere(['history_deal.new_status_id' => 6, 'DATE(history_deal.date)'=>date('Y-m-d')])
                                ->select(['tax'])
                                ->asArray()
                                ->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                        ?></td>
                    </tr>
                    <tr>
                        <td>За неделю:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()
                                ->joinWith('historyDeals')
                                ->where(['id_manager'=>\Yii::$app->user->id, 'status_order_id' => 6])
                                ->andWhere(['history_deal.new_status_id' => 6])->andWhere("WEEK(DATE(history_deal.date)) = WEEK(NOW())")
                                ->select(['tax'])
                                ->asArray()
                                ->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                            ?></td>
                    </tr>
                    <tr>
                        <td>За месяц:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()
                                ->joinWith('historyDeals')
                                ->where(['id_manager'=>\Yii::$app->user->id, 'status_order_id' => 6])
                                ->andWhere(['history_deal.new_status_id' => 6])
                                ->andWhere("MONTH(DATE(history_deal.date)) = MONTH(NOW())")
                                ->select(['tax'])
                                ->asArray()
                                ->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                        ?></td>
                    </tr>
                    <tr>
                        <td>За прошлый месяц:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()
                                ->joinWith('historyDeals')
                                ->where(['id_manager'=>\Yii::$app->user->id, 'status_order_id' => 6])
                                ->andWhere(['history_deal.new_status_id' => 6])
                                ->andWhere("MONTH(DATE(history_deal.date)) = MONTH(NOW())-1")
                                ->select(['tax'])
                                ->asArray()
                                ->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                            ?></td>
                    </tr>
                    <tr class="info">
                        <td>Всего:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()
                                ->joinWith('historyDeals')
                                ->where(['id_manager'=>\Yii::$app->user->id, 'status_order_id' => 6])
                                ->andWhere(['history_deal.new_status_id' => 6])
                                ->select(['tax'])
                                ->asArray()
                                ->all();
                            $preTax = 0;
                            foreach ($arrTax as $key=>$item){
                                $preTax += $item['tax'];
                            }
                            echo '<b>'.Yii::$app->formatter->asCurrency($preTax).'</b>';
                            ?></td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    $script2 = <<< JS
	$("a#deal-delete").on('click',function(e){
		e.preventDefault;
		var keys = $('.grid-view').yiiGridView('getSelectedRows');
		if(keys.length == 0){
		    alert("Ничего не выбрано.");
		}else {
		    $.post('/site/mass-delete', {keylist : keys}, function(data) {});
		    return false;   
		}
	});
    
JS;
    $this->registerJs($script2, \yii\web\View::POS_END);
    ?>

    <?$valueBtnCreate = 1;
    if($kpiManager['month']['month_kpi_contacts'] == 0 || $kpiManager['month']['month_kpi_kp'] == 0 || $kpiManager['month']['month_kpi_sale'] == 0){
       $valueBtnCreate = 0;
    }
    else{
        $valueBtnCreate = 1;
    }
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
            'order' => DynaGrid::ORDER_FIX_LEFT,
            'viewOptions' => ['title' => 'Детальная страница сделки', 'class' => 'btn btn-success btn-xs'],
            'updateOptions' => ['title' => 'Редактирование сделки', 'class' => 'btn btn-info btn-xs'],
            'deleteOptions' => ['title' => 'В корзину', 'class' => 'btn btn-danger btn-xs'],
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
                    $valueBtnCreate ?
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', ['create'], ['data-pjax' => 0, 'title' => 'Создать сделку', 'class' => 'btn btn-success']) .
                        Html::a('<i class="glyphicon glyphicon-trash"></i> В корзину', 'javascript:void(0)', ['data-pjax' => 1, 'class' => 'btn btn-danger', 'title' => 'В корзину', 'id'=>'deal-delete',]) .
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 1, 'class' => 'btn btn-primary', 'title' => 'Reset Grid'])
                        :
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Создать', 'javascript:void', ['disabled' => 'disabled', 'data-pjax' => 0, 'title' => 'KPI не установлен', 'class' => 'btn btn-success']) .
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