<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'admin', 'schedule', 'inspector', 'teacher', 'groupManager', 'applicantManager', 'student', 'deleted'], 'integer'],
            [['fName', 'mName', 'lName', 'username', 'password'], 'safe'],
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
        $query = User::find();

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
            'admin' => $this->admin,
            'schedule' => $this->schedule,
            'inspector' => $this->inspector,
            'teacher' => $this->teacher,
            'groupManager' => $this->groupManager,
            'applicantManager' => $this->applicantManager,
            'student' => $this->student,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'fName', $this->fName])
            ->andFilterWhere(['like', 'mName', $this->mName])
            ->andFilterWhere(['like', 'lName', $this->lName])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
    
    public function searchForGroup($group, $params)
    {
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder'=>[
                     'lName'=>SORT_ASC,
                ]
        ]]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
 

        $studentsIds = ArrayHelper::getColumn($group->students, 'id');
        $query->andWhere(['in', 'id', $studentsIds]);
        $query->andFilterWhere(['like', 'fName', $this->fName])
            ->andFilterWhere(['like', 'mName', $this->mName])
            ->andFilterWhere(['like', 'lName', $this->lName])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password]);

        return $dataProvider;
    }
}
