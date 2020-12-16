<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attestation".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $type
 * @property int|null $semestrNumber
 * @property int $deleted
 *
 * @property AttestationMark[] $attestationMarks
 * @property TeacherloadInAttestation[] $teacherloadInAttestations
 */
class Attestation extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attestation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['type'], 'string'],
            [['semestrNumber', 'deleted'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'type' => 'Type',
            'semestrNumber' => 'Semestr Number',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[AttestationMarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttestationMarks()
    {
        return $this->hasMany(AttestationMark::className(), ['attestationId' => 'id']);
    }

    /**
     * Gets query for [[TeacherloadInAttestations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherloadInAttestations()
    {
        return $this->hasMany(TeacherloadInAttestation::className(), ['attestationId' => 'id']);
    }

    protected function deleteDependent() {
        foreach($this->attestationMarks as $item){
            $item->delete();
        }
        foreach($this->teacherloadInAttestations as $item){
            $item->delete();
        }
    }
    
    public function getName() {
        $disciplineName = '';
        $teachersNames = '';
        foreach($this->teacherloadInAttestations as $item){
            $disciplineName = $item->teacherload->discipline->fullName;
            $teachersNames .= $item->teacherload->user->initials. ' ';
        }
        
        return $disciplineName. ' ('.$teachersNames.')';
    }
    
    public function getGroup() {
        return $this->teacherloadInAttestations[0]->teacherload->group;
    }
    
    public static function findForGroup($groupId) {
        $group = Group::findOne($groupId);
        $teacherloadsIds = ArrayHelper::getColumn($group->teacherloads, 'id');
        $attestationsIds = ArrayHelper::getColumn(TeacherloadInAttestation::find()->where(['in', 'teacherloadId', $teacherloadsIds])->all(), 'attestationId');
        return Attestation::find()->where(['in', 'id', $attestationsIds])->all();
    }
    
    
    public function addTeacherload($teacherloadId) {
        $teacherload = Teacherload::findOne($teacherloadId);
        if(!empty($teacherload)){
            $model = new TeacherloadInAttestation();
            $model->teacherloadId = $teacherload->id;
            $model->attestationId = $this->id;
            if($model->save()){
                return true;
            }
        }
        return false;
        
    }

}
