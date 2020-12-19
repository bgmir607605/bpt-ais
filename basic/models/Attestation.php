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
            'date' => 'Дата',
            'type' => 'Тип',
            'semestrNumber' => 'Семестр',
            'name' => 'Название',
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
        $groupName = '';
        foreach($this->teacherloadInAttestations as $item){
            $disciplineName = $item->teacherload->discipline->fullName;
            $teachersNames .= $item->teacherload->user->initials. ' ';
            $groupName = $item->teacherload->group->name;
        }
        
        return $groupName. ' '.$disciplineName. ' ('.$teachersNames.') '.$this->type;
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

    public static function getArrayOfSemestersNumbers() {
        return self::find()->select('semestrNumber')->distinct()->column();
    }
    
    public static function findForTeacherInSemestr($teacherId, $semestrNumber) {
        $teacher = User::findOne($teacherId);
        $teacherloadsIds = ArrayHelper::getColumn($teacher->teacherloads, 'id');
        $attestationsIds = ArrayHelper::getColumn(TeacherloadInAttestation::find()->where(['in', 'teacherloadId', $teacherloadsIds])->all(), 'attestationId');
        return Attestation::find()->where(['in', 'id', $attestationsIds])->andWhere(['semestrNumber' => $semestrNumber])->all();
    }
    
    /**
     * Проверяет: имеет ли доступ к редактированию (оценок) этой аттестации этот пользователь
     * @param type $userId
     */
    public function hasAccessForThisTeacher($userId) {
        $result = false;
        foreach ($this->teacherloadInAttestations as $item){
            if($item->teacherload->userId == $userId){
                $result = true;
                break;
            }
        }
        return $result;
    }
    
    public function saveMarkForStudent($mark, $studentId) {
        $model = AttestationMark::find()->where(['attestationId' => $this->id])->andWhere(['studentId' => $studentId])->one();
        // не понятно что я тут сочинил но выглядит не очень
        if(!empty($model)){
            $model->delete();
        }
        $model = new AttestationMark();
        $model->studentId = $studentId;
        $model->attestationId = $this->id;
        $model->value = $mark;
        $model->save();
    }
    
    public function getValueOfMarkForStudent($studentId) {
        return AttestationMark::find()->where(['attestationId' => $this->id])->andWhere(['studentId' => $studentId])->one()->value ?? '';
    }
}
