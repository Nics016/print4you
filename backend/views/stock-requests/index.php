<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\User;
use common\models\Office;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Пользователь',
                'attribute' => 'user_id',
                'value' => function($model){
                    $manager = User::findIdentity($model['user_id']);
                    return $manager['username'];
                }
            ],
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

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
