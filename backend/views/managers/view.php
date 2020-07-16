<?php

use common\models\ManagerDashboard;
use common\models\PriceOrder;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $searchModel common\models\AdminPanelSearch */
/* @var $dataProvider yii2mod\query\ArrayQuery */
/* @var $searchModel_deal common\models\ManagerDashboardSearch */
/* @var $dataProvider_deal yii\data\ActiveDataProvider */

$this->title = "ID:".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить данные', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить данного менеджера?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
                'label' => 'ID',
            ],
            [
                'attribute'=>'username',
                'label' => 'Логин',
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
                'label' => 'Email',
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'label' => 'Активность',
                'value' => function($model)
                {
                    if($model->status == 9){
                        return "<span class='glyphicon glyphicon-remove text-danger'></span>";
                    }
                    elseif ($model->status == 10){
                        return "<span class='glyphicon glyphicon-ok text-success'></span>";
                    }
                },
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Создан',
                'format'=>['DateTime','dd.MM.Y HH:mm:ss'],
            ],
            [
                'attribute' => 'updated_at',
                'label' => 'Обновлён',
                'format'=>['DateTime','dd.MM.Y HH:mm:ss'],
            ],
            [
                'attribute'=> 'name',
                'label' => 'Имя',
                'value' => function(\common\models\User $user){
                    return $user->userPersonalInfo->name;
                }
            ]
        ],
    ]);?>

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
                        <? $arStatistics = \common\models\Statistics::getStatistics($model->id);
                        foreach ($arStatistics as $key => $statisticItem) {
                            ?>
                            <tr <? if ($key == 'all'){?>class="info"<? }?>>
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
                        $kpiManager = \common\models\KpiManager::generateArrayResult($model->id);

                        if($kpiManager['today']['today_kpi_deals'] == 0 ||
                            $kpiManager['today']['today_kpi_contacts'] == 0 ||
                            $kpiManager['today']['today_kpi_kp'] == 0 ||
                            $kpiManager['today']['today_kpi_sale'] == 0){?>
                            <tr>
                                <td colspan="5">
                                    <p class="text-center text-danger">
                                        <b>Личный KPI не установлен!</b>
                                    </p>
                                </td>
                            </tr>
                        <?}
                        else{?>
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
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel panel-success">
                <div class="panel-heading">Агентская комиссия</div>
                <table class="table table-bordered table-hover table-striped">
                    <tbody>
                    <tr>
                        <td>В процессе:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()->where(['id_manager'=>$model->id])->andWhere('status_order_id < 6')->select(['tax'])->asArray()->all();
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
                                ->where(['id_manager'=>$model->id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$model->id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$model->id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$model->id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$model->id, 'status_order_id' => 6])
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


    <?$columns_deal = [
        [
            'class' => 'kartik\grid\ActionColumn',
            'dropdown' => false,
            'noWrap' => true,
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '/admin/site/view-deals/?id='.$model->id,
                        [
                            'title' => 'Детальная страница сделки',
                            'data-pjax' => '0',
                            'class' => 'btn btn-success btn-xs',
                            'target' => '_blank'
                        ]
                    );
                },
            ]
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
            'format' => ['DateTime', 'dd.MM.Y HH:mm:ss'],
            'filterWidgetOptions' => ([
                'includeMonthsFilter' => true,
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
                if ($model->status_order_id == 1) {
                    return '<span class="label label-success">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 2) {
                    return '<span class="label label-warning">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 3) {
                    return '<span class="label label-warning">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 4) {
                    return '<span class="label label-info">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 5) {
                    return '<span class="label label-light">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 6) {
                    return '<span class="label label-primary">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 7) {
                    return '<span class="label label-danger">' . $model->statusOrder->name . '</span>';
                } elseif ($model->status_order_id == 8) {
                    return '<span class="label label-dark">' . $model->statusOrder->name . '</span>';
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
                $contacts = \common\models\Contacts::find()->where(['manager_id' => $dashboard->id_manager, 'deal_id' => $dashboard->id, 'lpr_bool' => 1])->asArray()->all();
                foreach ($contacts as $record) {
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
            'filter' => MaskedInput::widget([
                'name' => 'phone',
                'clientOptions' => [
                    'alias' => 'phone',
                ],
            ]),
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id' => $dashboard->id_manager, 'deal_id' => $dashboard->id, 'lpr_bool' => 1])->asArray()->all();
                foreach ($contacts as $record) {
                    return '<a href="tel:' . $record['phone'] . '">' . $record['phone'] . '</a>';
                }
            },
        ],
        [
            'attribute' => 'email',
            'label' => 'Email',
            'filter' => MaskedInput::widget([
                'name' => 'email',
                'clientOptions' => [
                    'alias' => 'email',
                ],
            ]),
            'pageSummary' => false,
            'format' => 'raw',
            'value' => function (ManagerDashboard $dashboard) {
                $contacts = \common\models\Contacts::find()->where(['manager_id' => $dashboard->id_manager, 'deal_id' => $dashboard->id, 'lpr_bool' => 1])->asArray()->all();
                foreach ($contacts as $record) {
                    return '<a href="mailto:' . $record['email'] . '">' . $record['email'] . '</a>';
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
            'attribute' => 'tax',
            'label' => 'Коммисия',
            'filterType' => GridView::FILTER_NUMBER,
            'pageSummary' => true,
            'format' => 'raw',
            'value' => function (\common\models\ManagerDashboard $dashboard) {
                return "<b>" . Yii::$app->formatter->asCurrency($dashboard->tax) . "</b>";
            }

        ]
    ];
    Pjax::begin(['id' => 'deals']);
    $dynaGrid = DynaGrid::begin([
        'theme' => 'panel-primary',
        'columns' => $columns_deal,
        'storage' => DynaGrid::TYPE_DB,
        'gridOptions' => [
            'dataProvider' => $dataProvider_deal,
            'filterModel' => $searchModel_deal,
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
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', '/admin/managers/view?id='.$model->id, ['data-pjax' => 1, 'class' => 'btn btn-primary', 'title' => 'Reset Grid'])
                ],
                ['content' => '{dynagridFilter}{dynagridSort}{dynagrid}'],
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
