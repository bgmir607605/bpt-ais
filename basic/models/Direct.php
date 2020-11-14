<?php

namespace app\models;

/**
 * This is the model class for table "direct".
 *
 * @property int $id
 * @property string|null $code
 * @property string|null $name
 * @property string $type
 * @property int|null $forApplicant
 */
class Direct extends NotDeletableAR
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
    protected function deleteDependent()
    {
        /**
         * Зависят таблицы
         * group
         * discipline
         */
        foreach($this->groups as $item){
            $item->delete();
        }
        foreach($this->disciplines as $item){
            $item->delete();
        }
    }
    
    public function getGroups() {
        return Group::findForDirect($this->id);
    }
    public function getDisciplines() {
        return Discipline::findForDirect($this->id);
    }
}
