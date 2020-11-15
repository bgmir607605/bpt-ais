<?php
namespace app\models;

abstract class NotDeletableAR extends \yii\db\ActiveRecord {
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
    
    /**
     * Устанавливает deleted = 1
     * Фактически запись не удаляется
     */
    public function delete() {
        // Сначала удалить зависимые данные,
        // иначе они потом не пройдут валидацию
        $this->deleteDependent();
        $this->deleted = '1';        
        $this->save();
//        if(!$this->save())
//    print_r($this->getErrors());
    }
    /**
     * Ппомечает удалёнными зависящие данные
     * Объявляется и рализуется в наследующих классах
     */
    abstract protected function deleteDependent();
    
    /**
     * Поиск только по записям, у которых deleted =1
     */
    public static function findDeleted(){
        return parent::find()->onCondition(['and' ,['deleted' => '1']]);
    }
    /**
     * Поиск только по записям по id, у которых deleted =1
     */
    public static function findOneDeleted($id = null){
        return self::findDeleted()->where(['id' => $id])->one();
    }
    /**
     * Все записи, у которых deleted = 1
     * @return type
     */
    public static function findAllDeleted(){
        return self::findDeleted()->all();
    }
    
    // TODO confirmDelete()
    
    
}

