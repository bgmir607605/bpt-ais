<?php
namespace app\assets;
use yii\web\AssetBundle;

class CalendarAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/calendar';
    public $css = [ 'main.css' ];
    public $js = [];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
