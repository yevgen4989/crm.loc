<?php

namespace common\models;

use kartik\dynagrid\DynaGridStore;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ManagerDashboard;

/**
 * ManagerDashboardSearch represents the model behind the search form of `common\models\ManagerDashboard`.
 */
class ManagerDashboardTrashSearch extends ManagerDashboard
{

    public $name;
    public $type_contact_id;
    public $phone;
    public $email;
    public $price;
    public $text;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type_contact_id', 'phone', 'email', 'price', 'text'], 'safe'],
            [['id', 'id_manager', 'status_order_id', 'bool_fixed_or_individ', 'type_contact_id'], 'integer'],
            [['date', 'additional_info'], 'safe'],
            [['tax'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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

        $query = ManagerDashboard::find()->where(['for_trash' => 1]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => false
            ],
            'sort'=> [
                'defaultOrder' => [
                    'status_order_id' => SORT_ASC,
                    'date' => SORT_ASC
                ]
            ]
        ]);

        //$query->joinWith(['priceOrders', 'commentDeals']);
        $query->joinWith('contacts', true, 'LEFT JOIN');
        $query->joinWith('priceOrders', true, 'LEFT JOIN');
        $query->joinWith('commentDeals', true, 'LEFT JOIN');
        $query->groupBy('id');
        if (Yii::$app->request->getQueryParam(self::formName()) && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $dataProvider->sort->attributes['name'] = [
            'asc' => ['contacts.name' => SORT_ASC],
            'desc' => ['contacts.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['type_contact_id'] = [
            'asc' => ['contacts.type_contact_id' => SORT_ASC],
            'desc' => ['contacts.type_contact_id' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['phone'] = [
            'asc' => ['contacts.phone' => SORT_ASC],
            'desc' => ['contacts.phone' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['email'] = [
            'asc' => ['contacts.email' => SORT_ASC],
            'desc' => ['contacts.email' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['price'] = [
            'asc' => ['price_order.price' => SORT_ASC],
            'desc' => ['price_order.price' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['text'] = [
            'asc' => ['comment_deal.text' => SORT_ASC],
            'desc' => ['comment_deal.text' => SORT_DESC],
        ];



        $query->andFilterWhere([
            'id' => $this->id,
            'id_manager' => $this->id_manager,
            'account_name' => $this->account_name,
            'status_order_id' => $this->status_order_id,
            'bool_fixed_or_individ' => $this->bool_fixed_or_individ,
            'tax' => $this->tax
        ])
            ->andFilterWhere(['like', 'comment_deal.text', $this->text])
            ->andFilterWhere(['like', 'price_order.price', $this->price])
            ->andFilterWhere(['like', 'contacts.name', $this->name])
            ->andFilterWhere(['like', 'contacts.email', $this->email])
            ->andFilterWhere(['like', 'contacts.phone', $this->phone])
            ->andFilterWhere(['contacts.type_contact_id'=> $this->type_contact_id, 'contacts.lpr_bool'=>1]);

        if ( ! is_null($this->date) && strpos($this->date, ' - ') !== false ) {
            list($start_date, $end_date) = explode(' - ', $this->date);

            $start_date = date('Y-m-d',strtotime($start_date));
            $end_date = date('Y-m-d',strtotime($end_date));

            $query->andFilterWhere(['between', 'manager_dashboard.date',
                date('Y-m-d 00:00:00', strtotime($start_date)),
                date('Y-m-d 23:59:59', strtotime($end_date))
            ]);
//            $this->date = null;
        }

        return $dataProvider;
    }
}
