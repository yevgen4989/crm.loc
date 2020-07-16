<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "contacts".
 *
 * @property string $account_name
 *
 * @property ManagerDashboard $deal
 */
class Account extends Model
{
    public $account_name;

    public function rules()
    {
        return [
            [['account_name'], 'required'],
            [['account_name'], 'string', 'max' => 255],
            ['account_name', 'match', 'pattern' => '/^[a-z]\w*$/i'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'account_name' => 'Аккаунт'
        ];
    }

    public function getDeal()
    {
        return $this->hasOne(ManagerDashboard::className(), ['account_name' => 'account_name']);
    }
}
