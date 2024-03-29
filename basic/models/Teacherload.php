<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teacherload".
 *
 * @property int $id
 * @property int|null $userId
 * @property int|null $groupId
 * @property int|null $disciplineId
 * @property int $total
 * @property int $fSub
 * @property int $sSub
 * @property int $cons
 * @property int $fSubKP
 * @property int $sSubKP
 * @property int $sr
 * @property int $exam
 *
 * @property Discipline $discipline
 * @property Group $group
 * @property User $user
 */
class Teacherload extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacherload';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'groupId', 'disciplineId', 'total', 'fSub', 'sSub', 'cons', 'fSubKP', 'sSubKP', 'sr', 'exam', 'deleted'], 'integer'],
            [['disciplineId'], 'exist', 'skipOnError' => true, 'targetClass' => Discipline::className(), 'targetAttribute' => ['disciplineId' => 'id']],
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
            'userId' => 'Преподаватель',
            'groupId' => 'Группа',
            'disciplineId' => 'Дисциплина/Модуль',
            'total' => 'Общ. зан.',
            'fSub' => '1 п/г ПР',
            'sSub' => '2 п/г ПР',
            'cons' => 'Консульации',
            'fSubKP' => '1 п/г КП',
            'sSubKP' => '2 п/г КП',
            'sr' => 'Сам. работа',
            'exam' => 'Экзамены',
            'deleted' => 'deleted',
        ];
    }

    /**
     * Gets query for [[Discipline]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDiscipline()
    {
        return $this->hasOne(Discipline::className(), ['id' => 'disciplineId']);
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
    
    public function getCountSchedules() {
        $schedules = Schedule::find()->where(['teacherLoadId' => $this->id])->andWhere(['deleted' => '0'])->all();
        return count($schedules);
    }

    protected function deleteDependent() {
        foreach($this->schedules as $item){
            $item->delete();
        }
        // TODO monitoring delete too
    }

    public static function findForGroup($groupId = null) {
        return self::find()->where(['groupId' => $groupId])->all();
    }
    
    public static function findForUser($userId = null) {
        return self::find()->where(['userId' => $userId])->all();
    }
    
    public function getSchedules() {
        return Schedule::findForTeacherload($this->id);
    }
}
