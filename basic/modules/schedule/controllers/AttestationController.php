<?php

namespace app\modules\schedule\controllers;

use Yii;
use app\models\AttestationSearch;
use app\models\Attestation;
use app\models\Group;

/**
 * TeacherloadController implements the CRUD actions for Teacherload model.
 */
class AttestationController extends DefaultController
{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'add-teacherload' => ['POST'],
                    'remove-teacherload' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $model = new Attestation();
        $teacherloadId = Yii::$app->request->post('teacherloadId') ?? null;
        if ($model->load(Yii::$app->request->post()) && !empty($teacherloadId)) {
            if($model->save()){
                $teacherloadInAttestation = new \app\models\TeacherloadInAttestation();
                $teacherloadInAttestation->attestationId = $model->id;
                $teacherloadInAttestation->teacherloadId = $teacherloadId;
                if($teacherloadInAttestation->save()){
                    return $this->redirect(['/schedule/attestation/for-group', 'groupId' => $teacherloadInAttestation->teacherload->groupId]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'group' => $group,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = \app\models\Attestation::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/schedule/attestation/for-group', 'groupId' => $model->group->id]);
        }
        
        $searchModel = new \app\models\TeacherloadInAttestationSearch();
        $dataProvider = $searchModel->forAttestation($model, Yii::$app->request->queryParams);

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionForGroup($groupId = 0)
    {
        $group = Group::findOne($groupId);
        $model = new \app\models\Attestation();
        $searchModel = new AttestationSearch();
        $dataProvider = $searchModel->forGroup($group, Yii::$app->request->queryParams);

        return $this->render('for-group', [
            'model' => $model,
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    
    public function actionDelete($id)
    {
        $model = Attestation::findOne($id);
        $model->delete();
        

        return $this->redirect(['/schedule/attestation/for-group', 'groupId' => $model->group->id]);
    }
    
    public function actionAddTeacherload() {
        $attestation = Attestation::findOne(Yii::$app->request->post('attestationId'));
        if($attestation->addTeacherload(Yii::$app->request->post('teacherloadId'))){
            return $this->redirect(Yii::$app->request->referrer);
        }
    }
    public function actionRemoveTeacherload() {
        $model = \app\models\TeacherloadInAttestation::findOne(Yii::$app->request->get('teacherloadInAttestationId'));
        $attestation = $model->attestation;
        $group = $attestation->group;
        $model->delete();
        // TODO Вот такой вот костыль для обновления данных о связанных записях
        $attestation = Attestation::findOne($attestation->id);
        
        if(count($attestation->teacherloadInAttestations) > 0){
            return $this->redirect(Yii::$app->request->referrer);
        } 
        else {
            $attestation->delete();
            return $this->redirect(['/schedule/attestation/for-group', 'groupId' => $group->id]);
        }
    }

}
