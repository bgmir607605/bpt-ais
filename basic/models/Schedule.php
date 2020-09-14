<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schedule".
 *
 * @property int $id
 * @property string|null $date
 * @property int|null $number
 * @property string $type
 * @property int|null $teacherLoadId
 * @property int $cons
 * @property int $forTeach
 * @property int $hours
 * @property int $kp
 * @property int $sr
 * @property int|null $replaceTeacherId
 *
 * @property User $replaceTeacher
 * @property Teacherload $teacherLoad
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['number', 'teacherLoadId', 'cons', 'forTeach', 'hours', 'kp', 'sr', 'replaceTeacherId', 'deleted', 'cabinetId'], 'integer'],
            [['type'], 'string'],
            [['replaceTeacherId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['replaceTeacherId' => 'id']],
            [['teacherLoadId'], 'exist', 'skipOnError' => true, 'targetClass' => Teacherload::className(), 'targetAttribute' => ['teacherLoadId' => 'id']],
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
            'number' => 'Number',
            'type' => 'Type',
            'teacherLoadId' => 'Teacher Load ID',
            'cons' => 'Cons',
            'forTeach' => 'For Teach',
            'hours' => 'Hours',
            'kp' => 'Kp',
            'sr' => 'Sr',
            'replaceTeacherId' => 'Replace Teacher ID',
            'deleted' => 'deleted',
            'cabinetId' => 'Кабинет',
        ];
    }

    /**
     * Gets query for [[ReplaceTeacher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReplaceTeacher()
    {
        return $this->hasOne(User::className(), ['id' => 'replaceTeacherId']);
    }

    /**
     * Gets query for [[TeacherLoad]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherLoad()
    {
        return $this->hasOne(Teacherload::className(), ['id' => 'teacherLoadId']);
    }

    public function markAsDeleted()
    {
        $this->deleted = '1';
        $this->save();
    }
}
