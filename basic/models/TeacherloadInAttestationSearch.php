<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TeacherloadInAttestation;

/**
 * TeacherloadInAttestationSearch represents the model behind the search form of `app\models\TeacherloadInAttestation`.
 */
class TeacherloadInAttestationSearch extends TeacherloadInAttestation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'attestationId', 'teacherloadId', 'deleted'], 'integer'],
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
        $query = TeacherloadInAttestation::find();

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
            'attestationId' => $this->attestationId,
            'teacherloadId' => $this->teacherloadId,
            'deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }
    public function forAttestation($attestation, $params)
    {
        $query = TeacherloadInAttestation::find();

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
        $query->andWhere(['attestationId' => $attestation->id]);
        $query->andFilterWhere([
            'id' => $this->id,
//            'attestationId' => $this->attestationId,
            'teacherloadId' => $this->teacherloadId,
            'deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }
}
