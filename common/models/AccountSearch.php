<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\debug\components\search\Filter;
use yii\debug\components\search\matchers;
use yii2mod\query\ArrayQuery;

/**
 * ManagerDashboardSearch represents the model behind the search form of `common\models\ManagerDashboard`.
 */
class AccountSearch extends Account
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

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $arDeals = ManagerDashboard::find()->select('account_name')->asArray()->all();

        $arAccount = array();
        foreach ($arDeals as $key=>$deal){
            $arAccount[] = $deal['account_name'];
        }
        $arAccount = array_unique($arAccount);

        $arAccountModel = array();
        foreach ($arAccount as $item){
            $model = new Account();
            $model->account_name = $item;

            $arAccountModel[] = $model;
        }

        $query = new ArrayQuery();
        $query->from($arAccountModel);


        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(['account_name' => $this->account_name]);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->all(),
            'sort' => [
                'attributes' => [
                    'account_name'
                ],
            ],
            'pagination' => [
                'pageSize' => false
            ],
        ]);

        return $dataProvider;
    }
}
