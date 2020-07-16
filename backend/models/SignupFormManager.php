<?php
namespace backend\models;

use common\models\KpiManager;
use common\models\UserPersonalInfo;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupFormManager extends Model
{
    public $id;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    public $username;
    public $email;
    public $password;
    public $status;

    public $kpi_day_deals;
    public $kpi_day_contacts;
    public $kpi_day_kp;
    public $kpi_day_sale;

    public $name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['name', 'required'],
            ['name' , 'string', 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['status', 'required'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],


            [['kpi_day_deals', 'kpi_day_contacts', 'kpi_day_kp', 'kpi_day_sale'], 'required'],
            [['id', 'kpi_day_deals', 'kpi_day_contacts', 'kpi_day_kp', 'kpi_day_sale'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'email' => 'Email',
            'name' => 'Имя',
            'password' => 'Пароль',
            'status' => 'Статус',
            'kpi_day_deals' => 'KPI Сделок в день на текущий месяц',
            'kpi_day_contacts' => 'KPI Звонков в день на текущий месяц',
            'kpi_day_kp' => 'Kpi КП в день на текущий месяц',
            'kpi_day_sale' => 'Kpi Продаж в день на текущий месяц',
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        $user->status = $this->status;

        $user->created_at = time();
        $user->updated_at = time();

        if($user->save()){
            $auth = Yii::$app->authManager;
            $role = $auth->getRole('manager');
            $auth->assign($role, $user->id);

            $nameUser = new UserPersonalInfo();
            $nameUser->user_id = $user->id;
            $nameUser->name = $this->name;
            $nameUser->save();

            $kpi_day = new KpiManager();
            $kpi_day->kpi_deals_day = $this->kpi_day_deals;
            $kpi_day->kpi_contacts_day = $this->kpi_day_contacts;
            $kpi_day->kpi_kp_day = $this->kpi_day_kp;
            $kpi_day->kpi_sale_day = $this->kpi_day_sale;

            $kpi_day->manager_id = $user->id;
            $kpi_day->date = date('Y-m-d');
            $kpi_day->save();

            return $user;
        }

        return null;
    }

    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
