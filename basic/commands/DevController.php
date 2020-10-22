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
class DevController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionDbUpdate()
    {
        // Скачать новый бэкап
        $backup = file_get_contents('http://ais.bpt-coders.ru?r=api/get-backup');
        echo "\033[32m New backup downloaded \033[0m  \n";
        // Очистить ДБ
        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 0")->execute();
        foreach (\Yii::$app->db->schema->tableNames as $tableName) {
            Yii::$app->getDb()->createCommand()->dropTable($tableName)->execute();
        }
        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 1")->execute();
        echo "\033[32m DB cleared \033[0m   \n";
        // Импортировать скачанный бэкап
        Yii::$app->getDb()->createCommand($backup)->execute();
        echo "\033[32m DB imported \033[0m   \n";
        return ExitCode::OK;
    }
}
