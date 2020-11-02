<?php
namespace app\components\toExcel;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use app\models\Group;
use app\models\Teacherload;
use app\models\MonitoringMark;
use app\components\toExcel\Classes\PHPExcel\IOFactory;

class ToExcel extends Component{
    protected $date;
    protected $pathFile;
    protected $tempFile;
    protected $schedule;
    protected $phpexcel;
    
    public function getMonitoring(){
        $this->pathFile = \Yii::getAlias('@app') .'/components/toExcel/example.xlsx';
        $this->tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $this->phpexcel = IOFactory::load($this->pathFile); // Создаём объект PHPExcel
        $pageIndex = 0;
        $groups = Group::find()->where(['deleted' => '0'])->all();
        foreach ($groups as $group){
            
            $this->phpexcel->createSheet($pageIndex, 'sdf'); //  
            $page = $this->phpexcel->setActiveSheetIndex($pageIndex); // Делаем активной 
            $teacherloads = Teacherload::find()->where(['groupId' => $group->id])->andWhere(['deleted' => '0'])->all();
            $monitoringMarks = MonitoringMark::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($teacherloads, 'id')])->all();
            $columnindex = 2;
            $lineIndex = 3;
            foreach($teacherloads as $teacherload){
                $cellValue =  $teacherload->discipline->shortName.'('.$teacherload->user->lName.')';
                $page->setCellValue($this->getAddressForNumber($columnindex, $lineIndex), $cellValue);
                $columnindex++;
            }
            $lineIndex++;
            foreach($group->students as $student){
                $columnindex = 1;
                $cellValue = $group->name;
                $page->setCellValue('A1', $cellValue);
                $cellValue = $student->lName.' '.$student->fName;
                $page->setCellValue($this->getAddressForNumber($columnindex, $lineIndex), $cellValue);
                $columnindex++;
                foreach($teacherloads as $teacherload){
                    $cellValue = '';
//                    $cellValue = $teacherload->discipline->shortName;
                    foreach($monitoringMarks as $mark){
                        if($mark->userId == $student->id && $mark->teacherLoadId == $teacherload->id){
                            $cellValue .= $mark->mark;
                        break;
                        }
                    }
                    $page->setCellValue($this->getAddressForNumber($columnindex, $lineIndex), $cellValue);
                    $columnindex++;
                }
                $lineIndex++;
                
            }
//            $page->setCellValue("A2", $this->formatDate());
//            $n = 4;
//            foreach($this->schedule as $course){
//                foreach($course["groups"] as $group){
//                    // var_dump($group);
//                        $page->setCellValue("A$n", $group['name']);
//                        foreach($group['schedule'] as $item){
//                            $address = $this->getAddressForNumber($item['number'], $n);
//                            $curVal = $page->getCell($address)->getValue();
//                            $page->setCellValue($address, $curVal.$item["discipline"].' '.$item["teacher"]. "\n");
//                        }
//                        $n++;
//                }
//            }
            $pageIndex++;
        }
        

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
            case 6:
            $address = "I$n";
            break;
            case 7:
            $address = "J$n";
            break;
            case 8:
            $address = "K$n";
            break;
            case 9:
            $address = "L$n";
            break;
            case 10:
            $address = "M$n";
            break;
            case 11:
            $address = "N$n";
            break;
            case 12:
            $address = "O$n";
            break;
            case 13:
            $address = "P$n";
            break;
            case 14:
            $address = "Q$n";
            break;
            case 15:
            $address = "R$n";
            break;
            case 16:
            $address = "S$n";
            break;
            case 17:
            $address = "T$n";
            break;
            case 18:
            $address = "U$n";
            break;
            case 19:
            $address = "V$n";
            break;
            case 20:
            $address = "W$n";
            break;
            case 21:
            $address = "X$n";
            break;
            case 22:
            $address = "Y$n";
            break;
            case 23:
            $address = "Z$n";
            break;
            case 24:
            $address = "AA$n";
            break;
            case 25:
            $address = "AB$n";
            break;
            case 26:
            $address = "AC$n";
            break;
            case 27:
            $address = "AD$n";
            break;
            case 28:
            $address = "AE$n";
            break;
            case 29:
            $address = "AF$n";
            break;
            case 30:
            $address = "AG$n";
            break;
            case 31:
            $address = "AH$n";
            break;
            case 32:
            $address = "AJ$n";
            break;
            case 33:
            $address = "AK$n";
            break;
            default:
            $address = "AL$n";
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