<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;
use yii\debug\components\search\matchers;
use yii2mod\query\ArrayQuery;

/**
 * ManagerDashboardSearch represents the model behind the search form of `common\models\ManagerDashboard`.
 */
class AdminPanelSearch extends AdminPanel
{

    public $manager_id;
    public $manager_name;

    public $deal_day;
    public $deal_week;
    public $deal_month;
    public $deal_prev_month;
    public $deal_all;

    public $contacts_day;
    public $contacts_week;
    public $contacts_month;
    public $contacts_prev_month;
    public $contacts_all;

    public $in_work_day;
    public $in_work_week;
    public $in_work_month;
    public $in_work_prev_month;
    public $in_work_all;

    public $failure_day;
    public $failure_week;
    public $failure_month;
    public $failure_prev_month;
    public $failure_all;

    public $sale_day;
    public $sale_week;
    public $sale_month;
    public $sale_prev_month;
    public $sale_all;


    public $kpi_deals_day;
    public $kpi_contacts_day;
    public $kpi_kp_day;
    public $kpi_sale_day;

    public $kpi_deals_week;
    public $kpi_contacts_week;
    public $kpi_kp_week;
    public $kpi_sale_week;

    public $kpi_deals_month;
    public $kpi_contacts_month;
    public $kpi_kp_month;
    public $kpi_sale_month;

    public $kpi_deals_day_result;
    public $kpi_contacts_day_result;
    public $kpi_kp_day_result;
    public $kpi_sale_day_result;

    public $kpi_deals_week_result;
    public $kpi_contacts_week_result;
    public $kpi_kp_week_result;
    public $kpi_sale_week_result;

    public $kpi_deals_month_result;
    public $kpi_contacts_month_result;
    public $kpi_kp_month_result;
    public $kpi_sale_month_result;

    public $in_process_commission;
    public $today_commission;
    public $week_commission;
    public $month_commission;
    public $month_prev_commission;
    public $all_commission;


