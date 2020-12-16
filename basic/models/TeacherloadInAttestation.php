<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacherloadInAttestation".
 *
 * @property int $id
 * @property int|null $attestationId
 * @property int|null $teacherloadId
 * @property int $deleted
 *
 * @property Attestation $attestation
 * @property Teacherload $teacherload
 */
class TeacherloadInAttestation extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacherloadInAttestation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['attestationId', 'teacherloadId', 'deleted'], 'integer'],
            [['attestationId'], 'exist', 'skipOnError' => true, 'targetClass' => Attestation::className(), 'targetAttribute' => ['attestationId' => 'id']],
            [['teacherloadId'], 'exist', 'skipOnError' => true, 'targetClass' => Teacherload::className(), 'targetAttribute' => ['teacherloadId' => 'id']],
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
            'teacherloadId' => 'Teacherload ID',
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
     * Gets query for [[Teacherload]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherload()
    {
        return $this->hasOne(Teacherload::className(), ['id' => 'teacherloadId']);
    }

    protected function deleteDependent() {
        
    }
    
    public function getName() {
        return $this->teacherload->discipline->fullName.' ('. $this->teacherload->user->initials.')';
    }

}
