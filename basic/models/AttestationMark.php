<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attestationMark".
 *
 * @property int $id
 * @property int|null $attestationId
 * @property int|null $studentId
 * @property int|null $value
 * @property int $deleted
 *
 * @property Attestation $attestation
 * @property User $student
 */
class AttestationMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attestationMark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attestationId', 'studentId', 'value', 'deleted'], 'integer'],
            [['attestationId'], 'exist', 'skipOnError' => true, 'targetClass' => Attestation::className(), 'targetAttribute' => ['attestationId' => 'id']],
            [['studentId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['studentId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attestationId' => 'Attestation ID',
            'studentId' => 'Student ID',
            'value' => 'Value',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Attestation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttestation()
    {
        return $this->hasOne(Attestation::className(), ['id' => 'attestationId']);
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(User::className(), ['id' => 'studentId']);
    }
}
