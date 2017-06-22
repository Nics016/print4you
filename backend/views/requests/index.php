<?php

use yii\helpers\Html;
use yii\grid\GridView;

use frontend\models\RequestCallForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'Тип заявки',
                'attribute' => 'request_type',
                'value' => function($model){
                    switch($model['request_type']){
                        case RequestCallForm::FORM_TYPE_CALL:
                            return "Звонок";
                            break;

                        case RequestCallForm::FORM_TYPE_CONTACTS:
                            return "Звонок";
                            break;

                        case RequestCallForm::FORM_TYPE_FRANCHISE:
                            return "Франшиза";
                            break;
                    }
                }
            ],
            'name',
            'phone',
            'comment',
            [
                'label' => 'Дата',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
            // 'email:email',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',
            ],
        ],
    ]); ?>
</div>
