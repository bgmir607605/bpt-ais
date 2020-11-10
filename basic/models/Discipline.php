<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "discipline".
 *
 * @property int $id
 * @property string|null $shortName
 * @property string|null $fullName
 * @property int|null $directId
 * @property int $deleted
 *
 * @property Direct $direct
 * @property Teacherload[] $teacherloads
 */
class Discipline extends NotDeletableAR
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'discipline';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['directId', 'deleted'], 'integer'],
            [['shortName', 'fullName'], 'string', 'max' => 255],
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
            'shortName' => 'Краткое название',
            'fullName' => 'Полное название',
            'directId' => 'Направление',
            'deleted' => 'Deleted',
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

    /**
     * Gets query for [[Teacherloads]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTeacherloads()
    {
        return $this->hasMany(Teacherload::className(), ['disciplineId' => 'id']);
    }

    public function markAsDeleted()
    {
        $this->deleted = '1';
        $this->save();
        // Привязанные нагрузки
        $teacherloads = Teacherload::find()->where(['disciplineId' => $this->id])->all();
        foreach($teacherloads as $teacherload){
            $teacherload->markAsDeleted();
        }
    }

    protected function deleteDependent() {
        
    }

}
