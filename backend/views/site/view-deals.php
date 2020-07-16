<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ManagerDashboard */

$this->title = 'ID: '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Manager Dashboards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="manager-dashboard-view">
    <div class="panel panel-primary">
        <div class="panel-heading">Deal #<?=$model->id?></div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <tbody>
                <tr class="success">
                    <th colspan="100"><h4><b>Основная иформация</b></h4></th>
                </tr>
                <tr>
                    <th>Менеджер:</th>
                    <td colspan="100"><?=$model->manager->name?></td>
                </tr>
                <tr>
                    <th>Услуга:</th>
                    <td colspan="100"><?=$model->services->name?></td>
                </tr>
                <tr>
                    <th>Профиль Instagram:</th>
                    <td colspan="100"><a href="https://www.instagram.com/<?=$model->account_name?>/" target="_blank"><?=$model->account_name?></a></td>
                </tr>
                <tr>
                    <th>Дата создания:</th>
                    <td colspan="100"><?
                        echo \Yii::$app->formatter->asDatetime(new DateTime($model->date), 'php:d.m.Y H:i:s');?>
                    </td>
                </tr>
                <tr>
                    <th>Стадия сделки:</th>
                    <td colspan="100">
                        <?
                        if($model->status_order_id == 1){?>
                            <span class="label label-success"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 2){?>
                            <span class="label label-warning"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 3){?>
                            <span class="label label-warning"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 4){?>
                            <span class="label label-info"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 5){?>
                            <span class="label label-light"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 6){?>
                            <span class="label label-primary"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 7){?>
                            <span class="label label-danger"><?=$model->statusOrder->name?></span>
                        <?}
                        elseif ($model->status_order_id == 8){?>
                            <span class="label label-dark"><?=$model->statusOrder->name?></span>
                        <?}elseif ($model->status_order_id == 9){?>
                            <span class="label label-warning"><?=$model->statusOrder->name?></span>
                        <?}?>
                    </td>
                </tr>
                <tr>
                    <th>Тип коммисии:</th>
                    <td colspan="100">
                        <?if ($model->bool_fixed_or_individ){?>
                            <p><b>Индивидуальная</b></p>
                        <?}else{?>
                            <p><b>Фиксированная</b></p>
                        <?}?>
                    </td>
                </tr>
                <tr>
                    <th>Коммисия:</th>
                    <td colspan="100"><b><?=Yii::$app->formatter->asCurrency($model->tax)?></b></td>
                </tr>
                <tr class="success">
                    <th colspan="100"><h4><b>Контактные данные</b></h4></th>
                </tr>
                <tr>
                    <th>Поля\№</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <th class="info" colspan="100"><h5><b>Контакт #<?=$key+1?></b></h5></th>
                        <?}
                        else{?>
                            <th class="info"><h5><b>Контакт #<?=$key+1?></b></h5></th>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>Имя:</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <td colspan="100">
                                <?=$contact['name']?>
                            </td>
                        <?}
                        else{?>
                            <td>
                                <?=$contact['name']?>
                            </td>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>Телефон:</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <td colspan="100">
                                <a href="tel:<?=$contact['phone']?>"><?=$contact['phone']?></a>
                            </td>
                        <?}
                        else{?>
                            <td>
                                <a href="tel:<?=$contact['phone']?>"><?=$contact['phone']?></a>
                            </td>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>Email:</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <td colspan="100">
                                <a href="mailto:<?=$contact['email']?>"><?=$contact['email']?></a>
                            </td>
                        <?}
                        else{?>
                            <td>
                                <a href="mailto:<?=$contact['email']?>"><?=$contact['email']?></a>
                            </td>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>Тип контакта:</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <td colspan="100">
                                <?if($contact['type_contact_id'] == 1){?>
                                    <span class="label label-primary"><?=\common\models\TypeContact::findOne(['id'=>$contact['type_contact_id']])->name?></span>
                                <?}
                                elseif ($contact['type_contact_id'] == 2){?>
                                    <span class="label label-success"><?=\common\models\TypeContact::findOne(['id'=>$contact['type_contact_id']])->name?></span>
                                <?}?>
                            </td>
                        <?}
                        else{?>
                            <td>
                                <?if($contact['type_contact_id'] == 1){?>
                                    <span class="label label-primary"><?=\common\models\TypeContact::findOne(['id'=>$contact['type_contact_id']])->name?></span>
                                <?}
                                elseif ($contact['type_contact_id'] == 2){?>
                                    <span class="label label-success"><?=\common\models\TypeContact::findOne(['id'=>$contact['type_contact_id']])->name?></span>
                                <?}?>
                            </td>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>ЛПР:</th>
                    <?foreach ($model->contacts as $key=>$contact){?>
                        <?if(count($model->contacts) == $key+1){?>
                            <td colspan="100">
                                <?if($contact['lpr_bool']){?>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                <?}
                                else{?>
                                    <span class="glyphicon glyphicon-remove text-danger"></span>
                                <?}?>
                            </td>
                        <?}
                        else{?>
                            <td>
                                <?if($contact['lpr_bool']){?>
                                    <span class="glyphicon glyphicon-ok text-success"></span>
                                <?}
                                else{?>
                                    <span class="glyphicon glyphicon-remove text-danger"></span>
                                <?}?>
                            </td>
                        <?}?>
                    <?}?>
                </tr>
                <tr class="success">
                    <th colspan="100"><h4><b>Сумма сделки</b></h4></th>
                </tr>
                <tr>
                    <th>Сумма:</th>
                    <?foreach ($model->price_deal as $key=>$priceOrder){?>
                        <?if(count($model->price_deal) == $key+1){?>
                            <td colspan="100"><b><?=Yii::$app->formatter->asCurrency($priceOrder['price'])?></b></td>
                        <?}
                        else{?>
                            <td><b><?=Yii::$app->formatter->asCurrency($priceOrder['price'])?></b></td>
                        <?}?>
                    <?}?>
                </tr>
                <tr>
                    <th>Комментарий к сумме:</th>
                    <?foreach ($model->price_deal as $key=>$priceOrder){?>
                        <?if(count($model->price_deal) == $key+1){?>
                            <td colspan="100"><?=$priceOrder['comment']?></td>
                        <?}
                        else{?>
                            <td><?=$priceOrder['comment']?></td>
                        <?}?>
                    <?}?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Deal Comments #<?=$model->id?></div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">Комментарий</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Cтадия сделки на момент добавления комментария</th>
                </tr>
                </thead>
                <tbody>
                <?foreach ($model->commentDeals as $key=>$commentDeal){?>
                    <tr>
                        <td><?=$commentDeal->text?></td>
                        <td><?=\Yii::$app->formatter->asDatetime(new DateTime($commentDeal->date), 'php:d.m.Y H:i:s');?></td>
                        <td><?
                            if($commentDeal->status_deal_id == 1){?>
                                <span class="label label-success"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 2){?>
                                <span class="label label-warning"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 3){?>
                                <span class="label label-warning"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 4){?>
                                <span class="label label-info"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 5){?>
                                <span class="label label-light"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 6){?>
                                <span class="label label-primary"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 7){?>
                                <span class="label label-danger"><?=$commentDeal->statusDeal->name?></span>
                            <?}
                            elseif ($commentDeal->status_deal_id == 8){?>
                                <span class="label label-dark"><?=$commentDeal->statusDeal->name?></span>
                            <?}?></td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">Deal History #<?=$model->id?></div>

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">Кем создан</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Старый статус</th>
                    <th scope="col">Новый статус</th>
                </tr>
                </thead>
                <tbody>
                <?foreach ($model->historyDeals as $key=>$historyDeal){?>
                    <tr>
                        <td><?=$historyDeal->manager->name?></td>
                        <td><?=\Yii::$app->formatter->asDatetime(new DateTime($historyDeal->date), 'php:d.m.Y H:i:s');?></td>
                        <td><?
                            if($historyDeal->old_status_id == 1){?>
                                <span class="label label-success"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 2){?>
                                <span class="label label-warning"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 3){?>
                                <span class="label label-warning"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 4){?>
                                <span class="label label-info"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 5){?>
                                <span class="label label-light"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 6){?>
                                <span class="label label-primary"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 7){?>
                                <span class="label label-danger"><?=$historyDeal->oldStatus->name?></span>
                            <?}
                            elseif ($historyDeal->old_status_id == 8){?>
                                <span class="label label-dark"><?=$historyDeal->oldStatus->name?></span>
                            <?}?>
                        </td>

                        <td><?
                            if($historyDeal->new_status_id == 1){?>
                                <span class="label label-success"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 2){?>
                                <span class="label label-warning"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 3){?>
                                <span class="label label-warning"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 4){?>
                                <span class="label label-info"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 5){?>
                                <span class="label label-light"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 6){?>
                                <span class="label label-primary"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 7){?>
                                <span class="label label-danger"><?=$historyDeal->newStatus->name?></span>
                            <?}
                            elseif ($historyDeal->new_status_id == 8){?>
                                <span class="label label-dark"><?=$historyDeal->newStatus->name?></span>
                            <?}?>
                        </td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        </div>
    </div>

</div>
