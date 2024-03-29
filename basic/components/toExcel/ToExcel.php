<?php
namespace app\components\toExcel;

use yii\base\Component;
use yii\helpers\ArrayHelper;
use app\models\Group;
use app\models\Teacherload;
use app\models\MonitoringMark;
use app\components\toExcel\Classes\PHPExcel\IOFactory;
use app\components\toExcel\Classes\PHPExcel;

class ToExcel extends Component{
    protected $date;
    protected $pathFile;
    protected $tempFile;
    protected $schedule;
    protected $phpexcel;
    
    public function getMonitoring($groupId = 0){
        $this->tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $this->phpexcel = new PHPExcel();
        $pageIndex = 0;
        if($groupId == 0){
            $groups = Group::find()->where(['deleted' => '0'])->all();
        } else{
            $groups = Group::find()->where(['deleted' => '0'])->andWhere(['id' => $groupId])->all();
        }
        foreach ($groups as $group){
            $this->phpexcel->createSheet($pageIndex); //  
            $page = $this->phpexcel->setActiveSheetIndex($pageIndex); // Делаем активной 
            $this->phpexcel->getActiveSheet()->setTitle($group->name);
            $teacherloads = Teacherload::find()->where(['groupId' => $group->id])->andWhere(['deleted' => '0'])->all();
            $monitoringMarks = MonitoringMark::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($teacherloads, 'id')])->all();
            $columnindex = 1;
            $lineIndex = 1;
            
//            Индекс строки начинается с 1, столбца - с 0
//            $page->getCellByColumnAndRow(30, 2)->setValue('asd123');
            
            $actualTeacherloads = [];
            foreach($teacherloads as $teacherload){
                if($teacherload->countSchedules > 0){
                    $actualTeacherloads[] = $teacherload;
                }
            }
            $teacherloads = $actualTeacherloads;
            foreach($teacherloads as $teacherload){
                $cellValue =  $teacherload->discipline->shortName.'('.$teacherload->user->lName.')';
                $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
                $columnindex++;
            }
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Ср. знач');
            $columnindex++;
            $lineIndex++;
            foreach($group->students as $student){
                $startColumnIndex = 0;
                $columnindex = $startColumnIndex;
                $cellValue = $student->lName.' '.$student->fName;
                $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
                $columnindex++;
                foreach($teacherloads as $teacherload){
                    $cellValue = '';
                    foreach($monitoringMarks as $mark){
                        if($mark->userId == $student->id && $mark->teacherLoadId == $teacherload->id){
                            $cellValue .= $mark->mark;
                        break;
                        }
                    }
                    $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
                    $columnindex++;
                }
                $startCellCoordinates = $page->getCellByColumnAndRow($startColumnIndex +1, $lineIndex)->getCoordinate();
                $finishCellCoordinates = $page->getCellByColumnAndRow($columnindex -1, $lineIndex)->getCoordinate();
                $cellValue = "=AVERAGE($startCellCoordinates:$finishCellCoordinates)";
                $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
                $lineIndex++;
                
            }
            $pageIndex++;
        }
        

        $objWriter = IOFactory::createWriter($this->phpexcel, 'Excel2007');
        $objWriter->save($this->tempFile);
        $excelOutput = file_get_contents($this->tempFile);
        unlink($this->tempFile);
        return $excelOutput;
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

