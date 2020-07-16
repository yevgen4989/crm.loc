<?php

use common\models\ManagerDashboard;
use common\models\PriceOrder;
use common\models\RatingManager;
use frontend\assets\InputMaskPhoneAsset;
use kartik\dynagrid\DynaGrid;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\data\Pagination;

InputMaskPhoneAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\RatingManager */

$this->title = 'Доска и Рейтинг';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-dashboard-rating">

<?

$array = array();
foreach ($model as $item){
    $array[] = array(
        'manager_id' => $item->manager_id,
        'manager_name' => $item->manager_name,
        'sale_order' => $item->sale_order,
        'conversation_order' => $item->conversation_order,
        'sum_month_order' => $item->sum_month_order,
        'sum_prevmonth_order' => $item->sum_prevmonth_order,
        'sum_all_order' => $item->sum_all_order,
    );
}

$maxSumOrderMonth = 0;
$maxItemSumOrderMonth = null;
foreach ($array as $item) {
  if ($maxSumOrderMonth < $item['sum_month_order']) {
      $maxSumOrderMonth = $item['sum_month_order'];
    $maxItemSumOrderMonth = $item;
  }elseif ($maxSumOrderMonth == $item['sum_month_order']){
      if ($maxItemSumOrderMonth['sale_order'] < $item['sale_order']){
          $maxSumOrderMonth = $item['sum_month_order'];
          $maxItemSumOrderMonth = $item;
      }
  }
}


$maxCountSaleOrder = 0;
$maxItemSaleOrder = null;
foreach ($array as $item) {
    if ($item['sale_order'] > $maxCountSaleOrder) {
        $maxCountSaleOrder = $item['sale_order'];
        $maxItemSaleOrder = $item;
    }
    elseif ($maxCountSaleOrder == $item['sale_order']){

        if ($maxItemSaleOrder['sum_month_order'] < $item['sum_month_order']){
            $maxCountSaleOrder = $item['sale_order'];
            $maxItemSaleOrder = $item;
        }
    }
}

$maxCountConversation = 0;
$maxItemConversation = null;

foreach ($array as $item) {
    if ($item['conversation_order'] > $maxCountConversation) {
        $maxCountConversation = $item['conversation_order'];
        $maxItemConversation = $item;
    }
    elseif ($maxCountConversation == $item['conversation_order']){

        if ($maxItemConversation['sale_order'] < $item['sale_order']){
            $maxCountConversation = $item['conversation_order'];
            $maxItemConversation = $item;
        }
        elseif ($maxItemConversation['sale_order'] == $item['sale_order']){

            if ($maxItemConversation['sum_month_order'] < $item['sum_month_order']){
                $maxCountConversation = $item['conversation_order'];
                $maxItemConversation = $item;
            }
        }
    }
}

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-success">
            <div class="panel-heading"><b>ТОП Рейтинг</b></div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                    <tr>
                        <td>
                            Сумма продаж:
                        </td>
                        <td>
                            <b><?=$maxItemSumOrderMonth['manager_name'].' ( ID:'.$maxItemSumOrderMonth['manager_id'].' )'?></b>
                        </td>
                        <td>
                            <b><?=Yii::$app->formatter->asCurrency($maxSumOrderMonth)?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Завершено сделок:
                        </td>
                        <td>
                            <b>
                                <?=$maxItemSaleOrder['manager_name'].' ( ID:'.$maxItemSaleOrder['manager_id'].' )'?>
                            </b>
                        </td>
                        <td>
                            <b><?=$maxCountSaleOrder?></b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Сделок в обработке:
                        </td>
                        <td>
                            <b>
                                <?=$maxItemConversation['manager_name'].' ( ID:'.$maxItemConversation['manager_id'].' )'?>
                            </b>
                        </td>
                        <td>
                            <b><?=$maxCountConversation?></b>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel panel-primary">
            <div class="panel-heading">Доска менеджеров</div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Менеджер\Поле</th>
                        <th>Завершенных сделок за месяц</th>
                        <th>Сделок в обработке</th>

                        <th>Сумма продаж <br> за месяц</th>
                        <th>Сумма продаж <br> за предыдущий месяц</th>
                        <th>Сумма продаж <br> за всё время</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?foreach ($model as $item){?>
                        <tr>
                            <td><b><?=$item->manager_name.'( ID:'.$item->manager_id.' )'?></b></td>
                            <td><?=$item->sale_order?></td>
                            <td><?=$item->conversation_order?></td>
                            <td><?=Yii::$app->formatter->asCurrency($item->sum_month_order)?></td>
                            <td><?=Yii::$app->formatter->asCurrency($item->sum_prevmonth_order)?></td>
                            <td><?=Yii::$app->formatter->asCurrency($item->sum_all_order)?></td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</div>