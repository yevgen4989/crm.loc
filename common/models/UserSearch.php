<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $name;

    public $kpi_day_deals;
    public $kpi_day_contacts;
    public $kpi_kp_day;
    public $kpi_sale_day;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'individual_or_fixed', 'on_create_or_in_count', 'created_at', 'updated_at', 'kpi_day_deals', 'kpi_day_contacts', 'kpi_kp_day', 'kpi_sale_day'], 'integer'],
            [['username', 'name', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = user.id')->where(['auth_assignment.item_name'=>'manager']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'name', 'id', 'status', 'username', 'email', 'created_at', 'updated_at', 'kpi_day_deals', 'kpi_day_contacts', 'kpi_kp_day', 'kpi_sale_day'
                ],
            ],
            'pagination' => [
                'pagesize' => false
            ]
        ]);

        if (Yii::$app->request->getQueryParam(self::formName()) && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'individual_or_fixed' => $this->individual_or_fixed,
            'on_create_or_in_count' => $this->on_create_or_in_count,
            'name' => $this->name
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'verification_token', $this->verification_token])
            ->andFilterWhere(['like', 'name', $this->name]);

        //TODO Нужно проверить работу, странно
        if ( ! is_null($this->created_at) && strpos($this->created_at, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);

            $start_date = date('Y-m-d',strtotime($start_date));
            $end_date = date('Y-m-d',strtotime($end_date));

            //TODO А именно тут
            $query->andFilterWhere(['between', 'user.created_at',
                date('Y-m-d 00:00:00', strtotime($start_date)),
                date('Y-m-d 23:59:59', strtotime($end_date))
            ]);
        }

        if ( ! is_null($this->updated_at) && strpos($this->updated_at, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->updated_at);

            $start_date = date('Y-m-d',strtotime($start_date));
            $end_date = date('Y-m-d',strtotime($end_date));

            //TODO и тут
            $query->andFilterWhere(['between', 'user.updated_at',
                date('Y-m-d 00:00:00', strtotime($start_date)),
                date('Y-m-d 23:59:59', strtotime($end_date))
            ]);
        }

        return $dataProvider;
    }
}
