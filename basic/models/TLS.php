<?php
namespace app\models;

use yii\base\Model;
use Yii;

class TLS extends Model {
    public function asArray()
    {
        // $response["versionTLS"] = Yii::$app->ParameterManager->getVersionTLS();
        $response["teachers"] = User::find()->where(['teacher' => '1'])->andWhere(['deleted' => '0'])->orderBy('lName')->asArray()->all();
        $response["courses"] = array();
        // cabinets 
        $cabinets = array();
        $cab['id'] = '1';
        $cab['title'] = 'hz1';
        $cabinets[] = $cab;
        $cab['id'] = '2';
        $cab['title'] = 'hz2';
        $cabinets[] = $cab;
        $response['cabinets'] = $cabinets;
        // 
        for($course = 1; $course <= 4;$course++){
            // Временный массив для групп текущего курса
            $tmpCourse["groups"] = array();
            $groups = Yii::$app->db->createCommand("select * from `group` where `deleted` = 0 and course = '$course'")
            ->queryAll();
            foreach($groups as $group){
                $idGroup = $group["id"];
                // Описывем название группы и нагрузки
                $tmpGroup["name"] = $group["name"];
                $tmpGroup["id"] = $group["id"];


                $tmpTeacherLoads = array();
                $loads =  Yii::$app->db->createCommand("SELECT teacherload.id, discipline.shortName, user.lName FROM `teacherload` join discipline on discipline.id = teacherload.disciplineId join user on user.id = teacherload.userId  where groupId = $idGroup and `teacherload`.`deleted` = 0 order by discipline.shortName")
                ->queryAll();
                // Нагрузки по группе
                $tmpTeacherLoads[] = array('id' => '0', 'text' => '----');
                foreach($loads as $load){
                    array_push($tmpTeacherLoads, array('id' => $load["id"], 'text' => $load["shortName"].' '.$load["lName"]));
                }
                $tmpGroup["teacherLoads"] = $tmpTeacherLoads;
                // Добавляем группу в курс
                array_push($tmpCourse["groups"], $tmpGroup);
            }

            // Добавляем курс в ответ
            $tmpCourse["number"] = $course;
            array_push($response["courses"], $tmpCourse);
        }
        return $response;
    }

    public function asIdTextArray()
    {
        $response = array();
        $loads =  Yii::$app->db->createCommand("SELECT teacherload.id, discipline.shortName, user.lName FROM `teacherload` join discipline on discipline.id = teacherload.disciplineId join user on user.id = teacherload.userId where `deleted` = 0")
        ->queryAll();
        foreach($loads as $load){
            $response[$load["id"]] = $load["shortName"].' '.$load["lName"];
        }
        return $response;
    }
}