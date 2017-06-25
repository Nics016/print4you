<?php

use Yii;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\User;
use common\models\CommonUser;
use common\models\Orders;
use backend\models\StockColors;
use common\models\OrdersProduct;
use common\models\ConstructorColors;
use common\models\ConstructorSizes;
use frontend\components\Basket;

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
                'label' => 'Местонахождение заказа',
                'attribute' => 'order_status',
                'value' => function($model){
                    switch($model['location']){
                        case $model::LOCATION_MANAGER_NEW:
                            return 'Ожидает принятия менеджером';
                            break;

                        case $model::LOCATION_MANAGER_ACCEPTED:
                            return 'Менеджер принял заказ';
                            break;

                        case $model::LOCATION_EXECUTOR_NEW:
                            return 'Исполнитель назначен. Ожидается подтверждение исполнителя';
                            break;

                        case $model::LOCATION_EXECUTOR_ACCEPTED:
                            return 'Исполнитель выполняет заказ';
                            break;

                        case $model::LOCATION_COURIER_NEW:
                            return 'Заказ готов. Ожидает подтверждения курьера';
                            break;

                        case $model::LOCATION_COURIER_ACCEPTED:
                            return 'Курьер принял заказ';
                            break;

                        case $model::LOCATION_COURIER_COMPLETED:
                            return 'Заказ доставлен клиенту';
                            break;

                        case $model::LOCATION_EXECUTOR_COMPLETED:
                            return 'Исполнитель завершил выполнение';
                            break;

                    }
                }
            ],
            [
                'label' => 'Цена (руб.)',
                'attribute' => 'price',
                'value' => function($model) {
                    $products = OrdersProduct::find()
                        ->where(['order_id' => $model->id])
                        ->all();
                    $totalPrice = 0;
                    foreach ($products as $product) {
                        $totalPrice += $product->count * $product->price;
                    }
                    return $totalPrice;
                }
            ],
            [
                'label' => 'Цена со скидкой (руб.)',
                'value' => function($model) {
                    $products = OrdersProduct::find()
                        ->where(['order_id' => $model->id])
                        ->all();
                    $totalPrice = 0;
                    foreach ($products as $product) {
                        $productDiscountPrice = Orders::calculateDiscountPrice($product->count * $product->price, $product->discount_percent);
                        $totalPrice += $productDiscountPrice;
                    }
                    return $totalPrice;
                }
            ],
            [
                'label' => 'Менеджер',
                'attribute' => 'manager_id',
                'value' => function($model){
                    $manager = $model->getUser($model['manager_id']);
                    return $manager['username'];
                }
            ],
            [
                'label' => 'Клиент',
                'attribute' => '',
                'value' => function($model){
                    $user = CommonUser::findIdentity($model['client_id']);
                    return $user->username . ' - ' . $user->firstname 
                        . ' ' . $user->secondname;
                }
            ],
            [
                'label' => 'Номер телефона клиента',
                'attribute' => 'phone',
            ],
            [
                'label' => 'Заказанные товары',
                'format' => 'raw',
                'value' => function($model){
                    $answ = '';
                    $products = OrdersProduct::find()
                        ->where(['order_id' => $model->id])
                        ->all();
                    if ($products){
                        $answ .= '<ol>';
                        foreach($products as $product) {
                            $urlFrontImg = OrdersProduct::getImagesLink() . '/' . $product->front_image; 
                            $urlBackImg = OrdersProduct::getImagesLink() . '/' . $product->back_image; 
                            $discountPrice = Orders::calculateDiscountPrice($product->count * $product->price, $product->discount_percent);
                            $color = ConstructorColors::findOne(['id' => $product->color_id]);
                            $size = ConstructorSizes::findOne(['id' => $product->size_id]);

                            $liText = $product->count . ' x ' 
                                . $product->name
                                . ' (' . $color->name
                                . ', ' . $size->size . ')';
                            $liText .= '<br>Цена: ' . $product->price . ' р';
                            $liText .= '<br>Количество: ' . $product->count . ' шт';
                            $liText .= '<br>Сумма: ' . $product->count * $product->price . ' р';
                            if ($discountPrice !== $product->count * $product->price) {
                                $liText .= '<br>Скидка: ' . $product->discount_percent . ' %';
                                $liText .= '<br>Сумма (со скидкой): ' . $discountPrice . ' р';
                            }
                            $liText .= '<br><br>Принт спереди - '
                                . Html::a('Ссылка', $urlFrontImg, [ 'target' => 'blank', 'style' => 'color: purple']);
                            $liText .= '<br>Принт сзади - '
                                . Html::a('Ссылка', $urlBackImg, ['style' => 'color: purple', 'target' => '_blank']);
                            $answ .= '<li><h4>' . $liText . '</h4></li>';
                        } // foreach products
                        $answ .= '</ol>';
                    } // if products

                    return $answ;
                }
            ],
            [
                'label' => 'Затраченные материалы',
                'format' => 'html',
                'value' => function($model){
                    $answ = '';
                    if ($model->stock_color_liters > 0){
                        $answ .= '<h3>Краска<h3>';
                        $answ .= '<h4>';
                        $answ .= StockColors::findOne(['id' => $model->stock_color_id])->name . " - " . $model->stock_color_liters . " л";
                        $answ .= '</h4>';
                    }
                    
                    if ($answ === '')
                        $answ = 'Нет';
                    return $answ;
                }
            ],
            [
                'label' => 'Исполнитель',
                'attribute' => 'executor_id',
                'value' => function($model){
                    $manager = $model->getUser($model['executor_id']);
                    return $manager['username'];
                }
            ],
            [
                'label' => 'Курьер',
                'attribute' => 'courier_id',
                'value' => function($model){
                    $manager = $model->getUser($model['courier_id']);
                    return $manager['username'];
                }
            ],
            [
                'label' => 'Нужна доставка по адресу клиента',
                'attribute' => 'delivery_required',
                'value' => function($model){
                    $answ = $model['delivery_required'] ? "Да" : "Нет";
                    return $answ;
                }
            ],
            /*[
                'label' => 'Оптовый заказ',
                'attribute' => 'is_gross',
                'value' => function($model){
                    $answ = $model['is_gross'] ? "Да" : "Нет";
                    return $answ;
                }
            ],*/
            'address',
            [
                'label' => 'Комментарий',
                'attribute' => 'comment',
            ],
            [
                'label' => 'Дата создания',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
        ],
    ]) ?>

</div>
