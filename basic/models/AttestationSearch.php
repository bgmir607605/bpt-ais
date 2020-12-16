<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Attestation;
use yii\helpers\ArrayHelper;

/**
 * AttestationSearch represents the model behind the search form of `app\models\Attestation`.
 */
class AttestationSearch extends Attestation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'semestrNumber', 'deleted'], 'integer'],
            [['date', 'type'], 'safe'],
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
        $query = Attestation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'semestrNumber' => $this->semestrNumber,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
    public function forGroup($group, $params)
    {
        $query = Attestation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        
        $query->andWhere(['in', 'id', ArrayHelper::getColumn($group->attestations, 'id')]);
        
        $query->andFilterWhere([
            'date' => $this->date,
            'semestrNumber' => $this->semestrNumber,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
