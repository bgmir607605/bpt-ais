<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacherloadInSemestr".
 *
 * @property int $id
 * @property int|null $semestrId
 * @property int|null $teacherloadId
 * @property int $deleted
 *
 * @property Semestr $semestr
 * @property Teacherload $teacherload
 */
class TeacherloadInSemestr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacherloadInSemestr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['semestrId', 'teacherloadId', 'deleted'], 'integer'],
            [['semestrId'], 'exist', 'skipOnError' => true, 'targetClass' => Semestr::className(), 'targetAttribute' => ['semestrId' => 'id']],
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
            'semestrId' => 'Semestr ID',
            'teacherloadId' => 'Teacherload ID',
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
     * Gets query for [[Teacherload]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherload()
    {
        return $this->hasOne(Teacherload::className(), ['id' => 'teacherloadId']);
    }
}
