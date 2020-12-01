<?php
namespace app\components;

use yii\base\Component;
use app\models\Group;
use app\models\Schedule;
use yii\helpers\ArrayHelper;

class Notifier extends Component{

    public function vk($date) {
        // Захардкодил 5ИС
        $group = Group::findOne(14);
        $chatId = 6;
        $schedules = Schedule::find()->where(['in', 'teacherLoadId', ArrayHelper::getColumn($group->Teacherloads, 'id')])->andWhere(['date' => $date])->orderBy('number')->all();
            
        // Вынести в конфиги
        $vkToken = '188c962c622c1ed51fd36d699d8d51da54809f1fb03207ea38bb52ed438d25472bb15f92aea99f23e4151';
        $message = 'Новое расписание на '.$date."\n";
        foreach ($schedules as $item){
            $message .= $item->number .') ' .$item->teacherLoad->discipline->shortName.  ' ' .$item->type. ' ('.$item->teacherLoad->user->initials.")\n";
        }
        $url = 'https://api.vk.com/method/messages.send?v=5.46&access_token='.$vkToken.'&chat_id='.$chatId.'&message='.urlencode($message).'';
        $res = file_get_contents($url);
        //
    }


}