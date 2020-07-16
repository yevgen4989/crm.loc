<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "price_order".
 *
 * @property int $id
 * @property int $order_id
 * @property float|null $price
 * @property string|null $comment
 *
 * @property ManagerDashboard $order
 */
class PriceOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'price_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['price'], 'number'],
            [['comment'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerDashboard::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'price' => 'Price',
            'comment' => 'Comment',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ManagerDashboard::className(), ['id' => 'order_id']);
    }

    public function getSummaryDeal($id){
        $priceOrder = PriceOrder::find()->where(['order_id'=>$id])->asArray()->all();
        $priceSum = 0;

        foreach ($priceOrder as $value){
            $priceOrder += $value['price'];
        }
        return $priceSum;
    }
}
