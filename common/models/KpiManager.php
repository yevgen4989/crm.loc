<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kpi_manager".
 *
 * @property int $id
 * @property int $manager_id
 * @property int $kpi_deals_day
 * @property int $kpi_contacts_day
 * @property int $kpi_kp_day
 * @property int $kpi_sale_day
 * @property string $date
 *
 * @property User $manager
 */
class KpiManager extends \yii\db\ActiveRecord
{
    public $generateArray = array();
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_manager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manager_id', 'kpi_deals_day', 'kpi_contacts_day', 'kpi_kp_day', 'kpi_sale_day', 'date'], 'required'],
            [['manager_id', 'kpi_deals_day', 'kpi_contacts_day', 'kpi_kp_day', 'kpi_sale_day'], 'integer'],
            [['date'], 'safe'],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['manager_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => 'Менеджер',
            'kpi_deals_day' => 'KPI Сделок на день',
            'kpi_contacts_day' => 'KPI Звонков на день',
            'kpi_kp_day' => 'KPI КП на день',
            'kpi_sale_day' => 'KPI Продаж на день',
            'date' => 'Дата',
        ];
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(User::className(), ['id' => 'manager_id']);
    }


    public static function CalculateOnWeek($kpi)
    {
        $arMonth = array();
        $arMonthFull = array();
        $countMonthOnCurrentMonth = date('t');
        for ($i = 1; $i <= $countMonthOnCurrentMonth; $i++) {

            $year = date('Y');
            $month = date('m');
            $day = $i < 9 ? '0' . $i : $i;
            $string = $year . '-' . $month . '-' . $day;

            $arMonth[] = array(
                'dayNumber' => $i,
                'nameWeek' => date("l", strtotime($string)),
                'date' => $string,
            );
        }
        $arMonthFull = $arMonth;

        $firstDeal = \common\models\ManagerDashboard::find()->where(['id_manager'=>\Yii::$app->user->id])->select('date')->orderBy('date')->asArray()->all();

        $FD_y_m = date('Y-m', strtotime($firstDeal[0]['date']));
        $Cur_y_m = date('Y-m');

        if($FD_y_m == $Cur_y_m){
            $dateStartMonth = date('d', strtotime($firstDeal[0]['date']));
        }
        else{
            $dateStartMonth = 1;
        }

        foreach ($arMonth as $key => $value) {
            if ($value['dayNumber'] < $dateStartMonth) {
                unset($arMonth[$key]);
            }
        }
        $arMonthNew = array();
        foreach ($arMonth as $value) {
            $arMonthNew[] = array(
                'dayNumber' => $value['dayNumber'],
                'nameWeek' => $value['nameWeek'],
                'date' => $value['date'],
            );
        }
        $arMonth = $arMonthNew;

        $arWeekOfMonth = array();
        foreach ($arMonth as $key => $value) {
            if ($key == 0 && $value['nameWeek'] != 'Monday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            if ($key > 0 && $value['nameWeek'] == 'Monday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            else if ($key > 0 && $value['nameWeek'] == 'Sunday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            if ($key == (count($arMonth) - 1) && $value['nameWeek'] != 'Monday' && $value['nameWeek'] != 'Sunday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
        }

        $arForPrepareCalculates = array();
        for ($i = 0; $i < (count($arWeekOfMonth) - 1); $i += 2) {
            $arForPrepareCalculates[] = array(
                'start_date_week' => $arWeekOfMonth[$i],
                'last_date_week' => $arWeekOfMonth[$i + 1]
            );

            $kpiContactOnDay = $kpi;

            foreach ($arForPrepareCalculates as $key => $value) {
                $dayCount = ($value['last_date_week']['dayNumber'] - $value['start_date_week']['dayNumber']) + 1;
                $arForPrepareCalculates[$key]['dealNeed'] = round($kpiContactOnDay * $dayCount, 2);
            }

        }
        foreach ($arForPrepareCalculates as $key=>$arItem){
            if(strtotime($arItem['start_date_week']['date']) <= strtotime(date('Y-m-d'))
                && strtotime(date('Y-m-d')) <= strtotime($arItem['last_date_week']['date'])){
                return $arItem['dealNeed'];
            }
        }
    }

    public static function CalculateOnMonth($kpi)
    {
        $arMonth = array();
        $arMonthFull = array();
        $countMonthOnCurrentMonth = date('t');
        for ($i = 1; $i <= $countMonthOnCurrentMonth; $i++) {

            $year = date('Y');
            $month = date('m');
            $day = $i < 9 ? '0' . $i : $i;
            $string = $year . '-' . $month . '-' . $day;

            $arMonth[] = array(
                'dayNumber' => $i,
                'nameWeek' => date("l", strtotime($string)),
                'date' => $string,
            );
        }
        $arMonthFull = $arMonth;

        $firstDeal = \common\models\ManagerDashboard::find()->where(['id_manager'=>\Yii::$app->user->id])->select('date')->orderBy('date')->asArray()->all();

        $FD_y_m = date('Y-m', strtotime($firstDeal[0]['date']));
        $Cur_y_m = date('Y-m');

        if($FD_y_m == $Cur_y_m){
            $dateStartMonth = date('d', strtotime($firstDeal[0]['date']));
        }
        else{
            $dateStartMonth = 1;
        }

        foreach ($arMonth as $key => $value) {
            if ($value['dayNumber'] < $dateStartMonth) {
                unset($arMonth[$key]);
            }
        }
        $arMonthNew = array();
        foreach ($arMonth as $value) {
            $arMonthNew[] = array(
                'dayNumber' => $value['dayNumber'],
                'nameWeek' => $value['nameWeek'],
                'date' => $value['date'],
            );
        }
        $arMonth = $arMonthNew;

        $arWeekOfMonth = array();
        foreach ($arMonth as $key => $value) {
            if ($key == 0 && $value['nameWeek'] != 'Monday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            if ($key > 0 && $value['nameWeek'] == 'Monday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            else if ($key > 0 && $value['nameWeek'] == 'Sunday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
            if ($key == (count($arMonth) - 1) && $value['nameWeek'] != 'Monday' && $value['nameWeek'] != 'Sunday') {
                $arWeekOfMonth[] = array(
                    'dayNumber' => $value['dayNumber'],
                    'nameWeek' => $value['nameWeek'],
                    'date' => $value['date'],
                );
            }
        }

        $arForPrepareCalculates = array();
        for ($i = 0; $i < (count($arWeekOfMonth) - 1); $i += 2) {
            $arForPrepareCalculates[] = array(
                'start_date_week' => $arWeekOfMonth[$i],
                'last_date_week' => $arWeekOfMonth[$i + 1]
            );

            $kpiContactOnDay = $kpi;

            foreach ($arForPrepareCalculates as $key => $value) {
                $dayCount = ($value['last_date_week']['dayNumber'] - $value['start_date_week']['dayNumber']) + 1;
                $arForPrepareCalculates[$key]['dealNeed'] = round($kpiContactOnDay * $dayCount, 2);
            }

        }
        $needOnMonth = 0;
        foreach ($arForPrepareCalculates as $key=>$arItem){
            $needOnMonth += $arItem['dealNeed'];
        }
        return $needOnMonth;
    }

    public static function CountForKPI($date, $status, $manager_id, $week = 0, $month = 0){

        $arOrderIDs = ManagerDashboard::find()->select(['id'])->where(['id_manager'=>$manager_id])->asArray()->all();
        

        $arOrderIDs_temp = array();
        foreach ($arOrderIDs as $item){
            $arOrderIDs_temp[] = $item['id'];
        }
        $arOrderIDs = $arOrderIDs_temp;
        $arHistory = array();

        if($date && $week == 0 && $month == 0){
            $arHistory = HistoryDeal::find()
                ->where(['manager_id'=>$manager_id])
                ->andWhere("DATE(date) = DATE('$date')")
                ->asArray()
                ->all();

        }
        elseif ($date && $week == 1 && $month == 0){
            $arHistory = HistoryDeal::find()
                ->where(['manager_id'=>$manager_id])
                ->andWhere("WEEK(DATE(date)) = WEEK('$date')")
                ->asArray()
                ->all();
        }
        elseif ($date && $week == 0 && $month == 1){
            $arHistory = HistoryDeal::find()
                ->where(['manager_id'=>$manager_id])
                ->andWhere("MONTH(DATE(date)) = MONTH(DATE('$date'))")
                ->asArray()
                ->all();

        }

        $arDeal = array();
        foreach ($arHistory as $value){
            foreach ($arOrderIDs as $itemID){
                if($value['deal_id'] == $itemID ){
                    $arDeal[$value['deal_id']]['deal_id'] = $itemID;
                    $arDeal[$value['deal_id']]['manager_id'] = $value['manager_id'];
                    $arDeal[$value['deal_id']]['history'][] = $value['new_status_id'];
                }
            }
        }
        $arDeal_tmp = array();
        foreach ($arDeal as $value){
            $arDeal_tmp[] = $value;
        }
        $arDeal = $arDeal_tmp;

        $count = 0;
        foreach ($arDeal as $key=>$item){
            if (in_array($status, $item['history'])){
                $count += 1;
            }
        }



        return $count;
    }

    public static function generateArrayResult($manager_id)
    {
        $kpi = KpiManager::find()->where(['manager_id'=>$manager_id])->andWhere("MONTH(DATE(date)) = MONTH(DATE(NOW()))")->asArray()->all();
        $kpi = $kpi[count($kpi)-1];

        $arResult = array(
            'today' => array(
                'today_kpi_deals' => $kpi['kpi_deals_day'] ? $kpi['kpi_deals_day'] : 0,
                'today_kpi_contacts' => $kpi['kpi_contacts_day'] ? $kpi['kpi_contacts_day'] : 0,
                'today_kpi_kp' => $kpi['kpi_kp_day'] ? $kpi['kpi_kp_day'] : 0,
                'today_kpi_sale' => $kpi['kpi_sale_day'] ? $kpi['kpi_sale_day'] : 0,
            ),
            'week' => array(
                'week_kpi_deals' => self::CalculateOnWeek($kpi['kpi_deals_day']),
                'week_kpi_contacts' => self::CalculateOnWeek($kpi['kpi_contacts_day']),
                'week_kpi_kp' => self::CalculateOnWeek($kpi['kpi_kp_day']),
                'week_kpi_sale' => self::CalculateOnWeek($kpi['kpi_sale_day']),
            ),
            'month' => array(
                'month_kpi_deals' => self::CalculateOnMonth($kpi['kpi_deals_day']),
                'month_kpi_contacts' => self::CalculateOnWeek($kpi['kpi_contacts_day']),
                'month_kpi_kp' => self::CalculateOnMonth($kpi['kpi_kp_day']),
                'month_kpi_sale' => self::CalculateOnMonth($kpi['kpi_sale_day']),
            ),
            'kpi' => array(
                'today' => array(
                    'today_kpi_deals' => self::CountForKPI(date('Y-m-d'), 1, $manager_id),
                    'today_kpi_contacts' => self::CountForKPI(date('Y-m-d'), 2, $manager_id),
                    'today_kpi_kp' => self::CountForKPI(date('Y-m-d'), 9, $manager_id),
                    'today_kpi_sale' => self::CountForKPI(date('Y-m-d'), 6, $manager_id),
                ),
                'week' => array(
                    'week_kpi_deals' => self::CountForKPI(date('Y-m-d'), 1, $manager_id, 1, 0),
                    'week_kpi_contacts' => self::CountForKPI(date('Y-m-d'), 2, $manager_id, 1, 0),
                    'week_kpi_kp' => self::CountForKPI(date('Y-m-d'), 9, $manager_id, 1, 0),
                    'week_kpi_sale' => self::CountForKPI(date('Y-m-d'), 6, $manager_id, 1, 0),
                ),
                'month' => array(
                    'month_kpi_deals' => self::CountForKPI(date('Y-m-d'), 1, $manager_id, 0, 1),
                    'month_kpi_contacts' => self::CountForKPI(date('Y-m-d'), 2, $manager_id, 0, 1),
                    'month_kpi_kp' => self::CountForKPI(date('Y-m-d'), 9, $manager_id, 0, 1),
                    'month_kpi_sale' => self::CountForKPI(date('Y-m-d'), 6, $manager_id, 0, 1),
                ),
            )
        );

        return $arResult;
    }
}
