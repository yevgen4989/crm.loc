<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "status_order".
 *
 * @property int $id
 * @property string $name
 * @property int $active
 * @property int $sort
 *
 * @property CommentDeal[] $commentDeals
 * @property HistoryDeal[] $historyDeals
 * @property HistoryDeal[] $historyDeals0
 * @property ManagerDashboard[] $managerDashboards
 */
class StatusOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'active', 'sort'], 'required'],
            [['active', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'active' => 'Active',
            'sort' => 'Sort',
        ];
    }

    /**
     * Gets query for [[CommentDeals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommentDeals()
    {
        return $this->hasMany(CommentDeal::className(), ['status_deal_id' => 'id']);
    }

    /**
     * Gets query for [[HistoryDeals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryDeals()
    {
        return $this->hasMany(HistoryDeal::className(), ['old_status_id' => 'id']);
    }

    /**
     * Gets query for [[HistoryDeals0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryDeals0()
    {
        return $this->hasMany(HistoryDeal::className(), ['new_status_id' => 'id']);
    }

    /**
     * Gets query for [[ManagerDashboards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManagerDashboards()
    {
        return $this->hasMany(ManagerDashboard::className(), ['status_order_id' => 'id']);
    }
}
