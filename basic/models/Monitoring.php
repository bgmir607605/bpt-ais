<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "monitoring".
 *
 * @property int $id
 * @property string|null $date
 * @property string|null $name
 *
 * @property MonitoringMark[] $monitoringMarks
 */
class Monitoring extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'monitoring';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[MonitoringMarks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonitoringMarks()
    {
        return $this->hasMany(MonitoringMark::className(), ['monitoringId' => 'id']);
    }
}
