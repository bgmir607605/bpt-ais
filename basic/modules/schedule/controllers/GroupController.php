<?php

namespace app\modules\schedule\controllers;

use Yii;
use app\models\Group;
use app\models\GroupSearch;
use app\models\Direct;
use yii\web\NotFoundHttpException;

/**
 * GroupController implements the CRUD actions for Group model.
 */
class GroupController extends DefaultController
{

    /**
     * Lists all Group models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Group model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Group();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        // TODO Убрать дублирование
        $directs = Direct::find()->where(['deleted' => '0'])->all();
        $directsList = array();
        foreach($directs as $direct){
            $id = $direct["id"];
            $directsList[$id] = $direct["code"].' '.$direct["name"];
        }
        
        $coursesList =['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        // !
        return $this->render('create', [
            'model' => $model,
            'courses' => $coursesList,
            'directs' => $directsList,
        ]);
    }

    /**
     * Updates an existing Group model.
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

        $directs = Direct::find()->where(['deleted' => '0'])->all();
        $directsList = array();
        foreach($directs as $direct){
            $id = $direct["id"];
            $directsList[$id] = $direct["code"].' '.$direct["name"];
        }
        
        $coursesList =['1' => '1', '2' => '2', '3' => '3', '4' => '4'];
        // !
        return $this->render('update', [
            'model' => $model,
            'courses' => $coursesList,
            'directs' => $directsList,
        ]);
    }

    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
