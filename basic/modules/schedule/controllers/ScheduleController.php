<?php

namespace app\modules\schedule\controllers;

use Yii;
use app\models\Schedule;
use app\models\User;
use app\models\Teacherload;
use app\models\TLS;
use yii\web\NotFoundHttpException;

/**
 * ScheduleController implements the CRUD actions for Schedule model.
 */
class ScheduleController extends DefaultController
{
    /**
     * Lists all Schedule models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Updates an existing Schedule model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Schedule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Schedule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Schedule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


##################
    public function actionSave()
    {
        // $log = new Log();
        // $log->message = 'Shedule save';
        // $log->save();
        // Yii::$app->ToolDB->tableBackup('shedule');
        // Yii::$app->ToolDB->tableBackup('mark');
        $post = Yii::$app->request->post();
        $curDate = $post['date'];
        // $curDate = '2020-04-09';
        // $curDate = '2020-03-26';
        // Получить имеющееся расписание на эту дату
        $availableSchedule = Schedule::find()->where(['date' => $curDate])->andWhere(['deleted' => '0'])->all();
        // var_dump($availableSchedule);
        $newSchedule = isset($post['items']) ? $post['items'] : array();
        $errors = array();
        $processed = array();
        // var_dump($availableSchedule);
        if(empty($availableSchedule)){
            // На этот день не было расписания
            // Просто сохраняем новое
            foreach($newSchedule as $item){
                $scheduleItem = new Schedule();
                $scheduleItem->setAttributes($item);
                $scheduleItem->save();
                $processed[] = $item;
                if(count($scheduleItem->errors) > 0) {
                    $errors[] = $scheduleItem->errors;
                }
            }
        } else {
            // На этот день уже имелось расписание - надо действовать осторожно
            foreach($newSchedule as $item){
                // Есть похожая запись?
                $idSimilar = $this->findSimilar($item, $availableSchedule);
                if(!empty($idSimilar)){
                    // Обновить имеющуюся
                    $scheduleItem = Schedule::find()->where(['id' =>$idSimilar])->andWhere(['deleted' => '0'])->one();
                    $scheduleItem->setAttributes($item);
                    $scheduleItem->save();
                    $processed[] = $item;
                    if(count($scheduleItem->errors) > 0) {
                        $errors[] = $scheduleItem->errors;
                    }
                    // Удалить из массива имеющихся
                    $availableSchedule = $this->deleteProcessedFromAvailable($idSimilar, $availableSchedule);
                } else{
                    // Добавить новую
                    $scheduleItem = new Schedule();
                    $scheduleItem->setAttributes($item);
                    $scheduleItem->save();
                    $processed[] = $item;
                    if(count($scheduleItem->errors) > 0) {
                        $errors[] = $scheduleItem->errors;
                    }
                }

            }
            // Удалить оставшиеся старые записи
            foreach($availableSchedule as $rec){
                // Всё равно помечать удалённой. Фильтрация на удалённость лишняя
                $model = Schedule::findOne($rec->id);
                $model->delete();
            }
        }


        // Оповещалки
        Yii::$app->Notifier->vk($curDate);
        return $this->asJson([
            'received' => $processed,
            'errors' => $errors
        ]);
        
        
    }

    public function actionGetdata($needDate = NULL){
        if ($needDate == NULL) {
            $needDate = Yii::$app->request->post('needDate');
        }
        if ($needDate == NULL) {
            // return var_dump($data);
            return 'bad date. Технический долг';
        }
        // $profiler = new Profiler();
        // $profiler->start('shedule/getData');
        $response["date"] = $needDate;
        // $response["versionTLS"] = Yii::$app->ParameterManager->getVersionTLS();
        $response["courses"] = array();
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
                // Расписание на указанную дату по текущей группе
                $tmpGroup["shedule"] =  Yii::$app->db->createCommand("SELECT * from schedule where `deleted` = 0 and schedule.date = '$needDate' and schedule.teacherLoadId in (select id from teacherload where `deleted` = 0 and teacherload.groupId = $idGroup)")
                ->queryAll();
                // Добавляем группу в курс
                array_push($tmpCourse["groups"], $tmpGroup);
            }

            // Добавляем курс в ответ
            $tmpCourse["number"] = $course;
            array_push($response["courses"], $tmpCourse);
        }
        // $profiler->finish();
        return $this->asJson($response);
    }

    public function actionGettls()
    {
        
        // $profiler = new Profiler();
        // $profiler->start('shedule/getTLS');
        $tls = new TLS();
        // $profiler->finish();
        return $this->asJson($tls->asArray()); 
    }

    protected function findSimilar($item, $availableSchedule)
    {
        $result = null;
        foreach($availableSchedule as $available){
            if($available->teacherLoadId == $item["teacherLoadId"] &&$available->number == $item["number"]){
                $result = $available->id;
            }
        }
        return $result;
    }

    protected function deleteProcessedFromAvailable($scheduleId, $availableSchedule)
    {
        $res = array();
        foreach($availableSchedule as $item){
            if($item->id != $scheduleId){
                array_push($res, $item);
            }
        }
        return $res;
    }

    public function actionGetFile($date = NULL)
    {
        // TODO рефакторинг
        // TODO дублирование с API
        // Если дату не указали в гет - ищем в пост
        if ($date == NULL) {
            $date = Yii::$app->request->post('date');
        }
        // если не нашли в пост - находим последнюю дату занятия
        if ($date == NULL) {
            $date = Yii::$app->db->createCommand("select `date` from schedule where `deleted` = 0 order by `date` desc limit 1")
            ->queryOne()["date"];
        }
        $response["date"] = $date;
        $response["container"] = array();
        for($course = 1; $course <= 4;$course++){
            // Временный массив для групп текущего курса
            $tmpCourse["groups"] = array();
            $groups = Yii::$app->db->createCommand("select * from `group` where course = '$course' and `deleted` = 0")
            ->queryAll();
            foreach($groups as $group){
                $idGroup = $group["id"];
                // Описывем название группы и нагрузки
                $tmpGroup["name"] = $group["name"];
                $tmpGroup["id"] = $group["id"];

                // Расписание на указанную дату по текущей группе без УЧ
                $tmpSchedule = array();
                $loads =  Yii::$app->db->createCommand("SELECT * from schedule where `deleted` = 0 and schedule.date = '$date' 
                and schedule.teacherLoadId in (select id from teacherload where `deleted` = 0 and teacherload.groupId = $idGroup) and forTeach = 0 order by schedule.number")
                ->queryAll();
                foreach($loads as $schedule){
                    $teacherLoad = Teacherload::find()->where(['deleted' => '0'])->andWhere(['id' => $schedule["teacherLoadId"]])->one();
                    // $teacherLoad = Teacherload::findOne($schedule["teacherLoadId"]);
                    $tmp["number"] = $schedule["number"];
                    $tmp["type"] = $schedule["type"];
                    $tmp["discipline"] = $teacherLoad->discipline->shortName . ' '. $schedule["type"];
                    $tmp["teacher"] = $teacherLoad->user->lName;
                    $replacer = User::find()->where(['deleted' => '0'])->andWhere(['id' => $schedule["replaceTeacherId"]])->one();
                    // $replacer = User::findOne($schedule["replaceTeacherId"]);
                    if(!empty($replacer)){
                        $tmp["teacher"] = $replacer->lName;
                    }

                    array_push($tmpSchedule, $tmp);
                }
                $tmpGroup["schedule"] = $tmpSchedule;
                // Добавляем группу в курс
                array_push($tmpCourse["groups"], $tmpGroup);
            }
    
            // Добавляем курс в ответ
            $tmpCourse["number"] = $course;
            array_push($response["container"], $tmpCourse);
        }
        Yii::$app->toExcel->getFile($response);
    }

}
