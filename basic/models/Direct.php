<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "direct".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string $type
 * @property int|null $forApplicant
 */
class Direct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'direct';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['forApplicant', 'deleted'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
            'name' => 'Название',
            'type' => 'СПО / НПО',
            'forApplicant' => 'Набор',
            'deleted' => 'deleted',
        ];
    }
    public function markAsDeleted()
    {
        $this->deleted = '1';
        $this->save();
        // Привязанные группы
        $groups = Group::find()->where(['directId' => $this->id])->all();
        foreach($groups as $group){
            $group->markAsDeleted();
        }
        // Привязанные дисциплины
        $disciplines = Discipline::find()->where(['directId' => $this->id])->all();
        foreach($disciplines as $discipline){
            $discipline->markAsDeleted();
        }
    }
}
