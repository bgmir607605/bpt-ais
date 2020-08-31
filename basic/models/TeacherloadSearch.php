<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Teacherload;

/**
 * TeacherloadSearch represents the model behind the search form of `app\models\Teacherload`.
 */
class TeacherloadSearch extends Teacherload
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userId', 'groupId', 'disciplineId', 'total', 'fSub', 'sSub', 'cons', 'fSubKP', 'sSubKP', 'sr', 'exam'], 'integer'],
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
        $query = Teacherload::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'userId' => $this->userId,
            'groupId' => $this->groupId,
            'disciplineId' => $this->disciplineId,
            'total' => $this->total,
            'fSub' => $this->fSub,
            'sSub' => $this->sSub,
            'cons' => $this->cons,
            'fSubKP' => $this->fSubKP,
            'sSubKP' => $this->sSubKP,
            'sr' => $this->sr,
            'exam' => $this->exam,
        ]);

        return $dataProvider;
    }
    public function forGroup($groupId = 0, $params)
    {
        $query = Teacherload::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
                'pageParam' => 'active',
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['groupId' => $groupId]);
        // Не показывать сущности, отмеченные удалёнными
        $query->andFilterWhere(['deleted' => '0']);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'userId' => $this->userId,
            // 'groupId' => $this->groupId,
            'disciplineId' => $this->disciplineId,
            'total' => $this->total,
            'fSub' => $this->fSub,
            'sSub' => $this->sSub,
            'cons' => $this->cons,
            'fSubKP' => $this->fSubKP,
            'sSubKP' => $this->sSubKP,
            'sr' => $this->sr,
            'exam' => $this->exam,
        ]);

        return $dataProvider;
    }
}
