<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "semestr".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $type
 * @property int|null $semestrNumber
 * @property int $deleted
 *
 * @property SemestrMark[] $semestrMarks
 * @property TeacherloadInSemestr[] $teacherloadInSemestrs
 */
class Semestr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'semestr';
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
     * Gets query for [[SemestrMarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSemestrMarks()
    {
        return $this->hasMany(SemestrMark::className(), ['semestrId' => 'id']);
    }

    /**
     * Gets query for [[TeacherloadInSemestrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherloadInSemestrs()
    {
        return $this->hasMany(TeacherloadInSemestr::className(), ['semestrId' => 'id']);
    }
}
