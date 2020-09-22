<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string|null $userId
 * @property string|null $action
 * @property string|null $ip
 * @property string|null $optional
 * @property string|null $datetime
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['userId', 'action', 'ip', 'optional'], 'string', 'max' => 255],
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
            'action' => 'Action',
            'ip' => 'Ip',
            'optional' => 'Optional',
            'datetime' => 'Datetime',
        ];
    }
}
