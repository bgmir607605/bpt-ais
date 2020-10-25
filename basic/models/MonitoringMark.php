<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "monitoringMark".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $teacherLoadId
 * @property int|null $monitoringId
 * @property string|null $mark
 *
 * @property Monitoring $monitoring
 * @property Teacherload $teacherLoad
 * @property User $user
 */
class MonitoringMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monitoringMark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'teacherLoadId', 'monitoringId'], 'integer'],
            [['mark'], 'string', 'max' => 255],
            [['monitoringId'], 'exist', 'skipOnError' => true, 'targetClass' => Monitoring::className(), 'targetAttribute' => ['monitoringId' => 'id']],
            [['teacherLoadId'], 'exist', 'skipOnError' => true, 'targetClass' => Teacherload::className(), 'targetAttribute' => ['teacherLoadId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'teacherLoadId' => 'Teacher Load ID',
            'monitoringId' => 'Monitoring ID',
            'mark' => 'Mark',
        ];
    }

    /**
     * Gets query for [[Monitoring]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonitoring()
    {
        return $this->hasOne(Monitoring::className(), ['id' => 'monitoringId']);
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}
