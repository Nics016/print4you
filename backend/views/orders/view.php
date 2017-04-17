<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = "Заказ №".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот заказ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Статус заказа',
                'attribute' => 'order_status',
            ],
            [
                'label' => 'Цена (руб.)',
                'attribute' => 'price',
            ],
            [
                'label' => 'Менеджер',
                'attribute' => 'manager_id',
                'value' => function($model){
                    $manager = $model->getManager($model['manager_id']);
                    return $manager['username'];
                }
            ],
            [
                'label' => 'Комментарий',
                'attribute' => 'comment',
            ],
            [
                'label' => 'Дата создания',
                'attribute' => 'created_at',
            ],
            [
                'label' => 'Дата изменения',
                'attribute' => 'updated_at',
            ],
        ],
    ]) ?>

</div>
