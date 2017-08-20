<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Office;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цвета краски';
$this->params['breadcrumbs'][] = $this->title;

/**
 * Разрешенные для текущего пользователя действия с красками
 * 
 * @var string
 */
$allowedActions = '{view}';
if (Yii::$app->user->identity->role == User::ROLE_ADMIN){
    $allowedActions .= ' {update}{delete}';
}
?>
<div class="stock-colors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая краска', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            'liters',
            [
                'label' => 'Офис',
                'attribute' => 'office_id',
                'value' => function($model){
                    $record = Office::FindOne(['id' => $model['office_id']]);

                    if ($record)
                        return $record['address'];                        
                    
                    return "Офис не задан";
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $allowedActions,
            ],
        ],
    ]); ?>
</div>
