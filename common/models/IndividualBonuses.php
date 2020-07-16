<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "individual_bonuses".
 *
 * @property int $id
 * @property string $name
 * @property int $percent
 * @property int $manager_id
 * @property int|null $deal_id
 * @property int $for_all
 * @property int|null $min_count_deal
 * @property int|null $max_count_deal
 *
 * @property ManagerDashboard $deal
 * @property User $manager
 */
class IndividualBonuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'individual_bonuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'percent', 'manager_id'], 'required'],
            [['percent', 'manager_id', 'deal_id', 'for_all', 'min_count_deal', 'max_count_deal'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['deal_id'], 'unique'],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerDashboard::className(), 'targetAttribute' => ['deal_id' => 'id']],
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
            'name' => 'Name',
            'percent' => 'Percent',
            'manager_id' => 'Manager ID',
            'deal_id' => 'Deal ID',
            'for_all' => 'For All',
            'min_count_deal' => 'Min Count Deal',
            'max_count_deal' => 'Max Count Deal',
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
        return $this->hasOne(User::className(), ['id' => 'manager_id']);
    }
}
