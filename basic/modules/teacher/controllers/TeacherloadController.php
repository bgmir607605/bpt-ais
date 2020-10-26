<?php

namespace app\modules\teacher\controllers;

use Yii;
use app\models\Teacherload;
use app\models\TeacherloadSearch;
use app\models\User;
use app\models\Discipline;
use app\models\Group;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

/**
 * TeacherloadController implements the CRUD actions for Teacherload model.
 */
class TeacherloadController extends DefaultController
{

    public function actionIndex()
    {
        $userId = Yii::$app->user->identity->id;
        $searchModel = new TeacherloadSearch();
        $dataProvider = $searchModel->forTeacher($userId, Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
