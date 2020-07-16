<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "services".
 *
 * @property int $id
 * @property string $name
 * @property int $active
 *
 * @property ManagerDashboard[] $managerDashboards
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['active'], 'integer'],
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
        ];
    }

    /**
     * Gets query for [[ManagerDashboards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManagerDashboards()
    {
        return $this->hasMany(ManagerDashboard::className(), ['services_id' => 'id']);
    }
}
