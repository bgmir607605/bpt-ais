<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "groupManager".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $groupId
 * @property int $deleted
 *
 * @property Group $group
 * @property User $user
 */
class GroupManager extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groupManager';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'groupId', 'deleted'], 'integer'],
            [['groupId'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['groupId' => 'id']],
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
            'groupId' => 'Group ID',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'groupId']);
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

    protected function deleteDependent() {
        
    }

}