//    TODO адо от неё избавиться. Там где она используется перейти на это
//    Индекс строки начинается с 1, столбца - с 0
//    $page->getCellByColumnAndRow(30, 2)->setValue('asd123');
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

    
    // Excel посещаемости для группы за месяц для кл руководителя
    public function getSkips($group, $period, $start){
        $tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $phpexcel = new PHPExcel();
        $phpexcel = $this->addSkipsPageForGroup($phpexcel, $group, $period, $start);
        $objWriter = IOFactory::createWriter($phpexcel, 'Excel2007');
        $objWriter->save($tempFile);
        $excelOutput = file_get_contents($tempFile);
        unlink($tempFile);
        return $excelOutput;
    }
    
    /**
     * Посещаемость для инспектора
     */
    public function getSkipsForInspector($period, $start){
        
        $tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $phpexcel = new PHPExcel();
        // Получаем список групп
        $groups = Group::find()->orderBy('name')->all();
        foreach($groups as $group){
            $phpexcel = $this->addSkipsPageForGroup($phpexcel, $group, $period, $start);
        }
        // Пишем файл
        $objWriter = IOFactory::createWriter($phpexcel, 'Excel2007');
        $objWriter->save($tempFile);
        $excelOutput = file_get_contents($tempFile);
        unlink($tempFile);
        return $excelOutput;
    }


    public function addSkipsPageForGroup(PHPExcel $phpexcel, $group, $period, $start)
    {
        $pageIndex = 0;
        // Индекс столбца итого
        $indexColumnItogo = 0;
        // Индекс столбца ув
        $indexColumnUv = 0;
        // Индекс столбца неув
        $indexColumnNeUv = 0;
        $phpexcel->createSheet($pageIndex);
        $page = $phpexcel->setActiveSheetIndex($pageIndex); // Делаем активной 
        $phpexcel->getActiveSheet()->setTitle($group->name);
        $columnindex = 0;
        $lineIndex = 1;

        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Студент');
        $columnindex++;
        foreach($period as $date){
            $cellValue =  $date->format('d');
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
            $columnindex++;
        }
        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Итого');
        $indexColumnItogo = $columnindex;
        $columnindex++;
        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Ув.');
        $indexColumnUv = $columnindex;
        $columnindex++;
        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Не ув.');
        $indexColumnNeUv = $columnindex;
        $columnindex++;
        $lineIndex++;
        
        $groupSkips = $group->getSkipsForYearAndMonth($start->format('Y'), $start->format('m'));

        foreach($group->students as $student){
            $startColumnIndex = 0;
            $columnindex = $startColumnIndex;
            $cellValue = $student->lName.' '.$student->fName;
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
            $columnindex++;
            // Итого по студенту
            $sumForMonth = 0;
            // Печатаем по дням
            foreach($period as $date){
                $skipsOnDate = 0;
                foreach ($groupSkips as $skip){
                    if($skip->studentId == $student->id && $skip->schedule->date == $date->format('Y-m-d')){
                        $skipsOnDate += $skip->schedule->hours;
                    }
                }
                $sumForMonth += $skipsOnDate;
                if($skipsOnDate > 0){
                    $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($skipsOnDate.'');
                }
                $columnindex++;
            }
            // Итого по студенту
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($sumForMonth.'');
            $columnindex++;
            // Уважительно по студенту
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('0');
            $columnindex++;
            // Не уважительно по студенту (формула)
            // Координаты ячейкт "Итого"
            $fCell = $page->getCellByColumnAndRow($columnindex -2, $lineIndex)->getCoordinate();
            // Координаты ячейкт "Уважительно"
            $sCell = $page->getCellByColumnAndRow($columnindex -1, $lineIndex)->getCoordinate();
            // Пишем формулу вычитания уважительных из итого
            $cellValue = "=$fCell-$sCell";
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue.'');
            $columnindex++;
            $lineIndex++;
        }
        // Последняя строка с суммами
        $page->getCellByColumnAndRow(0, $lineIndex)->setValue('Итого');
        // Итого
        $fCell = $page->getCellByColumnAndRow($indexColumnItogo, 2)->getCoordinate();
        $sCell = $page->getCellByColumnAndRow($indexColumnItogo, $lineIndex -1)->getCoordinate();
        $cellValue = "=SUM($fCell:$sCell)";
        $page->getCellByColumnAndRow($indexColumnItogo, $lineIndex)->setValue($cellValue.'');
        // Ув
        $fCell = $page->getCellByColumnAndRow($indexColumnUv, 2)->getCoordinate();
        $sCell = $page->getCellByColumnAndRow($indexColumnUv, $lineIndex -1)->getCoordinate();
        $cellValue = "=SUM($fCell:$sCell)";
        $page->getCellByColumnAndRow($indexColumnUv, $lineIndex)->setValue($cellValue.'');
        // Не ув
        $fCell = $page->getCellByColumnAndRow($indexColumnNeUv, 2)->getCoordinate();
        $sCell = $page->getCellByColumnAndRow($indexColumnNeUv, $lineIndex -1)->getCoordinate();
        $cellValue = "=SUM($fCell:$sCell)";
        $page->getCellByColumnAndRow($indexColumnNeUv, $lineIndex)->setValue($cellValue.'');
        return $phpexcel;
    }
    
    // Excel семестровых для группы
    public function getAttestationsForGroup($group){
        $tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $phpexcel = new PHPExcel();
        $phpexcel = $this->addAttestationsPageForGroup($phpexcel, $group);
        $objWriter = IOFactory::createWriter($phpexcel, 'Excel2007');
        $objWriter->save($tempFile);
        $excelOutput = file_get_contents($tempFile);
        unlink($tempFile);
        return $excelOutput;
    }
    
    public function addAttestationsPageForGroup(PHPExcel $phpexcel, Group $group)
    {
        $pageIndex = 0;
        $phpexcel->createSheet($pageIndex);
        $page = $phpexcel->setActiveSheetIndex($pageIndex); // Делаем активной 
        $phpexcel->getActiveSheet()->setTitle($group->name);
        $columnindex = 0;
        $lineIndex = 1;

        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Семестр');
        $lineIndex++;
        $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue('Студент');
        $lineIndex--;
        $columnindex++;
        foreach($group->attestations as $attestation){
            $cellValue =  (String) $attestation->semestrNumber;
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
            $lineIndex++;
            $cellValue =  $attestation->name;
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
            $lineIndex--;
            $columnindex++;
        }
        $lineIndex++;
        $lineIndex++;
        

        foreach($group->students as $student){
            $startColumnIndex = 0;
            $columnindex = $startColumnIndex;
            $cellValue = $student->lName.' '.$student->fName;
            $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
            $columnindex++;
            foreach($group->attestations as $attestation){
                $cellValue = (String)$attestation->getValueOfMarkForStudent($student->id);
                $page->getCellByColumnAndRow($columnindex, $lineIndex)->setValue($cellValue);
                $columnindex++;
            }
            $lineIndex++;
        }
        return $phpexcel;
    }
    
    public function getAttestations() {
        $tempFile = \Yii::getAlias('@app') .'/components/toExcel/'.$this->date.'.xlsx'; 
        $phpexcel = new PHPExcel();
        // Получаем список групп
        $groups = Group::find()->orderBy('name')->all();
        foreach($groups as $group){
            $phpexcel = $this->addAttestationsPageForGroup($phpexcel, $group);
        }
        // Пишем файл
        $objWriter = IOFactory::createWriter($phpexcel, 'Excel2007');
        $objWriter->save($tempFile);
        $excelOutput = file_get_contents($tempFile);
        unlink($tempFile);
        return $excelOutput;
    }
    
}