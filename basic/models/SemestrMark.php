<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "semestrMark".
 *
 * @property int $id
 * @property int|null $semestrId
 * @property int|null $studentId
 * @property int|null $value
 * @property int $deleted
 *
 * @property Semestr $semestr
 * @property User $student
 */
class SemestrMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'semestrMark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semestrId', 'studentId', 'value', 'deleted'], 'integer'],
            [['semestrId'], 'exist', 'skipOnError' => true, 'targetClass' => Semestr::className(), 'targetAttribute' => ['semestrId' => 'id']],
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
            'semestrId' => 'Semestr ID',
            'studentId' => 'Student ID',
            'value' => 'Value',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Semestr]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSemestr()
    {
        return $this->hasOne(Semestr::className(), ['id' => 'semestrId']);
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
