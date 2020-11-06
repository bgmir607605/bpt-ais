<?php

namespace app\models;

/**
 * This is the model class for table "group".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $directId
 * @property string $course
 *
 * @property Direct $direct
 */
class Group extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['directId', 'deleted'], 'integer'],
            [['course'], 'required'],
            [['course'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['directId'], 'exist', 'skipOnError' => true, 'targetClass' => Direct::className(), 'targetAttribute' => ['directId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'directId' => 'Направление',
            'course' => 'Курс',
            'deleted' => 'deleted',
        ];
    }
    
    
    protected function deleteDependent()
    {
         /**
          * Привязаны таблицы
          * groupManager
          * studentInGroup
          * teacherload
          */
        foreach($this->managers as $item){
             $item->delete();
         }
        foreach(StudentInGroup::findForGroup($this->id) as $item){
            $item->delete();
        }
        foreach($this->teacherloads as $item){
            $item->delete();
        }
    }

    public function getDirect()
    {
        return $this->hasOne(Direct::className(), ['id' => 'directId']);
    }

    public function getStudentsAsUsers()
    {
        $usersIds = StudentInGroup::find()->select('userId')->where(['groupId' => $this->id])->andWhere(['deleted' => '0']);
        return User::find()->where(['in', 'id', $usersIds])->andWhere(['deleted' => '0'])->orderBy('lName')->all();
    }
    
    public function getTeacherloads()
    {
        return Teacherload::findForGroup($this->id);
    }
    
    function getManagers() {
        return GroupManager::findForGroup($this->id);
    }
}
