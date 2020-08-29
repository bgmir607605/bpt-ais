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
            'fName' => 'Имя',
            'mName' => 'Отчество',
            'lName' => 'Фамилия',
            'username' => 'Логин',
            'password' => 'Пароль',
            'admin' => 'Доступ к админке',
            'schedule' => 'Доступ к составлению расписания',
            'inspector' => 'Доступ к мониторингу',
            'teacher' => 'Преподаватель',
            'groupManager' => 'Кл. руководитель',
            'applicantManager' => 'Приёмная комиссия',
            'student' => 'Студент',
            'deleted' => 'deleted',
        ];
    }

    public function getFio()
    {
        return $this->lName.' '.$this->fName.' '.$this->mName.' ';
    }

    public static function teachers()
    {
        return self::find()->where(['teacher' => '1'])->andWhere(['deleted' => '0'])->orderBy('lName')->all();
    }

    public static function teachersForDropdown()
    {
        $res = array();
        foreach(self::teachers() as $teacher){
            $res[$teacher->id] = $teacher->fio;
        }
        return $res;
    }

    public function markAsDeleted()
    {
        $this->deleted = '1';
        $this->save();
        // Привязанные нагрузки
        $teacherloads = Teacherload::find()->where(['userId' => $this->id])->all();
        foreach($teacherloads as $teacherload){
            $teacherload->markAsDeleted();
        }
        // Привязанные занятия
        $schedules = Schedule::find()->where(['replaceTeacherId' => $this->id])->all();
        foreach($schedules as $schedule){
            $schedule->markAsDeleted();
        }
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
