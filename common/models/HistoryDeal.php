<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history_deal".
 *
 * @property int $id
 * @property int $deal_id
 * @property int $manager_id
 * @property int|null $old_status_id
 * @property int|null $new_status_id
 * @property string $date
 *
 * @property ManagerDashboard $deal
 * @property User $manager
 * @property StatusOrder $oldStatus
 * @property StatusOrder $newStatus
 */
class HistoryDeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'history_deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deal_id', 'manager_id', 'date'], 'required'],
            [['deal_id', 'manager_id', 'old_status_id', 'new_status_id'], 'integer'],
            [['date'], 'safe'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerDashboard::className(), 'targetAttribute' => ['deal_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['manager_id' => 'id']],
            [['old_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['old_status_id' => 'id']],
            [['new_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['new_status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deal_id' => 'Deal ID',
            'manager_id' => 'Manager ID',
            'old_status_id' => 'Old Status ID',
            'new_status_id' => 'New Status ID',
            'date' => 'Date',
        ];
    }

    /**
     * Gets query for [[Deal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeal()
    {
        return $this->hasOne(ManagerDashboard::className(), ['id' => 'deal_id']);
    }

    /**
     * Gets query for [[Manager]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManager()
    {
        return $this->hasOne(UserPersonalInfo::className(), ['user_id' => 'manager_id']);
    }

    /**
     * Gets query for [[OldStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOldStatus()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'old_status_id']);
    }

    /**
     * Gets query for [[NewStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewStatus()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'new_status_id']);
    }
}
