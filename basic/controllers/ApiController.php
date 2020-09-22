<?php

namespace app\controllers;

use Yii;
use app\models\{Teacherload, User, Log};
use yii\web\Controller;

/**
 * DirectController implements the CRUD actions for Direct model.
 */
class ApiController extends Controller
{

public function actionGetBackup()
    {
        $log = new Log();
        $log->ip = $_SERVER['REMOTE_ADDR'];
        $log->action = $this->route;
        $log->userId = 'guest';
        $log->save();
        $file = Yii::$app->ToolDB->fullBackup();
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

    public function actionGetdata($needDate = NULL){
        // TODO рефакторинг
        // Если дату не указали в гет - ищем в пост
        if ($needDate == NULL) {
            $needDate = Yii::$app->request->post('needDate');
        }
        // если не нашли в пост - находим последнюю дату занятия
        if ($needDate == NULL) {

            $scheduleDates = Yii::$app->db->createCommand('select date from schedule where `deleted` = 0 GROUP BY date ORDER BY date desc')
                ->queryAll();
            // $certificationDates = Yii::$app->db->createCommand("select date from certification where type = 'Экзамен' GROUP BY date ORDER BY date desc ")
            //     ->queryAll();
            $dates = array();
            
            // foreach(array_merge($scheduleDates, $certificationDates) as $item){
            //     $dates[] = $item["date"];
            // }
            foreach($scheduleDates as $item){
                $dates[] = $item["date"];
            }

            rsort($dates);

            $needDate = $dates[0];
        }
        $response["date"] = $needDate;
        $response["container"] = array();
        for($course = 1; $course <= 4;$course++){
            // Временный массив для групп текущего курса
            $tmpCourse["groups"] = array();
            $groups = Yii::$app->db->createCommand("select * from `group` where course = '$course' AND deleted = 0")
            ->queryAll();
            foreach($groups as $group){
                $idGroup = $group["id"];
                // Описывем название группы и нагрузки
                $tmpGroup["name"] = $group["name"];
                $tmpGroup["id"] = $group["id"];

                // Расписание на указанную дату по текущей группе без УЧ
                $tmpSchedule = array();
                // Уроки
                $loads =  Yii::$app->db->createCommand("SELECT * from schedule where schedule.date = '$needDate' and deleted = 0 
                and schedule.teacherLoadId in (select id from teacherload where teacherload.groupId = $idGroup and deleted = 0) and forTeach = 0 order by schedule.number")
                ->queryAll();
                foreach($loads as $schedule){
                    $teacherLoad = Teacherload::findOne($schedule["teacherLoadId"]);
                    $tmp["number"] = $schedule["number"];
                    $tmp["type"] = $schedule["type"];
                    $tmp["discipline"] = $teacherLoad->discipline->shortName . ' '. $schedule["type"];
                    $tmp["teacher"] = $teacherLoad->user->lName;
                    $replacer = User::findOne($schedule["replaceTeacherId"]);
                    if(!empty($replacer)){
                        $tmp["teacher"] = $replacer->lName;
                    }

                    array_push($tmpSchedule, $tmp);
                }
                // Аттестация
                // Получить список аттестаций по группе, отфильтровать по дате
                // $group = Group::findOne($idGroup);
                // foreach($group->certifications as $cert){
                //     if($cert->date == $needDate && $cert->type == 'Экзамен'){
                //         $tmp = array();
                //         $tmp['number'] = '';
                //         $tmp['type'] = '';
                //         $tmp['teacher'] = '';
                //         $tmp['discipline'] = $cert->name;
                //         array_push($tmpShedule, $tmp);
                //     }
                // }

                // Поместить уроки и аттестации по группе в ответ
                $tmpGroup["schedule"] = $tmpSchedule;
                // Добавляем группу в курс
                array_push($tmpCourse["groups"], $tmpGroup);
            }
    
            // Добавляем курс в ответ
            $tmpCourse["number"] = $course;
            array_push($response["container"], $tmpCourse);
        }
        return $this->asJson($response);
    }
}