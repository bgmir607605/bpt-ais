<?php
namespace app\modules\teacher\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use app\models\Attestation;
use app\models\AttestationMark;


class AttestationController extends DefaultController {

    public function actionIndex()
    {
        $semesters = Attestation::getArrayOfSemestersNumbers();
        return $this->render('index',[
            'semesters' => $semesters,
        ]);
    }
    
    public function actionForSemestr($semestrNumber) {
         $attestations = Attestation::findForTeacherInSemestr(Yii::$app->user->identity->id, $semestrNumber);
         return $this->render('forSemestr',[
            'attestations' => $attestations,
            'semestrNumber' => $semestrNumber,
        ]);
    }

    public function actionMark($id = NULL)
    {
        $attestation = Attestation::findOne($id);
        if(!$attestation->hasAccessForThisTeacher(Yii::$app->user->identity->id)){
            // Не имеем доступ
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        
        return $this->render('mark', [
            'attestation' => $attestation,
        ]);
    }

    public function actionSave()
    {
        $attestationId = Yii::$app->request->post('attestationId');
        $attestation = Attestation::findOne($attestationId);
        if(!$attestation->hasAccessForThisTeacher(Yii::$app->user->identity->id)){
            throw new ForbiddenHttpException('Доступ запрещён');
        }
        $post = Yii::$app->request->post();
        foreach($post as $k => $v){
            if($k != 'csrf' && $k != 'attestationId'){
                $userId = str_replace('studentId', '', $k);
                $attestation->saveMarkForStudent($v, $userId);
            }
        }
        Yii::$app->session->setFlash('success', "Данные сохранены");
        return $this->redirect(Yii::$app->request->referrer);
    }

}