    public function rules()
    {
        return [
            [
                [
                    'manager_id',
                    'deal_day', 'deal_week', 'deal_month', 'deal_prev_month', 'deal_all',
                    'contacts_day', 'contacts_week', 'contacts_month', 'contacts_prev_month', 'contacts_all',
                    'in_work_day', 'in_work_week', 'in_work_month', 'in_work_prev_month', 'in_work_all',
                    'failure_day', 'failure_week', 'failure_month', 'failure_prev_month', 'failure_all',
                    'sale_day', 'sale_week', 'sale_month', 'sale_prev_month', 'sale_all'
                ],
                'integer'],
            [
                [
                    'kpi_deals_day', 'kpi_contacts_day', 'kpi_kp_day', 'kpi_sale_day',
                    'kpi_deals_week', 'kpi_contacts_week', 'kpi_kp_week', 'kpi_sale_week',
                    'kpi_deals_month', 'kpi_contacts_month', 'kpi_kp_month', 'kpi_sale_month',

                    'kpi_deals_day_result', 'kpi_contacts_day_result', 'kpi_kp_day_result', 'kpi_sale_day_result',
                    'kpi_deals_week_result', 'kpi_contacts_week_result', 'kpi_kp_week_result', 'kpi_sale_week_result',
                    'kpi_deals_month_result', 'kpi_contacts_month_result', 'kpi_kp_month_result', 'kpi_sale_month_result',

                    'in_process_commission', 'today_commission', 'week_commission', 'month_commission', 'month_prev_commission', 'all_commission',
                ],
                'number'
            ],
            [['manager_name'], 'string', 'max'=>255]
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {

        $arResult = array();

        if($this->manager_id != null){
            $managers = \common\models\UserPersonalInfo::find()->where(['user_id'=>$this->manager_id])->all();
        }else{
            $managers = \common\models\UserPersonalInfo::find()->all();
        }

        $arrIdManager = array();
        foreach ($managers as $key=>$manager){
            if(\Yii::$app->authManager->getAssignment('manager', $manager->user_id)){
                $arrIdManager[] = array(
                    'id' => $manager->user_id,
                    'name' => $manager->name,
                );
            }
        }
        $arStatistics = array();
        foreach ($arrIdManager as $manager){
            $arStatistics[] = array(
                'manager_id' => $manager['id'],
                'statistics' => \common\models\Statistics::getStatistics($manager['id'])
            );
        }
        $arKpiOfManagers = array();
        foreach ($arrIdManager as $manager){
            $arKpiOfManagers[] = array(
                'manager_id' => $manager['id'],
                'kpi' => \common\models\KpiManager::generateArrayResult($manager['id'])
            );
        }
        foreach ($arrIdManager as $key=>$manager){
            $arResult[$key]['id'] = $manager['id'];
            $arResult[$key]['name'] = $manager['name'];
            foreach ($arStatistics as $statistic){
                if($manager['id'] == $statistic['manager_id']){
                    $arResult[$key]['statistics'] = $statistic['statistics'];
                }
            }
            foreach ($arKpiOfManagers as $kpiOfManager){
                if($manager['id'] == $kpiOfManager['manager_id']){
                    $arResult[$key]['kpi'] = $kpiOfManager['kpi'];
                }
            }
        }
        $arAdminPanelList = array();
        foreach ($arResult as $key=>$item){
            $arAdminPanel = new \common\models\AdminPanel();
            $arAdminPanel->manager_id = $item['id'];
            $arAdminPanel->manager_name = $item['name'];

            $arAdminPanel->deal_day = $item['statistics']['day']['deal'];
            $arAdminPanel->deal_week = $item['statistics']['week']['deal'];
            $arAdminPanel->deal_month = $item['statistics']['month']['deal'];
            $arAdminPanel->deal_prev_month = $item['statistics']['last_month']['deal'];
            $arAdminPanel->deal_all = $item['statistics']['all']['deal'];

            $arAdminPanel->contacts_day = $item['statistics']['day']['contact'];
            $arAdminPanel->contacts_week = $item['statistics']['week']['contact'];
            $arAdminPanel->contacts_month = $item['statistics']['month']['contact'];
            $arAdminPanel->contacts_prev_month = $item['statistics']['last_month']['contact'];
            $arAdminPanel->contacts_all = $item['statistics']['all']['contact'];

            $arAdminPanel->in_work_day = $item['statistics']['day']['in_work'];
            $arAdminPanel->in_work_week = $item['statistics']['week']['in_work'];
            $arAdminPanel->in_work_month = $item['statistics']['month']['in_work'];
            $arAdminPanel->in_work_prev_month = $item['statistics']['last_month']['in_work'];
            $arAdminPanel->in_work_all = $item['statistics']['all']['in_work'];

            $arAdminPanel->failure_day = $item['statistics']['day']['failure'];
            $arAdminPanel->failure_week = $item['statistics']['week']['failure'];
            $arAdminPanel->failure_month = $item['statistics']['month']['failure'];
            $arAdminPanel->failure_prev_month = $item['statistics']['last_month']['failure'];
            $arAdminPanel->failure_all = $item['statistics']['all']['failure'];

            $arAdminPanel->sale_day = $item['statistics']['day']['sale'];
            $arAdminPanel->sale_week = $item['statistics']['week']['sale'];
            $arAdminPanel->sale_month = $item['statistics']['month']['sale'];
            $arAdminPanel->sale_prev_month = $item['statistics']['last_month']['sale'];
            $arAdminPanel->sale_all = $item['statistics']['all']['sale'];

            $arAdminPanel->kpi_deals_day = $item['kpi']['today']['today_kpi_deals'];
            $arAdminPanel->kpi_contacts_day = $item['kpi']['today']['today_kpi_contacts'];
            $arAdminPanel->kpi_kp_day = $item['kpi']['today']['today_kpi_kp'];
            $arAdminPanel->kpi_sale_day = $item['kpi']['today']['today_kpi_sale'];

            $arAdminPanel->kpi_deals_week = $item['kpi']['week']['week_kpi_deals'];
            $arAdminPanel->kpi_contacts_week = $item['kpi']['week']['week_kpi_contacts'];
            $arAdminPanel->kpi_kp_week = $item['kpi']['week']['week_kpi_kp'];
            $arAdminPanel->kpi_sale_week = $item['kpi']['week']['week_kpi_sale'];

            $arAdminPanel->kpi_deals_month = $item['kpi']['month']['month_kpi_deals'];
            $arAdminPanel->kpi_contacts_month = $item['kpi']['month']['month_kpi_contacts'];
            $arAdminPanel->kpi_kp_month = $item['kpi']['month']['month_kpi_kp'];
            $arAdminPanel->kpi_sale_month = $item['kpi']['month']['month_kpi_sale'];

            $arAdminPanel->kpi_deals_day_result = $item['kpi']['kpi']['today']['today_kpi_deals'];
            $arAdminPanel->kpi_contacts_day_result = $item['kpi']['kpi']['today']['today_kpi_contacts'];
            $arAdminPanel->kpi_kp_day_result = $item['kpi']['kpi']['today']['today_kpi_kp'];
            $arAdminPanel->kpi_sale_day_result = $item['kpi']['kpi']['today']['today_kpi_sale'];

            $arAdminPanel->kpi_deals_week_result = $item['kpi']['kpi']['week']['week_kpi_deals'];
            $arAdminPanel->kpi_contacts_week_result = $item['kpi']['kpi']['week']['week_kpi_contacts'];
            $arAdminPanel->kpi_kp_week_result = $item['kpi']['kpi']['week']['week_kpi_kp'];
            $arAdminPanel->kpi_sale_week_result = $item['kpi']['kpi']['week']['week_kpi_sale'];

            $arAdminPanel->kpi_deals_month_result = $item['kpi']['kpi']['month']['month_kpi_deals'];
            $arAdminPanel->kpi_contacts_month_result = $item['kpi']['kpi']['month']['month_kpi_contacts'];
            $arAdminPanel->kpi_kp_month_result = $item['kpi']['kpi']['month']['month_kpi_kp'];
            $arAdminPanel->kpi_sale_month_result = $item['kpi']['kpi']['month']['month_kpi_sale'];


            $arrInProcess = ManagerDashboard::find()->where(['id_manager' => $item['id']])->andWhere('status_order_id < 6')->select(['tax'])->asArray()->all();
            $preInProcess = 0;
            foreach ($arrInProcess as $arrInProcessItem) {
                $preInProcess += $arrInProcessItem['tax'];
            }
            $arAdminPanel->in_process_commission = $preInProcess;

            $arrTodayCommission = ManagerDashboard::find()
                ->joinWith('historyDeals')
                ->where(['id_manager'=>$item['id'], 'status_order_id' => 6])
                ->andWhere(['history_deal.new_status_id' => 6, 'DATE(history_deal.date)'=>date('Y-m-d')])
                ->select(['tax'])
                ->asArray()
                ->all();
            $preTodayCommission = 0;
            foreach ($arrTodayCommission as $todayCommissionItem){
                $preTodayCommission += $todayCommissionItem['tax'];
            }
            $arAdminPanel->today_commission = $preTodayCommission;

            $arrTax = ManagerDashboard::find()
                ->joinWith('historyDeals')
                ->where(['id_manager'=>$item['id'], 'status_order_id' => 6])
                ->andWhere(['history_deal.new_status_id' => 6])->andWhere("WEEK(DATE(history_deal.date)) = WEEK(NOW())")
                ->select(['tax'])
                ->asArray()
                ->all();
            $preTax = 0;
            foreach ($arrTax as $weekCommissionItem){
                $preTax += $weekCommissionItem['tax'];
            }
            $arAdminPanel->week_commission = $preTax;

            $arrTax = ManagerDashboard::find()
                ->joinWith('historyDeals')
                ->where(['id_manager'=>$item['id'], 'status_order_id' => 6])
                ->andWhere(['history_deal.new_status_id' => 6])
                ->andWhere("MONTH(DATE(history_deal.date)) = MONTH(NOW())")
                ->select(['tax'])
                ->asArray()
                ->all();
            $preTax = 0;
            foreach ($arrTax as $monthCommissionItem){
                $preTax += $monthCommissionItem['tax'];
            }
            $arAdminPanel->month_commission = $preTax;

            $arrTax = ManagerDashboard::find()
                ->joinWith('historyDeals')
                ->where(['id_manager'=>$item['id'], 'status_order_id' => 6])
                ->andWhere(['history_deal.new_status_id' => 6])
                ->andWhere("MONTH(DATE(history_deal.date)) = MONTH(NOW())-1")
                ->select(['tax'])
                ->asArray()
                ->all();
            $preTax = 0;
            foreach ($arrTax as $prevMonthCommissionItem){
                $preTax += $prevMonthCommissionItem['tax'];
            }
            $arAdminPanel->month_prev_commission = $preTax;



            $arAdminPanelList[] = $arAdminPanel;

        }


        $query = new ArrayQuery();
        $query->from($arAdminPanelList);




        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['manager_id' => $this->manager_id]);
            $query->andFilterWhere(['manager_name' => $this->manager_name]);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => [
                    'manager_id', 'manager_name',

                    'deal_day', 'deal_week', 'deal_month', 'deal_prev_month', 'deal_all',
                    'contacts_day', 'contacts_week', 'contacts_month', 'contacts_prev_month', 'contacts_all',
                    'in_work_day', 'in_work_week', 'in_work_month', 'in_work_prev_month', 'in_work_all',
                    'failure_day', 'failure_week', 'failure_month', 'failure_prev_month', 'failure_all',
                    'sale_day', 'sale_week', 'sale_month', 'sale_prev_month', 'sale_all',

                    'kpi_deals_day', 'kpi_contacts_day', 'kpi_kp_day', 'kpi_sale_day',
                    'kpi_deals_week', 'kpi_contacts_week', 'kpi_kp_week', 'kpi_sale_week',
                    'kpi_deals_month', 'kpi_contacts_month', 'kpi_kp_month', 'kpi_sale_month',

                    'kpi_deals_day_result', 'kpi_contacts_day_result', 'kpi_kp_day_result', 'kpi_sale_day_result',
                    'kpi_deals_week_result', 'kpi_contacts_week_result', 'kpi_kp_week_result', 'kpi_sale_week_result',
                    'kpi_deals_month_result', 'kpi_contacts_month_result', 'kpi_kp_month_result', 'kpi_sale_month_result',

                    'in_process_commission', 'today_commission', 'week_commission', 'month_commission', 'month_prev_commission', 'all_commission'
                ],
            ],
            'pagination' => [
                'pageSize' => false
            ],
        ]);

        return $dataProvider;
    }
}
