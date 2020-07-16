<?php


namespace common\models;


class Statistics
{
    public static function getStatisticsDayByID($manager_id){
        //Day Deal START
        $arOrderDayIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderDayIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderDayIDs = $arOrderIDs_temp;

        $arHistoryDealDay = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere('DATE(date) = DATE(NOW())')->asArray()->all();

        $arDealDay = array();
        foreach ($arHistoryDealDay as $value){
            foreach ($arOrderDayIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealDay[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealDay[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealDay[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealDay as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealDay = $arDeal_tmp;

        $dealDay = 0;
        foreach ($arDealDay as $key=>$item){
            if (in_array(1, $item['history'])){
                $dealDay += 1;
            }
        }
        //Day Deal END


        //Day Contact START
        $arOrderContactDayIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderContactDayIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderContactDayIDs = $arOrderIDs_temp;

        $arHistoryDealContactDay = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere('DATE(date) = DATE(NOW())')->asArray()->all();

        $arDealContactDay = array();
        foreach ($arHistoryDealContactDay as $value){
            foreach ($arOrderContactDayIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealContactDay[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealContactDay[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealContactDay[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealContactDay as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealContactDay = $arDeal_tmp;

        $contactsContactDay = 0;
        foreach ($arDealContactDay as $key=>$item){
            if (in_array(2, $item['history'])){
                $contactsContactDay += 1;
            }
        }
        //Day Contact END

        //Day InWork START
        $arOrderInWorkDayIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderInWorkDayIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderInWorkDayIDs = $arOrderIDs_temp;

        $arHistoryDealInWorkDay = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere('DATE(date) = DATE(NOW())')->asArray()->all();

        $arDealInWorkDay = array();
        foreach ($arHistoryDealInWorkDay as $value){
            foreach ($arOrderInWorkDayIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealInWorkDay[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealInWorkDay[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealInWorkDay[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealInWorkDay as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealInWorkDay = $arDeal_tmp;

        $contactsInWorkDay = 0;
        foreach ($arDealInWorkDay as $key=>$item){
            if (in_array(5, $item['history'])){
                $contactsInWorkDay += 1;
            }
        }
        //Day InWork END

        //Day Failure START
        $arOrderFailureDayIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderFailureDayIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderFailureDayIDs = $arOrderIDs_temp;

        $arHistoryDealFailureDay = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere('DATE(date) = DATE(NOW())')->asArray()->all();

        $arDealFailureDay = array();
        foreach ($arHistoryDealFailureDay as $value){
            foreach ($arOrderFailureDayIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealFailureDay[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealFailureDay[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealFailureDay[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealFailureDay as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealFailureDay = $arDeal_tmp;

        $contactsFailureDay = 0;
        foreach ($arDealFailureDay as $key=>$item){
            if (in_array(7, $item['history'])){
                $contactsFailureDay += 1;
            }
        }
        //Day Failure END

        //Day Sale START
        $arOrderSaleDayIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderSaleDayIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderSaleDayIDs = $arOrderIDs_temp;

        $arHistoryDealSaleDay = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere('DATE(date) = DATE(NOW())')->asArray()->all();

        $arDealSaleDay = array();
        foreach ($arHistoryDealSaleDay as $value){
            foreach ($arOrderSaleDayIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealSaleDay[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealSaleDay[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealSaleDay[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealSaleDay as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealSaleDay = $arDeal_tmp;

        $contactsSaleDay = 0;
        foreach ($arDealSaleDay as $key=>$item){
            if (in_array(6, $item['history'])){
                $contactsSaleDay += 1;
            }
        }
        //Day Sale END

        $arResultDay = array(
            'text' => 'Сегодня:',
            'deal' => $dealDay,
            'contact'=> $contactsContactDay,
            'in_work'=> $contactsInWorkDay,
            'failure'=> $contactsFailureDay,
            'sale'=> $contactsSaleDay
        );

        return $arResultDay;
    }

    public static function getStatisticsWeekByID($manager_id){
        //Week Deal START
        $arOrderWeekIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderWeekIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderWeekIDs = $arOrderIDs_temp;

        $arHistoryDealWeek = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("WEEK(DATE(date)) = WEEK(NOW())")->asArray()->all();

        $arDealWeek = array();
        foreach ($arHistoryDealWeek as $value){
            foreach ($arOrderWeekIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealWeek[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealWeek[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealWeek[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealWeek as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealWeek = $arDeal_tmp;

        $dealWeek = 0;
        foreach ($arDealWeek as $key=>$item){
            if (in_array(1, $item['history'])){
                $dealWeek += 1;
            }
        }
        //Week Deal END

        //Week Contact START
        $arOrderContactWeekIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderContactWeekIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderContactWeekIDs = $arOrderIDs_temp;

        $arHistoryDealContactWeek = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("WEEK(DATE(date)) = WEEK(NOW())")->asArray()->all();

        $arDealContactWeek = array();
        foreach ($arHistoryDealContactWeek as $value){
            foreach ($arOrderContactWeekIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealContactWeek[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealContactWeek[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealContactWeek[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealContactWeek as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealContactWeek = $arDeal_tmp;

        $contactsContactWeek = 0;
        foreach ($arDealContactWeek as $key=>$item){
            if (in_array(2, $item['history'])){
                $contactsContactWeek += 1;
            }
        }
        //Week Contact END

        //Week InWork START
        $arOrderInWorkWeekIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderInWorkWeekIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderInWorkWeekIDs = $arOrderIDs_temp;

        $arHistoryDealInWorkWeek = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("WEEK(DATE(date)) = WEEK(NOW())")->asArray()->all();

        $arDealInWorkWeek = array();
        foreach ($arHistoryDealInWorkWeek as $value){
            foreach ($arOrderInWorkWeekIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealInWorkWeek[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealInWorkWeek[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealInWorkWeek[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealInWorkWeek as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealInWorkWeek = $arDeal_tmp;

        $contactsInWorkWeek = 0;
        foreach ($arDealInWorkWeek as $key=>$item){
            if (in_array(5, $item['history'])){
                $contactsInWorkWeek += 1;
            }
        }
        //Week InWork END

        //Week Failure START
        $arOrderFailureWeekIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderFailureWeekIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderFailureWeekIDs = $arOrderIDs_temp;

        $arHistoryDealFailureWeek = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("WEEK(DATE(date)) = WEEK(NOW())")->asArray()->all();

        $arDealFailureWeek = array();
        foreach ($arHistoryDealFailureWeek as $value){
            foreach ($arOrderFailureWeekIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealFailureWeek[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealFailureWeek[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealFailureWeek[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealFailureWeek as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealFailureWeek = $arDeal_tmp;

        $contactsFailureWeek = 0;
        foreach ($arDealFailureWeek as $key=>$item){
            if (in_array(7, $item['history'])){
                $contactsFailureWeek += 1;
            }
        }
        //Week Failure END

        //Week Sale START
        $arOrderSaleWeekIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderSaleWeekIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderSaleWeekIDs = $arOrderIDs_temp;

        $arHistoryDealSaleWeek = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("WEEK(DATE(date)) = WEEK(NOW())")->asArray()->all();

        $arDealSaleWeek = array();
        foreach ($arHistoryDealSaleWeek as $value){
            foreach ($arOrderSaleWeekIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealSaleWeek[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealSaleWeek[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealSaleWeek[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealSaleWeek as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealSaleWeek = $arDeal_tmp;

        $contactsSaleWeek = 0;
        foreach ($arDealSaleWeek as $key=>$item){
            if (in_array(6, $item['history'])){
                $contactsSaleWeek += 1;
            }
        }
        //Week Sale END

        $arResultWeek = array(
            'text' => 'За неделю:',
            'deal'=> $dealWeek,
            'contact'=> $contactsContactWeek,
            'in_work'=> $contactsInWorkWeek,
            'failure'=> $contactsFailureWeek,
            'sale'=> $contactsSaleWeek
        );

        return $arResultWeek;
    }

    public static function getStatisticsMonthByID($manager_id){
        //Month Deal START
        $arOrderMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderMonthIDs = $arOrderIDs_temp;

        $arHistoryDealMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();

        $arDealMonth = array();
        foreach ($arHistoryDealMonth as $value){
            foreach ($arOrderMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealMonth = $arDeal_tmp;

        $dealMonth = 0;
        foreach ($arDealMonth as $key=>$item){
            if (in_array(1, $item['history'])){
                $dealMonth += 1;
            }
        }
        //Month Deal END

        //Month Contact START
        $arOrderContactMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderContactMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderContactMonthIDs = $arOrderIDs_temp;

        $arHistoryDealContactMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();

        $arDealContactMonth = array();
        foreach ($arHistoryDealContactMonth as $value){
            foreach ($arOrderContactMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealContactMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealContactMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealContactMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealContactMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealContactMonth = $arDeal_tmp;

        $contactsContactMonth = 0;
        foreach ($arDealContactMonth as $key=>$item){
            if (in_array(2, $item['history'])){
                $contactsContactMonth += 1;
            }
        }
        //Month Contact END

        //Month InWork START
        $arOrderInWorkMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderInWorkMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderInWorkMonthIDs = $arOrderIDs_temp;

        $arHistoryDealInWorkMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();

        $arDealInWorkMonth = array();
        foreach ($arHistoryDealInWorkMonth as $value){
            foreach ($arOrderInWorkMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealInWorkMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealInWorkMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealInWorkMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealInWorkMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealInWorkMonth = $arDeal_tmp;

        $contactsInWorkMonth = 0;
        foreach ($arDealInWorkMonth as $key=>$item){
            if (in_array(5, $item['history'])){
                $contactsInWorkMonth += 1;
            }
        }
        //Month InWork END

        //Month Failure START
        $arOrderFailureMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderFailureMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderFailureMonthIDs = $arOrderIDs_temp;

        $arHistoryDealFailureMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();

        $arDealFailureMonth = array();
        foreach ($arHistoryDealFailureMonth as $value){
            foreach ($arOrderFailureMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealFailureMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealFailureMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealFailureMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealFailureMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealFailureMonth = $arDeal_tmp;

        $contactsFailureMonth = 0;
        foreach ($arDealFailureMonth as $key=>$item){
            if (in_array(7, $item['history'])){
                $contactsFailureMonth += 1;
            }
        }
        //Month Failure END

        //Month Sale START
        $arOrderSaleMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderSaleMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderSaleMonthIDs = $arOrderIDs_temp;

        $arHistoryDealSaleMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())")->asArray()->all();

        $arDealSaleMonth = array();
        foreach ($arHistoryDealSaleMonth as $value){
            foreach ($arOrderSaleMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealSaleMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealSaleMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealSaleMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealSaleMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealSaleMonth = $arDeal_tmp;

        $contactsSaleMonth = 0;
        foreach ($arDealSaleMonth as $key=>$item){
            if (in_array(6, $item['history'])){
                $contactsSaleMonth += 1;
            }
        }
        //Month Sale END

        $arResultMonth = array(
            'text' => 'В этом месяце:',
            'deal' => $dealMonth,
            'contact'=> $contactsContactMonth,
            'in_work'=> $contactsInWorkMonth,
            'failure'=> $contactsFailureMonth,
            'sale'=> $contactsSaleMonth
        );

        return $arResultMonth;
    }

    public static function getStatisticsLastMonthByID($manager_id){
        //Last Month Deal START
        $arOrderLastMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderLastMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderLastMonthIDs = $arOrderIDs_temp;

        $arHistoryDealLastMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())-1")->asArray()->all();

        $arDealLastMonth = array();
        foreach ($arHistoryDealLastMonth as $value){
            foreach ($arOrderLastMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealLastMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealLastMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealLastMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealLastMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealLastMonth = $arDeal_tmp;

        $dealLastMonth = 0;
        foreach ($arDealLastMonth as $key=>$item){
            if (in_array(1, $item['history'])){
                $dealLastMonth += 1;
            }
        }
        //Last Month Deal END

        //Last Month Contact START
        $arOrderContactLastMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderContactLastMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderContactLastMonthIDs = $arOrderIDs_temp;

        $arHistoryDealContactLastMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())-1")->asArray()->all();

        $arDealContactLastMonth = array();
        foreach ($arHistoryDealContactLastMonth as $value){
            foreach ($arOrderContactLastMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealContactLastMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealContactLastMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealContactLastMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealContactLastMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealContactLastMonth = $arDeal_tmp;

        $contactsContactLastMonth = 0;
        foreach ($arDealContactLastMonth as $key=>$item){
            if (in_array(2, $item['history'])){
                $contactsContactLastMonth += 1;
            }
        }
        //Last Month Contact END

        //Last Month InWork START
        $arOrderInWorkLastMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderInWorkLastMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderInWorkLastMonthIDs = $arOrderIDs_temp;

        $arHistoryDealInWorkLastMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())-1")->asArray()->all();

        $arDealInWorkLastMonth = array();
        foreach ($arHistoryDealInWorkLastMonth as $value){
            foreach ($arOrderInWorkLastMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealInWorkLastMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealInWorkLastMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealInWorkLastMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealInWorkLastMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealInWorkLastMonth = $arDeal_tmp;

        $contactsInWorkLastMonth = 0;
        foreach ($arDealInWorkLastMonth as $key=>$item){
            if (in_array(5, $item['history'])){
                $contactsInWorkLastMonth += 1;
            }
        }
        //Last Month InWork END

        //Last Month Failure START
        $arOrderFailureLastMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderFailureLastMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderFailureLastMonthIDs = $arOrderIDs_temp;

        $arHistoryDealFailureLastMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())-1")->asArray()->all();

        $arDealFailureLastMonth = array();
        foreach ($arHistoryDealFailureLastMonth as $value){
            foreach ($arOrderFailureLastMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealFailureLastMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealFailureLastMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealFailureLastMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealFailureLastMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealFailureLastMonth = $arDeal_tmp;

        $contactsFailureLastMonth = 0;
        foreach ($arDealFailureLastMonth as $key=>$item){
            if (in_array(7, $item['history'])){
                $contactsFailureLastMonth += 1;
            }
        }
        //Last Month Failure END

        //Last Month Sale START
        $arOrderSaleLastMonthIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderSaleLastMonthIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderSaleLastMonthIDs = $arOrderIDs_temp;

        $arHistoryDealSaleLastMonth = HistoryDeal::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(NOW())-1")->asArray()->all();

        $arDealSaleLastMonth = array();
        foreach ($arHistoryDealSaleLastMonth as $value){
            foreach ($arOrderSaleLastMonthIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealSaleLastMonth[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealSaleLastMonth[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealSaleLastMonth[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealSaleLastMonth as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealSaleLastMonth = $arDeal_tmp;

        $contactsSaleLastMonth = 0;
        foreach ($arDealSaleLastMonth as $key=>$item){
            if (in_array(6, $item['history'])){
                $contactsSaleLastMonth += 1;
            }
        }
        //Last Month Sale END

        $arResultLastMonth = array(
            'text' => 'В прошлом месяце:',
            'deal' => $dealLastMonth,
            'contact'=> $contactsContactLastMonth,
            'in_work'=> $contactsInWorkLastMonth,
            'failure'=> $contactsFailureLastMonth,
            'sale'=> $contactsSaleLastMonth
        );

        return $arResultLastMonth;
    }

    public static function getStatisticsAllByID($manager_id){
        //All Deal START
        $arOrderAllIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderAllIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderAllIDs = $arOrderIDs_temp;

        $arHistoryDealAll = HistoryDeal::find()->where(['manager_id'=>$manager_id])->asArray()->all();

        $arDealAll = array();
        foreach ($arHistoryDealAll as $value){
            foreach ($arOrderAllIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealAll[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealAll[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealAll[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealAll as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealAll = $arDeal_tmp;

        $dealAll = 0;
        foreach ($arDealAll as $key=>$item){
            if (in_array(1, $item['history'])){
                $dealAll += 1;
            }
        }
        //All Deal END

        //All Contact START
        $arOrderContactAllIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderContactAllIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderContactAllIDs = $arOrderIDs_temp;

        $arHistoryDealContactAll = HistoryDeal::find()->where(['manager_id'=>$manager_id])->asArray()->all();

        $arDealContactAll = array();
        foreach ($arHistoryDealContactAll as $value){
            foreach ($arOrderContactAllIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealContactAll[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealContactAll[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealContactAll[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealContactAll as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealContactAll = $arDeal_tmp;

        $contactsContactAll = 0;
        foreach ($arDealContactAll as $key=>$item){
            if (in_array(2, $item['history'])){
                $contactsContactAll += 1;
            }
        }
        //All Contact END

        //All InWork START
        $arOrderInWorkAllIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderInWorkAllIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderInWorkAllIDs = $arOrderIDs_temp;

        $arHistoryDealInWorkAll = HistoryDeal::find()->where(['manager_id'=>$manager_id])->asArray()->all();

        $arDealInWorkAll = array();
        foreach ($arHistoryDealInWorkAll as $value){
            foreach ($arOrderInWorkAllIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealInWorkAll[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealInWorkAll[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealInWorkAll[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealInWorkAll as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealInWorkAll = $arDeal_tmp;

        $contactsInWorkAll = 0;
        foreach ($arDealInWorkAll as $key=>$item){
            if (in_array(5, $item['history'])){
                $contactsInWorkAll += 1;
            }
        }
        //All InWork END

        //All Failure START
        $arOrderFailureAllIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderFailureAllIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderFailureAllIDs = $arOrderIDs_temp;

        $arHistoryDealFailureAll = HistoryDeal::find()->where(['manager_id'=>$manager_id])->asArray()->all();

        $arDealFailureAll = array();
        foreach ($arHistoryDealFailureAll as $value){
            foreach ($arOrderFailureAllIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealFailureAll[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealFailureAll[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealFailureAll[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealFailureAll as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealFailureAll = $arDeal_tmp;

        $contactsFailureAll = 0;
        foreach ($arDealFailureAll as $key=>$item){
            if (in_array(7, $item['history'])){
                $contactsFailureAll += 1;
            }
        }
        //All Failure END

        //All Sale START
        $arOrderSaleAllIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        $arOrderIDs_temp = array();
        foreach ($arOrderSaleAllIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderSaleAllIDs = $arOrderIDs_temp;

        $arHistoryDealSaleAll = HistoryDeal::find()->where(['manager_id'=>$manager_id])->asArray()->all();

        $arDealSaleAll = array();
        foreach ($arHistoryDealSaleAll as $value){
            foreach ($arOrderSaleAllIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDealSaleAll[$value['deal_id']]['deal_id'] = $itemID;
                    $arDealSaleAll[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDealSaleAll[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDealSaleAll as $value){
            $arDeal_tmp[] = $value;
        }
        $arDealSaleAll = $arDeal_tmp;

        $contactsSaleAll = 0;
        foreach ($arDealSaleAll as $key=>$item){
            if (in_array(6, $item['history'])){
                $contactsSaleAll += 1;
            }
        }
        //All Sale END

        $arResultAll = array(
            'text' => 'Всего:',
            'deal' => $dealAll,
            'contact'=> $contactsContactAll,
            'in_work'=> $contactsInWorkAll,
            'failure'=> $contactsFailureAll,
            'sale'=> $contactsSaleAll
        );

        return $arResultAll;
    }

    public static function getStatistics($manager_id){
        $arResultDay = self::getStatisticsDayByID($manager_id);
        $arResultWeek = self::getStatisticsWeekByID($manager_id);
        $arResultMonth = self::getStatisticsMonthByID($manager_id);
        $arResultLastMonth = self::getStatisticsLastMonthByID($manager_id);
        $arResultAll = self::getStatisticsAllByID($manager_id);

        $arResult = array(
            'day' => $arResultDay,
            'week' => $arResultWeek,
            'month' => $arResultMonth,
            'last_month' => $arResultLastMonth,
            'all' => $arResultAll
        );

        return $arResult;
    }

}