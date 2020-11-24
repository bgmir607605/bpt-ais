<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\FileHelper;
use Yii;

class ToolDB extends Component{


// Создание резервной копии всей БД
public function fullBackup() {
    $path = '@app/backups/';
    $fileName = 'dump_' . date('d-m-Y_H-i-s') . '.sql';
    
    $path = FileHelper::normalizePath(Yii::getAlias($path));
    $filePath = '';
    if (file_exists($path)) {
        if (is_dir($path)) {
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
            $db = Yii::$app->getDb();
            $db->password = str_replace("(","\(",$db->password);
            exec('echo "-- Version: '.Yii::$app->version.' \n" > ' . $filePath);
            exec('mysqldump --opt --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' --skip-add-locks >> ' . $filePath);
            return $filePath;
        }
    }
    return $filePath;
}
// Создание резервной копии всей БД
public function getContentOfFullBackup() {
    $db = Yii::$app->getDb();
    $db->password = str_replace("(","\(",$db->password);
    $dump = shell_exec('mysqldump --opt  --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' --skip-add-locks');
    return $dump;
}

// Создание резервной копии всей БД
public function tableBackup($tableName = '') {
    $path = '@app/backups/';
    $fileName = 'dump_'.$tableName.'_' . date('d-m-Y_H-i-s') . '.sql';    
    $path = FileHelper::normalizePath(Yii::getAlias($path));
    if (file_exists($path)) {
        if (is_dir($path)) {
            $filePath = $path . DIRECTORY_SEPARATOR . $fileName;
            $db = Yii::$app->getDb();
            //Экранируем скобку которая есть в пароле
            $db->password = str_replace("(","\(",$db->password);
            exec('mysqldump --opt --host=' . $this->getDsnAttribute('host', $db->dsn) . ' --user=' . $db->username . ' --password=' . $db->password . ' ' . $this->getDsnAttribute('dbname', $db->dsn) . ' '.$tableName.' --skip-add-locks > ' . $filePath);
        }
    }
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