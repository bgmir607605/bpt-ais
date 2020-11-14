<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\FileHelper;
use Yii;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller
{
    protected $time_start;
    protected $time_end;
    
    public function beforeAction($action)
    {
        $this->time_start = microtime(true);
     return parent::beforeAction($action);
    }
    public function afterAction($action, $result)
    {
        $this->time_end = microtime(true);
        $time = $this->time_end - $this->time_start;
        echo "\033[32m Время выполнения: $time секунд \033[0m   \n";
        
     return parent::beforeAction($action, $result);
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionUnit($modelTest = '*', $reloadDump = true)
    {
        // Перечитать конфигурацию codeception
        $path = FileHelper::normalizePath(Yii::getAlias('@app')).'/';
        echo shell_exec('cd '.$path.'; ./vendor/bin/codecept build');
        // Перезагрузить дамп
        if($reloadDump){
            $this->actionReloadDump();
        }
        if($modelTest == '*'){
            // TODO Переписать для перебора тестов с возможностью предварительной перезагрузки дампа
            echo shell_exec('cd '.$path.'; ./vendor/bin/codecept run unit');
        } else {
            echo shell_exec('cd '.$path.'; ./vendor/bin/codecept run unit models/'.$modelTest);
        }
        
    }
    
    public function actionReloadDump($fileName = 'dump.sql') {
        // Считать дамп
        $path = FileHelper::normalizePath(Yii::getAlias('@app/tests/_data/'));
        $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
        $backup = file_get_contents($filePath);
        echo "\033[32m Dump finded \033[0m  \n";
        // Очистить ДБ
        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 0")->execute();
        foreach (\Yii::$app->db->schema->tableNames as $tableName) {
            Yii::$app->getDb()->createCommand()->dropTable($tableName)->execute();
        }
        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 1")->execute();
        echo "\033[32m DB cleared \033[0m   \n";
        // Импортировать нужный дамп
        Yii::$app->getDb()->createCommand($backup)->execute();
        echo "\033[32m Dump imported \033[0m   \n";
        return ExitCode::OK;
    }
    
    public function actionHello($param, $reload) {
        var_dump($param);
        var_dump($reload);
    }
}
