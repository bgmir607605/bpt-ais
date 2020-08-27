<?php

namespace app\modules\schedule\controllers;

use Yii;
use app\models\Discipline;
use app\models\DisciplineSearch;
use app\models\Direct;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;

/**
 * DisciplineController implements the CRUD actions for Discipline model.
 */
class DisciplineController extends DefaultController
{

    /**
     * Lists all Discipline models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Discipline();
        $searchModel = new DisciplineSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $directsList = Direct::find()->orderBy('name')->all();
        array_unshift($directsList, ['id' => '', 'name' =>'Показать всё']);
        array_unshift($directsList, ['id' => '-1', 'name' =>'Только общие']);

        $directsList = ArrayHelper::map($directsList, 'id', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'directsList' => $directsList,
            'directsListForForm' => $this->getDirectsList(),
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Discipline model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Discipline();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Discipline model.
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
            'directsListForForm' => $this->getDirectsList(),
            'model' => $model,
        ]);
    }

    /**
     * Finds the Discipline model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Discipline the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Discipline::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getDirectsList(){
        $directs = Direct::find()->orderBy('code')->all();
        $directsList = array();
        $directsList[''] = 'Общ';
        foreach($directs as $direct){
            $id = $direct->id;
            $directsList[$id] = $direct->code.' '.$direct->name;
        }
        return $directsList;
    }
}
