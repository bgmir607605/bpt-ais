<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\FileHelper;
use app\models\Log;
use Yii;

class ToolDB extends Component{


// Создание резервной копии всей БД
public function fullBackup() {
    $path = '@app/backups/';
    $fileName = 'dump_' . date('d-m-Y_H-i-s') . '.sql';
    // $log = new Log();
    // $log->message = 'Авторизация администратора. Создание резервной копии '. $fileName;
    // $log->save();
    
    $path = FileHelper::normalizePath(Yii::getAlias($path));
    $filePath = '';
    if (file_exists($path)) {
        if (is_dir($path)) {
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
            $db = Yii::$app->getDb();
            $db->password = str_replace("(","\(",$db->password);
            exec('echo "-- Version: '.Yii::$app->version.' \n" > ' . $filePath);
            exec('mysqldump --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' --skip-add-locks >> ' . $filePath);
            return $filePath;
        }
    }
    return $filePath;
}

// Создание резервной копии всей БД
public function tableBackup($tableName = '') {
    $path = '@app/backups/';
    $fileName = 'dump_'.$tableName.'_' . date('d-m-Y_H-i-s') . '.sql';
    // $log = new Log();
    // $log->message = $fileName;
    // $log->save();
    
    $path = FileHelper::normalizePath(Yii::getAlias($path));
    if (file_exists($path)) {
        if (is_dir($path)) {
            if (!is_writable($path)) {
                // Yii::$app->session->setFlash('error', 'Дирректория не доступна для записи.');
                // return Yii::$app->response->redirect(['db/index']);
            } 
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
            $db = Yii::$app->getDb();
            if (!$db) {
                // Yii::$app->session->setFlash('error', 'Нет подключения к базе данных.');
                // return Yii::$app->response->redirect(['db/index']);
            } 
            //Экранируем скобку которая есть в пароле
            $db->password = str_replace("(","\(",$db->password);
            exec('mysqldump --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' '.$tableName.' --skip-add-locks > ' . $filePath);
            // Yii::$app->session->setFlash('success', 'Экспорт успешно завершен. Файл "'.$fileName.'" в папке ' . $path);
        } else {
            // Yii::$app->session->setFlash('error', 'Путь должен быть папкой.');
        }
    } else {
        // Yii::$app->session->setFlash('error', 'Указанный путь не существует.');
    }
    // return Yii::$app->response->redirect(['db/index']);
}





//Возвращает название хоста (например localhost)
private function getDsnAttribute($name, $dsn) {
    if (preg_match('/' . $name . '=([^;]*)/', $dsn, $match)) {
        return $match[1];
    } else {
        return null;
    }
}

public function getFile()
{
    $file = $this->fullBackup();
    if (file_exists($file)) { 
        $filename = basename($file); 
        $size = filesize($file); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Length: $size"); 
        header("Charset: UTF-8"); 
        header("Content-Type: application/unknown"); 
        if (@readfile($file)) { 
            unlink($file); 
        } 
    }
}


}