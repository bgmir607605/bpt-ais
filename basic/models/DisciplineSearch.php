<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Discipline;

/**
 * DisciplineSearch represents the model behind the search form of `app\models\Discipline`.
 */
class DisciplineSearch extends Discipline
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'directId'], 'integer'],
            [['shortName', 'fullName'], 'safe'],
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
        $query = Discipline::find();

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
        // Не показывать сущности, отмеченные удалёнными
        $query->andFilterWhere(['deleted' => '0']);
        
        // grid filtering conditions
        if($this->directId === '-1'){
            $query->andFilterWhere(['is', 'directId', new \yii\db\Expression('null')]);    
        } else {
            $query->andFilterWhere(['directId' => $this->directId]);    
        }
        $query->andFilterWhere(['id' => $this->id]);

        $query->andFilterWhere(['like', 'shortName', $this->shortName])
            ->andFilterWhere(['like', 'fullName', $this->fullName]);

        return $dataProvider;
    }
}
