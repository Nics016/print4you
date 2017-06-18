<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use frontend\models\RequestCallForm;

/* @var $this yii\web\View */
/* @var $model common\models\Requests */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-view">

    <h1>Заявка от <?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить заявку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
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
            'email:email',
            'comment',
            [
                'label' => 'Дата',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
        ],
    ]) ?>

</div>
