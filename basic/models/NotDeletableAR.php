<?php
namespace app\models;

// needRefactoring Должен быть абстрактным?
class NotDeletableAR extends \yii\db\ActiveRecord {
    /**
     * Очищенные от помеченных на удаление
     * @return type
     */
    public static function find(){
        return parent::find()->onCondition(['and' ,['deleted' => '0']]);
    }
    /**
     * Очищенные от помеченных на удаление
     * @return type
     */
    public static function findOne($id = null){
        return self::find()->where(['id' => $id])->one();
    }
    /**
     * Очищенные от помеченных на удаление
     * @return type
     */
    public static function findAll($condition = ''){
        return self::find()->all();
    }
    
    public function delete() {
        $this->deleted = '1';
        $this->save();
        $this->deleteDependent();
    }
    
    /**
     * Ппомечает удалёнными зависящие данные
     * Объявляется и рализуется в наследующих классах
     */
//    public function deleteDependent()
//    {
//       
//    }
    
}

