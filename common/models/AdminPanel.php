<?php


namespace common\models;


use yii\base\Model;

class AdminPanel extends Model
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

                    'in_process_commission', 'today_commission', 'week_commission', 'month_commission', 'month_prev_commission', 'all_commission'
                ],
                'number'
            ],
            [['manager_name'], 'string', 'max'=>255]

        ];
    }
}