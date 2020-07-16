<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "type_contact".
 *
 * @property int $id
 * @property string $name
 *
 * @property ManagerDashboard[] $managerDashboards
 */
class TypeContact extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'type_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[ManagerDashboards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManagerDashboards()
    {
        return $this->hasMany(ManagerDashboard::className(), ['type_contact_id' => 'id']);
    }
}
