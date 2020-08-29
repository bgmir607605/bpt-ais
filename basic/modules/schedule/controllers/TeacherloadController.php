<?php

namespace app\modules\schedule\controllers;

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

    /**
     * Lists all Teacherload models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeacherloadSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $groups = Group::find()->where(['deleted' => '0'])->all();
        $groups = ArrayHelper::map($groups, 'id', 'name');
        $disciplines = Discipline::find()->where(['deleted' => '0'])->all();
        $disciplines = ArrayHelper::map($disciplines, 'id', 'fullName');
        Yii::$app->session->setFlash('info', 'Добавление нагрузок осуществляется через страницу "Группы"');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'teachersList' => User::teachersForDropdown(),
            'groupsList' => $groups,
            'disciplinesList' => $disciplines,
        ]);
    }

    /**
     * Creates a new Teacherload model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $model = new Teacherload();
        $disciplines = Discipline::find()->where(['deleted' => '0'])
        ->andWhere(['or', ['is', 'directId', new \yii\db\Expression('null')], ['directId' => $group->directId]])
        ->all();
        $disciplines = ArrayHelper::map($disciplines, 'id', 'fullName');


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/schedule/teacherload/list-for-group', 'groupId' => $model->groupId]);
        }

        return $this->render('create', [
            'model' => $model,
            'group' => $group,
            'teachers' => User::teachersForDropdown(),
            'disciplines' => $disciplines,
        ]);
    }

    /**
     * Updates an existing Teacherload model.
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
        $disciplines = Discipline::find()->where(['deleted' => '0'])
        ->andWhere(['or', ['is', 'directId', new \yii\db\Expression('null')], ['directId' => $model->group->directId]])
        ->all();
        $disciplines = ArrayHelper::map($disciplines, 'id', 'fullName');

        return $this->render('update', [
            'model' => $model,
            'teachers' => User::teachersForDropdown(),
            'disciplines' => $disciplines,
        ]);
    }

    /**
     * Finds the Teacherload model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Teacherload the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacherload::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListForGroup($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $searchModel = new TeacherloadSearch();
        $dataProvider = $searchModel->forGroup($groupId, Yii::$app->request->queryParams);

        return $this->render('for-group', [
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
