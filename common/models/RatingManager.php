<?php


namespace common\models;
/**
 *
 * @property int $conversation_order
 * @property int $sale_order
 * @property int $manager_id
 * @property string $manager_name
 * @property float $sum_month_order
 * @property float $sum_prevmonth_order
 * @property float $sum_all_order
 *
 */
use yii\base\Model;

class RatingManager extends Model
{
    public $manager_id;
    public $manager_name;
    public $conversation_order;
    public $sale_order;
    public $sum_month_order;
    public $sum_prevmonth_order;
    public $sum_all_order;

    public function rules()
    {
        return [
            [['manager_id', 'sale_order', 'conversation_order'], 'integer'],
            [['manager_name'], 'string', 'max'=>255],
            [['sum_month_order', 'sum_prevmonth_order', 'sum_all_order'], 'number'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'manager_id' => 'ID Менеджера',
            'manager_name' => 'Имя менеджера',
            'sale_order' => 'Завершенных сделок за месяц',
            'conversation_order' => 'В обработке ',
            'sum_month_order' => 'Сумма продаж за месяц',
            'sum_prevmonth_order' => 'Сумма продаж за предыдущий месяц',
            'sum_all_order' => 'Сумма продаж за всё время',
        ];
    }
}