<?php

use common\models\ManagerDashboard;
use yii\helpers\Html;
?>


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
                        <? $arStatistics = \common\models\Statistics::getStatistics($id);
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
                        $kpiManager = \common\models\KpiManager::generateArrayResult($id);

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
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel panel-success">
                <div class="panel-heading">Агентская комиссия</div>
                <table class="table table-bordered table-hover table-striped">
                    <tbody>
                    <tr>
                        <td>В процессе:</td>
                        <td><?
                            $arrTax = ManagerDashboard::find()->where(['id_manager'=>$id])->andWhere('status_order_id < 6')->select(['tax'])->asArray()->all();
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
                                ->where(['id_manager'=>$id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$id, 'status_order_id' => 6])
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
                                ->where(['id_manager'=>$id, 'status_order_id' => 6])
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