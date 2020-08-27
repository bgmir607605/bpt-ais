<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $fName
 * @property string|null $mName
 * @property string|null $lName
 * @property string|null $username
 * @property string|null $password
 * @property int $admin
 * @property int $schedule
 * @property int $inspector
 * @property int $teacher
 * @property int $groupManager
 * @property int $applicantManager
 * @property int $student
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['admin', 'schedule', 'inspector', 'teacher', 'groupManager', 'applicantManager', 'student', 'deleted'], 'integer'],
            [['fName', 'mName', 'lName', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fName' => 'F Name',
            'mName' => 'M Name',
            'lName' => 'L Name',
            'username' => 'Username',
            'password' => 'Password',
            'admin' => 'Admin',
            'schedule' => 'Schedule',
            'inspector' => 'Inspector',
            'teacher' => 'Teacher',
            'groupManager' => 'Group Manager',
            'applicantManager' => 'Applicant Manager',
            'student' => 'Student',
            'deleted' => 'deleted',
        ];
    }



    ########################
    // Реализация интерфейса
    ########################
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        return isset($user) ? new static($user) : null;
    }

    public static function findByUsername($username)
    {
        $user = self::find()->where(['username' => $username])->one();
        return isset($user) ? new static($user) : null;
    }
 
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // 
    }

    public function getAuthKey()
    {
        // 
    }

    public function validateAuthKey($authKey)
    {
        // 
    }

}
