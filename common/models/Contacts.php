<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property int $manager_id
 * @property int $deal_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property int $type_contact_id
 * @property int $lpr_bool
 *
 * @property ManagerDashboard $deal
 * @property UserPersonalInfo $manager
 * @property TypeContact $typeContact
 */
class Contacts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_contact_id', 'lpr_bool'], 'required'],
            [['manager_id', 'deal_id', 'type_contact_id', 'lpr_bool'], 'integer'],
            [['name', 'email'], 'string', 'max' => 255],
            ['email', 'email'],
            [['phone'], 'string', 'min'=>11, 'max'=>255],
            [['deal_id'], 'exist', 'skipOnError' => true, 'targetClass' => ManagerDashboard::className(), 'targetAttribute' => ['deal_id' => 'id']],
            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserPersonalInfo::className(), 'targetAttribute' => ['manager_id' => 'user_id']],
            [['type_contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeContact::className(), 'targetAttribute' => ['type_contact_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => 'Manager ID',
            'deal_id' => 'Deal ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'type_contact_id' => 'Type Contact ID',
            'lpr_bool' => 'Lpr Bool',
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
     * Gets query for [[TypeContact]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeContact()
    {
        return $this->hasOne(TypeContact::className(), ['id' => 'type_contact_id']);
    }
}
