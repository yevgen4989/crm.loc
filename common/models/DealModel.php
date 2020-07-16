<?php


namespace common\models;

/**
 * This is the model class for table "manager_dashboard".
 *
 * @property int $id
 * @property int $id_manager
 * @property string $date
 * @property int $status_order_id
 * @property string $additional_info
 * @property int $bool_fixed_or_individ
 * @property float $tax
 * @property string $account_name
 * @property int $manager_id
 * @property int $deal_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property int $type_contact_id
 * @property int $lpr_bool
 *
 * @property HistoryDeal[] $historyDeals
 * @property IndividualBonuses $individualBonus
 * @property StatusOrder $statusOrder
 * @property UserPersonalInfo $manager
 * @property PriceOrder[] $priceOrders
 * @property User $manager
 * @property ManagerDashboard $deal
 * @property TypeContact $typeContact
 */

use yii\base\Model;

class DealModel extends Model
{


    public $id_manager;
    public $date;
    public $status_order_id;
    public $bool_fixed_or_individ;
    public $tax;
    public $additional_info;
    public $account_name;

    public $services_id;
    public $text;

    public $contacts;
    public $price_deal;


    public function rules()
    {
        return [
            [['contacts', 'price_deal', 'services_id', 'account_name'] , 'safe' ],
            ['contacts', 'validateContacts'],
            ['price_deal', 'validatePriceOrder'],

            [['services_id', 'status_order_id', 'account_name'], 'required'],
            [['id_manager', 'status_order_id', 'services_id', 'bool_fixed_or_individ'], 'integer'],
            [['date'], 'safe'],
            [['tax'], 'number'],
            [['text'], 'string'],
            [['additional_info', 'account_name'], 'string', 'max' => 255],
            ['account_name', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            [['services_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['services_id' => 'id']],
            [['status_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['status_order_id' => 'id']],
            [['id_manager'], 'exist', 'skipOnError' => true, 'targetClass' => UserPersonalInfo::className(), 'targetAttribute' => ['id_manager' => 'user_id']],
        ];

    }

    public function validateContacts($attribute)
    {
        foreach($this->$attribute as $index => $row) {
            $acc = new Contacts($row);
            if(!$acc->validate()){
                foreach ($acc->errors as $key => $error){
                    $keyAttr = $attribute . '[' . $index . '][' . $key . ']';
                    $this->addError($keyAttr, $error[0]);
                    
                }
            }
        }
    }

    public function validatePriceOrder($attribute)
    {
        foreach($this->$attribute as $index => $row) {
            $acc = new PriceOrder($row);
            if(!$acc->validate()){
                foreach ($acc->errors as $key => $error){
                    $keyAttr = $attribute . '[' . $index . '][' . $key . ']';
                    $this->addError($keyAttr, $error[0]);

                }
            }
        }
    }

    public function getServices()
    {
        return $this->hasOne(Services::className(), ['id' => 'services_id']);
    }

    public function getManagerDashboards()
    {
        return $this->hasMany(ManagerDashboard::className(), ['account_id' => 'id']);
    }

    public function getHistoryDeals()
    {
        return $this->hasMany(HistoryDeal::className(), ['deal_id' => 'id']);
    }

    public function getIndividualBonus()
    {
        return $this->hasOne(IndividualBonuses::className(), ['deal_id' => 'id']);
    }

    public function getStatusOrder()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'status_order_id']);
    }

    public function getPriceOrders()
    {
        return $this->hasMany(PriceOrder::className(), ['order_id' => 'id']);
    }
}