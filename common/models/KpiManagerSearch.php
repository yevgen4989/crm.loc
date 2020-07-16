<?php

namespace common\models;

use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\KpiManager;

/**
 * KpiManagerSearch represents the model behind the search form of `common\models\KpiManager`.
 */
class KpiManagerSearch extends KpiManager
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'manager_id', 'kpi_deals_day', 'kpi_contacts_day', 'kpi_kp_day', 'kpi_sale_day'], 'integer'],
            [['date'], 'safe'],
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
        $query = KpiManager::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (Yii::$app->request->getQueryParam(self::formName()) && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'manager_id' => $this->manager_id,
            'kpi_deals_day' => $this->kpi_deals_day,
            'kpi_contacts_day' => $this->kpi_contacts_day,
            'kpi_kp_day' => $this->kpi_kp_day,
            'kpi_sale_day' => $this->kpi_sale_day,
        ]);

        if($this->date != null){


            $date = DateTime::createFromFormat('m.Y', $this->date);
            $date = $date->format('Y-m').'-01';
            
            $query->andWhere("MONTH(DATE(date)) = MONTH(DATE('".$date."'))");
        }

        return $dataProvider;
    }
}
