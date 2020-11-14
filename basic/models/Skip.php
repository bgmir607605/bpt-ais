<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "skip".
 *
 * @property int $id
 * @property int|null $scheduleId
 * @property int|null $studentId
 * @property int $deleted
 *
 * @property Schedule $schedule
 * @property User $student
 */
class Skip extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skip';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scheduleId', 'studentId', 'deleted'], 'integer'],
            [['scheduleId'], 'exist', 'skipOnError' => true, 'targetClass' => Schedule::className(), 'targetAttribute' => ['scheduleId' => 'id']],
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
            'scheduleId' => 'Schedule ID',
            'studentId' => 'Student ID',
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
        return $this->hasOne(User::className(), ['id' => 'studentId']);
    }

    protected function deleteDependent() {
        
    }
    
    public static function findForSchedule($scheduleId = null){
        return self::find()->where(['scheduleId' => $scheduleId])->all();
    }

}
