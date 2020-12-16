<?php

namespace app\models;

use Yii;

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

}
