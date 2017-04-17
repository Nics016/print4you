<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = "Заказ №".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
        <p>
            <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
             [
                'label' => 'Статус заказа',
                'attribute' => 'order_status',
                'value' => function($model){
                    switch($model['order_status']){
                        case $model::STATUS_NEW:
                            return 'Новый';
                            break;

                        case $model::STATUS_PROCCESSING:
                            return 'В обработке';
                            break;

                        case $model::STATUS_COMPLETED:
                            return 'Завершен';
                            break;

                        case $model::STATUS_CANCELLED:
                            return 'Отменен';
                            break;
                    }
                }
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
