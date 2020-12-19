<?php
// TODO интерфейс
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
/* @var $this yii\web\View */

$this->title = 'Семестровые оценки ';
?>
<div class="site-index">

  

    <div class="body-content">

    <?php
        if(!empty($group)){
            $this->title .= $group->name;
        }
        echo '<h1>'.$this->title.'</h1>';
        ?>
        <?= Html::a('Скачать Excel файл со всеми группами', ['/inspector/attestation/excel'], ['class' => 'btn btn-info']) ?>
        <span><?php
        if(!empty($logLastUpdate)){
            echo '('.$logLastUpdate->datetime.' '. $logLastUpdate->userId.')';
        }
        ?></span>
        <hr>
        <?php
        $url = Url::toRoute(['/inspector/attestation']);
            $form = ActiveForm::begin([
            'id' => 'form-input-example',
            'method' => 'get',
            'action' => $url,
            'enableClientValidation' => false,
            'enableAjaxValidation' => false
        ]);
        ?>
        <select name="groupId" id="">
        <option value="">----</option>
                <?php
                    foreach($groups as $item){
                        if(!empty($group) && $item->id == $group->id){
                            echo '<option value="'.$item->id.'" selected>'.$item->name.'</option>';
                        } else {
                            echo '<option value="'.$item->id.'">'.$item->name.'</option>';
                        }
                    }
                ?>
        </select>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-success']) ?>
        <?php
        ActiveForm::end();
        ?>

        
        <?php
        if(!empty($group)){
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'name',
                    'date',
                    'type',
                    'semestrNumber',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                    ],
                ],
            ]);
        }

        ?>
    </div>
</div>
