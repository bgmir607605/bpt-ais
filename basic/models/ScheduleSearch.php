<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Schedule;

/**
 * ScheduleSearch represents the model behind the search form of `app\models\Schedule`.
 */
class ScheduleSearch extends Schedule
{
    /**
     * {@inheritdoc}
     */
    public $dateFrom;
    public $dateTo;
    
    public function rules()
    {
        return [
            [['id', 'number', 'teacherLoadId', 'hours', 'replaceTeacherId'], 'integer'],
            [['date', 'type', 'dateFrom', 'dateTo', 'sr', 'cons', 'forTeach', 'kp'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        $parentsLabels = parent::attributeLabels();
        $parentsLabels['dateFrom'] = 'С';
        $parentsLabels['dateTo'] = 'По';
        return $parentsLabels;
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
        $query = Schedule::find();

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
            'date' => $this->date,
            'number' => $this->number,
            'teacherLoadId' => $this->teacherLoadId,
            'cons' => $this->cons,
            'forTeach' => $this->forTeach,
            'hours' => $this->hours,
            'kp' => $this->kp,
            'sr' => $this->sr,
            'replaceTeacherId' => $this->replaceTeacherId,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
    
    public function searchForTeacherload($teacherloadId, $params)
    {
        $query = Schedule::find();

        // add conditions that should always apply here
        // Не показывать сущности, отмеченные удалёнными
        $query->andFilterWhere(['deleted' => '0']);
        $type = array();
                if(!empty($params["ScheduleSearch"]["type"])){
                    foreach($params["ScheduleSearch"]["type"] as $k => $v){
                        if($v == '0'){
                            $type[$k] = '';
                        } else {
                            $type[$k] = $v;
                        }
                    }
                }

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
            'number' => $this->number,
            'teacherLoadId' => $teacherloadId,
            'cons' => $this->cons,
            'forTeach' => $this->forTeach,
            'hours' => $this->hours,
//            'type' => $type,
            'kp' => $this->kp,
            'sr' => $this->sr,
            'replaceTeacherId' => $this->replaceTeacherId,
        ]);

        $query->andFilterWhere(['in', 'type', $type]);
        $query->andFilterWhere(['>=', 'date', $this->dateFrom]);
        $query->andFilterWhere(['<=', 'date', $this->dateTo]);
        return $dataProvider;
    }
}
