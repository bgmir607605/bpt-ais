<?php
namespace app\modules\admin\controllers;
use Yii;
use app\models\Group;
use app\models\StudentInGroup;
use app\models\User;

class GroupController extends DefaultController {
    public function actionIndex()
    {
        $groups = Group::find()->where(['deleted' => '0'])->orderBy('name')->all();
        return $this->render('index', ['groups' => $groups],);
    }

    public function actionStudents($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $ids = StudentInGroup::find()->select('userId')->where(['groupId' => $groupId]);
        $students = User::find()->where(['in', 'id', $ids])->andWhere(['deleted' => '0'])->orderBy('lName')->all();
        return $this->render('students', [
            'students' => $students,
            'group' => $group,
        ]);
    }

    public function actionAddStudents()
    {
        // Считать данные из поста(Префикс идГрупы и список студентов)
        $groupId = Yii::$app->request->post('groupId');
        $prefix = Yii::$app->request->post('prefix');
        $students = Yii::$app->request->post('students');
        // Распарсить список студентов
        $students = explode(';', $students);
        $UsersIds = array();
        for($i = 0; $i < count($students); $i++){
            $students[$i] = explode(' ', $students[$i]);
            $user = new User();
            $user->lName = trim($students[$i][0]);
            $user->fName = trim($students[$i][1]);
            $user->mName = trim($students[$i][2]);
            $user->student = 1;
            $user->username = $this->generate_login($prefix.'-'.$user->lName.'-'.$user->fName);
            $user->password = Yii::$app->getSecurity()->generatePasswordHash('0000', 10);
            $user->save();
            $UsersIds[] = $user->id;
        }
        // Добавить привязку к группе
        foreach($UsersIds as $userId){
            $model = new StudentInGroup();
            $model->groupId = $groupId;
            $model->userId = $userId;
            $model->save();
        }
        // Вернуть на /admin/group/students
        return $this->redirect(['/admin/group/students', 'groupId' => $groupId]);
    }
    function generate_login($value)
    {
        $converter = array(
            'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
            'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
            'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
            'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
            'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
            'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
            'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
        
            'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
            'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
            'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
            'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
            'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
            'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
            'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
        );
        
        $value = strtr($value, $converter);
        return $value;
    }

    
}