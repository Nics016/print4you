<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Office;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model backend\models\StockColors */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stock Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-colors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
        ],
    ]) ?>

</div>
