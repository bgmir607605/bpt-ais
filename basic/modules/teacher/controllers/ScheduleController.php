<?php
namespace app\modules\teacher\controllers;

use Yii;
use app\models\Schedule;
use app\models\Teacherload;
use app\models\StudentInGroup;
use app\models\User;
use app\models\Mark;
use app\models\Skip;
use app\models\Group;

class ScheduleController extends DefaultController {
    public function actionIndex($date = null)
    {
        $userId = Yii::$app->user->identity->id;
        // Если дату не указали явно - берём сегодняшнюю дату и проверяем, есть ли на неё расписание
        if(empty($date)){
            $date = date('Y-m-d');
        }

        // Ищем расписание на дату по преподу
        // TODO переписать на построители, но после написания тестов
        // TODO тесты, с учётом замен
        $tmp = Yii::$app->db->createCommand('SELECT * FROM schedule WHERE ((teacherLoadId IN (select id FROM teacherload where userId = :userId) and replaceTeacherId is NULL) or replaceTeacherId = :replaceTeacherId) and date = :date  and deleted = 0 order by number')
            ->bindValue(':userId', $userId)
            ->bindValue(':replaceTeacherId', $userId)
            ->bindValue(':date', $date)
            ->queryAll();
        // Ищем модели
        $schedules = array();
        foreach($tmp as $item){
            $schedules[] = Schedule::findOne($item["id"]);
        }

        // Отдаём представление
        return $this->render('index',[
            'schedules' => $schedules,
            'date' => $date,
        ]);
    }
}