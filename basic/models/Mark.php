<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mark".
 *
 * @property int $id
 * @property int|null $scheduleId
 * @property int|null $studentId
 * @property int|null $value
 * @property int $deleted
 *
 * @property Schedule $schedule
 * @property StudentInGroup $student
 */
class Mark extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scheduleId', 'studentId', 'value', 'deleted'], 'integer'],
            [['scheduleId'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::className(), 'targetAttribute' => ['scheduleId' => 'id']],
            [['studentId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentInGroup::className(), 'targetAttribute' => ['studentId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'scheduleId' => 'Schedule ID',
            'studentId' => 'Student ID',
            'value' => 'Value',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Schedule]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSchedule()
    {
        return $this->hasOne(Schedule::className(), ['id' => 'scheduleId']);
    }

    /**
     * Gets query for [[Student]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudent()
    {
        return $this->hasOne(StudentInGroup::className(), ['id' => 'studentId']);
    }
    
    protected function deleteDependent() {
//        От оценок ничего не зависит, никого больше не удаляем
    }


    public static function findForSchedule($scheduleId = null){
        return self::find()->where(['scheduleId' => $scheduleId])->all();
    }
}
