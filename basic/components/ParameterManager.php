<?php
namespace app\components;

use yii\base\Component;
use app\models\Parameter;
use Yii;

class ParameterManager extends Component{

    /**
     * Возвращает время последнего изменения набора нагрузок
     */
    public function getVersionTLS()
    {
        $param = Parameter::find()->where(['k' => 'versionTLS'])->one();
        if(empty($param)){
            $param = $this->initVersionTLS();
        }
        return $param->v;
    }

    public function updateVersionTLS()
    {
        $param = Parameter::find()->where(['k' => 'versionTLS'])->one();
        $param->v = (string) time();
        $param->save();
    }
    
    protected function initVersionTLS(){
        $param = new Parameter();
        $param->k = 'versionTLS';
        $param->v = (string) time();
        $param->save();
        return $param;
    }
}