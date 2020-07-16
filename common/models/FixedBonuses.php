<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "fixed_bonuses".
 *
 * @property int $id
 * @property string $name
 * @property int $bonuses
 * @property int $min_count_deal
 * @property int|null $max_count_deal
 */
class FixedBonuses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fixed_bonuses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'bonuses', 'min_count_deal'], 'required'],
            [['bonuses', 'min_count_deal', 'max_count_deal'], 'integer'],
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
            'bonuses' => 'Bonuses',
            'min_count_deal' => 'Min Count Deal',
            'max_count_deal' => 'Max Count Deal',
        ];
    }
}
