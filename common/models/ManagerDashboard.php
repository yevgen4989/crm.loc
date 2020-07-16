<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;
use yii\web\Response;

/**
 * This is the model class for table "manager_dashboard".
 *
 * @property int $id
 * @property int $id_manager
 * @property int $services_id
 * @property string $date
 * @property int $status_order_id
 * @property string $account_name
 * @property int $bool_fixed_or_individ
 * @property float $tax
 * @property int $for_trash
 *
 * @property CommentDeal[] $text
 * @property Contacts[] $contacts
 * @property HistoryDeal[] $historyDeals
 * @property IndividualBonuses $individualBonus
 * @property StatusOrder $statusOrder
 * @property UserPersonalInfo $manager
 * @property Services $services
 * @property PriceOrder[] $price_deal
 */
class ManagerDashboard extends \yii\db\ActiveRecord
{
    public $contacts;
    public $price_deal;
    public $text;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manager_dashboard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['contacts', 'price_deal', 'text'] , 'safe' ],
            ['contacts', 'validateContacts'],
            ['price_deal', 'validatePriceOrder'],


            [['id_manager', 'services_id', 'date', 'status_order_id', 'account_name', 'bool_fixed_or_individ', 'tax'], 'required'],
            [['id_manager', 'services_id', 'status_order_id', 'bool_fixed_or_individ', 'for_trash'], 'integer'],
            [['date'], 'safe'],
            [['tax'], 'number'],
            [['account_name'], 'string', 'max' => 255],
            ['account_name', 'match', 'pattern' => '/^[a-z]\w*$/i'],
            ['account_name','validateAccountName'],
            [['status_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['status_order_id' => 'id']],
            [['id_manager'], 'exist', 'skipOnError' => true, 'targetClass' => UserPersonalInfo::className(), 'targetAttribute' => ['id_manager' => 'user_id']],
            [['services_id'], 'exist', 'skipOnError' => true, 'targetClass' => Services::className(), 'targetAttribute' => ['services_id' => 'id']],
        ];
    }

    public function validateAccountName($attribute){

        if(!Yii::$app->authManager->getAssignment('admin', \Yii::$app->user->id)){

            $account_name = ManagerDashboard::find()->select(['account_name', 'id_manager'])->where('id_manager !='.\Yii::$app->user->id)->andWhere('status_order_id != 7')->asArray()->all();
            $profile_name = $this->attributes;
            foreach ($account_name as $key => $item){
                if ($item['account_name'] == $profile_name['account_name']){

                    $this->addError($attribute, 'This Account fixed for the manager '.UserPersonalInfo::findOne(['user_id'=>$item['id_manager']])->name.'('.$item['id_manager'].')');
                }
            }
        }

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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_manager' => 'Id Manager',
            'services_id' => 'Services ID',
            'date' => 'Date',
            'status_order_id' => 'Status Order ID',
            'account_name' => 'Account Name',
            'bool_fixed_or_individ' => 'Bool Fixed Or Individ',
            'tax' => 'Tax',
            'for_trash' => 'В корзине',
        ];
    }

    /**
     * Gets query for [[CommentDeals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommentDeals()
    {
        return $this->hasMany(CommentDeal::className(), ['deal_id' => 'id']);
    }

    /**
     * Gets query for [[Contacts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contacts::className(), ['deal_id' => 'id']);
    }

    /**
     * Gets query for [[HistoryDeals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryDeals()
    {
        return $this->hasMany(HistoryDeal::className(), ['deal_id' => 'id']);
    }

    /**
     * Gets query for [[IndividualBonus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndividualBonus()
    {
        return $this->hasOne(IndividualBonuses::className(), ['deal_id' => 'id']);
    }

    /**
     * Gets query for [[StatusOrder]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusOrder()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'status_order_id']);
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(UserPersonalInfo::className(), ['user_id' => 'id_manager']);
    }

    /**
     * Gets query for [[Services]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServices()
    {
        return $this->hasOne(Services::className(), ['id' => 'services_id']);
    }

    /**
     * Gets query for [[PriceOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriceOrders()
    {
        return $this->hasMany(PriceOrder::className(), ['order_id' => 'id']);
    }
}
