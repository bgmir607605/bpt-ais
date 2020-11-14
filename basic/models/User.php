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
class User extends NotDeletableAR implements \yii\web\IdentityInterface
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
            [['lastDateTime'], 'date', 'format' => 'php:Y-m-d H:i:s'],
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
            'lastDateTime' => 'Посл. действие',
            'deleted' => 'deleted',
        ];
    }

    public function getFio()
    {
        return $this->lName.' '.$this->fName.' '.$this->mName.' ';
    }
    
    public function updateLastDateTime(){
        $this->lastDateTime = date('Y-m-d H:i:s').'';
//        $this->lastDateTime = new \yii\db\Expression('NOW()');
        $this->save();
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

    public function getInitials()
    {
        return sprintf('%s %s. %s.',
            $this->lName,
            mb_substr($this->fName, 0, 1),
            mb_substr($this->mName, 0, 1)
        );
    }

    public function getFullName()
    {
        return implode(' ', [
            $this->lName, $this->fName, $this->mName
        ]);
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

    // needRefactoring
    //TODO дописать
    protected function deleteDependent() {
        
    }
    
    /**
     * Для учителя возвращает список групп, в которых есть его основные нагрузки
     */
    public function getGroupsWhereIamWorking() {
        $teacherloads = Teacherload::find()->select('groupId')->distinct()->where(['userId' => $this->id])->all();
        $groupsIds = \yii\helpers\ArrayHelper::getColumn($teacherloads, 'groupId');
        return Group::find()->where(['in', 'id', $groupsIds])->orderBy('name')->all();
    }
    /**
     * Для учителя возвращает список групп, в которых он заменяет
     */
    public function getGroupsWhereIamReplacer() {
        $replaceTeacherloadsIds = Schedule::find()->select('teacherLoadId')->distinct()->where(['replaceTeacherId' => $this->id])->andWhere(['deleted' => '0']); 
        $replaceTeacherloads = Teacherload::find()->select('groupId')->distinct()->where(['in', 'id', $replaceTeacherloadsIds])->all();
        $groupsIds = \yii\helpers\ArrayHelper::getColumn($replaceTeacherloads, 'groupId');
        return Group::find()->where(['in', 'id', $groupsIds])->orderBy('name')->all();
    }
    
    public function getTeacherloadsInGroup($groupId) {
        $res = [];
        foreach($this->getTeacherloads() as $item){
            if($item->groupId == $groupId){
                $res[] = $item;
            }
        }
        return $item;
    }
    
    public function getTeacherloads() {
        Teacherload::findForUser($this->id);
    }

}
