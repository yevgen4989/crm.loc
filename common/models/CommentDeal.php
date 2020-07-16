<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment_deal".
 *
 * @property int $id
 * @property string $text
 * @property int $deal_id
 * @property int $status_deal_id
 * @property string $date
 *
 * @property ManagerDashboard $deal
 * @property StatusOrder $statusDeal
 */
class CommentDeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment_deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['deal_id', 'status_deal_id'], 'integer'],
            [['date'], 'safe'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerDashboard::className(), 'targetAttribute' => ['deal_id' => 'id']],
            [['status_deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusOrder::className(), 'targetAttribute' => ['status_deal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
            'deal_id' => 'Deal ID',
            'status_deal_id' => 'Status Deal ID',
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
     * Gets query for [[StatusDeal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusDeal()
    {
        return $this->hasOne(StatusOrder::className(), ['id' => 'status_deal_id']);
    }
}
