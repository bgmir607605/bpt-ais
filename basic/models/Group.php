<?php

namespace app\models;

use Yii;

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
class Group extends \yii\db\ActiveRecord
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

    /**
     * Gets query for [[Direct]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirect()
    {
        return $this->hasOne(Direct::className(), ['id' => 'directId']);
    }

    public function markAsDeleted()
    {
        $this->deleted = '1';
        $this->save();
        // Привязанные нагрузки
        $teacherloads = Teacherload::find()->where(['groupId' => $this->id])->all();
        foreach($teacherloads as $teacherload){
            $teacherload->markAsDeleted();
        }
    }
    public function getStudents()
    {
        $usersIds = StudentInGroup::find()->select('userId')->where(['groupId' => $this->id])->andWhere(['deleted' => '0']);
        return User::find()->where(['in', 'id', $usersIds])->andWhere(['deleted' => '0'])->orderBy('lName')->all();
    }
    public function getTeacherloads()
    {
        return Teacherload::find()->select('id')->where(['groupId' => $this->id])->andWhere(['deleted' => '0'])->asArray()->all();
    }
}
