<?php
namespace app\components\toExcel;

use yii\base\Component;
use app\models\Group;
use app\components\toExcel\Classes\PHPExcel\IOFactory;

class ToExcel extends Component{
    protected $date;
    protected $pathFile;
    protected $tempFile;
    protected $schedule;
    protected $phpexcel;

    public function getFile($data){
        $this->schedule = $data["container"];
        $this->date = $data["date"];
        $this->pathFile = \Yii::getAlias('@app') .'/components/toExcel/example.xlsx';
        $this->tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $this->phpexcel = IOFactory::load($this->pathFile); // Создаём объект PHPExcel
        $this->makePageForAll();
        $this->makePageForSPO();
        $this->makePageForNPO();

        $objWriter = IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save($this->tempFile);
        
        if (file_exists($this->tempFile)) { 
            $filename = basename($this->tempFile); 
            $size = filesize($this->tempFile); 
            header("Content-Disposition: attachment; filename=$filename"); 
            header("Content-Length: $size"); 
            header("Charset: UTF-8"); 
            header("Content-Type: application/unknown"); 
            if (@readfile($this->tempFile)) { 
                unlink($this->tempFile); 
            } 
        }

    }

    protected function makePageForAll(){
        $page = $this->phpexcel->setActiveSheetIndex(0); // Делаем активной 1 страницу и получаем её
        $page->setCellValue("A2", $this->formatDate());
        $n = 4;
        foreach($this->schedule as $course){
            foreach($course["groups"] as $group){
                // var_dump($group);
                    $page->setCellValue("A$n", $group['name']);
                    foreach($group['schedule'] as $item){
                        $address = $this->getAddressForNumber($item['number'], $n);
                        $curVal = $page->getCell($address)->getValue();
                        $page->setCellValue($address, $curVal.$item["discipline"].' '.$item["teacher"]. "\n");
                    }
                    $n++;
            }
        }
    }


    protected function makePageForSPO(){
        $page = $this->phpexcel->setActiveSheetIndex(1); // Делаем активной 2 страницу и получаем её
        $page->setCellValue("A2", $this->formatDate());
        $n = 4;
        foreach($this->schedule as $course){
            foreach($course['groups'] as $group){
                $groupModel = Group::findOne($group['id']);
                if($groupModel->direct->type == 'СПО'){
                    $page->setCellValue("A$n", $group['name']);
                    foreach($group['schedule'] as $item){
                        $address = $this->getAddressForNumber($item['number'], $n);
                        $curVal = $page->getCell($address)->getValue();
                        $page->setCellValue($address, $curVal.$item["discipline"].' '.$item["teacher"]. "\n");
                    }
                    $n++;
                }
            }
        }
    }

    protected function makePageForNPO(){
        $page = $this->phpexcel->setActiveSheetIndex(2); // Делаем активной 3 страницу и получаем её
        $page->setCellValue("A2", $this->formatDate());
        $n = 4;
        foreach($this->schedule as $course){
            foreach($course['groups'] as $group){
                $groupModel = Group::findOne($group['id']);
                if($groupModel->direct->type == 'НПО'){
                    $page->setCellValue("A$n", $group['name']);
                    foreach($group['schedule'] as $item){
                        $address = $this->getAddressForNumber($item['number'], $n);
                        $curVal = $page->getCell($address)->getValue();
                        $page->setCellValue($address, $curVal.$item["discipline"].' '.$item["teacher"]. "\n");
                    }
                    $n++;
                }
            }
        }
    }

    protected function getAddressForNumber(string $number, $n){
        $address = '';
        switch ($number){
            case 1:
            $address = "C$n";
            break;
            case 2:
            $address = "D$n";
            break;
            case 3:
            $address = "F$n";
            break;
            case 4:
            $address = "G$n";
            break;
            case 5:
            $address = "H$n";
            break;
            default:
            $address = "A$n";
        }
        return $address;
    }

    protected function formatDate(){
        $dayName;
        $time = strtotime($this->date);
        $numberDayInWeek = date('w', $time);
        switch($numberDayInWeek){
            case 1:
                $dayName = 'ПОНЕДЕЛЬНИК';
                break;
            case 2:
                $dayName = 'ВТОРНИК';
                break;
            case 3:
                $dayName = 'СРЕДА';
                break;
            case 4:
                $dayName = 'ЧЕТВЕРГ';
                break;
            case 5:
                $dayName = 'ПЯТНИЦА';
                break;
            case 6:
                $dayName = 'СУББОТА';
                break;
            case 7:
                $dayName = 'ВОСКРЕСЕНИЕ';
                break;
            default:
                $dayName = "";
        }

        return $dayName.' '.$this->date;
    }

}