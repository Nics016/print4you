<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Orders;
use common\models\OrdersProduct;
use backend\models\User;
use common\models\CommonUser;
use yii\widgets\ActiveForm;
use common\models\ConstructorColors;
use common\models\ConstructorSizes;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $ordersTitle - contains current title (new orders, proccessing orders, etc) */

$this->title = $ordersTitle;
$this->params['breadcrumbs'][] = $this->title;

// Получаем список заказов, чтобы использовать их id в ссылках внутри модалок
$orders = $dataProvider->getModels();

?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
        <p>
            <?= Html::a('Создать заказ', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
    <?php 
        $allowedActions = '{view}';
        if (Yii::$app->user->identity->role == User::ROLE_ADMIN){
            $allowedActions .= ' {update}';
        }
     ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
                            return 'Ожидается подтверждение исполнителя';
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
                'label' => 'Цена со скидкой (руб.)',
                'value' => function($model) {
                    $products = OrdersProduct::find()
                        ->where(['order_id' => $model->id])
                        ->all();
                    $totalPrice = 0;
                    foreach ($products as $product) {
                        $frontPrintData = json_decode($product->front_print_data, true);
                        $productPrice = $product->price;
                        if ($frontPrintData){
                            $productPrice += $frontPrintData['price'];
                        }
                        $productDiscountPrice = Orders::calculateDiscountPrice($product->count * $productPrice, $product->discount_percent);
                        $totalPrice += $productDiscountPrice;
                    }
                    return $totalPrice;
                }
            ],
            [
                'label' => 'Товары',
                'format' => 'html',
                'value' => function($model){
                    $answ = '';
                    $products = OrdersProduct::find()
                        ->where(['order_id' => $model->id])
                        ->all();
                    if ($products){
                        $answ .= '<ol>';
                        foreach($products as $product) {
                            $color = ConstructorColors::findOne(['id' => $product->color_id]);
                            $size = ConstructorSizes::findOne(['id' => $product->size_id]);
                            $liText = $product->count . ' x ' 
                                . $product->name
                                . ' (' . $color->name
                                . ', ' . $size->size . ')';
                            $answ .= '<li>' . $liText . '</li>';
                        } // foreach products
                        $answ .= '</ol>';
                    } // if products

                    return $answ;
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
                    return $user['username'];
                }
            ],
            [
                'label' => 'Курьер',
                'attribute' => '',
                'value' => function($model){
                    $user = $model->getUser($model['courier_id']);
                    return $user['username'];
                }
            ],
            // [
            //     'label' => 'Комментарий',
            //     'attribute' => 'comment',
            // ],
            [
                'label' => 'Дата',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'raw',
                'value' => function($model){
                    // Если статус заказа "новый", то показываем менеджеру кнопку "Принять",
                    // "в обработке" и заказ текущего менеджера, то показываем "Завершить",
                    // админу показываем кнопку "Отменить"
                    
                    // Менеджер
                    $btnAccept = Html::tag('a', 'Принять', ['class' => 'btn btn-info', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'orders/accept', 
                            'id' => $model['id'], 
                            // 'comment' => ''
                        ]),
                    ]);
                    $btnPickCourier = Html::tag('button', 'Назначить курьера', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#pickCourierModal'.$model['id']]);
                    $btnPickExecutor = Html::tag('button', 'Назначить исполнителя', ['class' => 'btn btn-primary', 'data-toggle' => 'modal', 'data-target' => '#pickExecutorModal'.$model['id']]);

                    // Исполнитель
                    $btnAcceptExecutor = Html::tag('button', 'Принять', ['class' => 'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#acceptExecutorModal'.$model['id']]);
                    $btnCompleteExecutor = Html::tag('a', 'Завершить', ['class' => 'btn btn-success', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'orders/complete-executor', 
                            'id' => $model['id']
                        ]),
                    ]);

                    // Курьер
                    $btnAcceptCourier = Html::tag('a', 'Принять', ['class' => 'btn btn-info', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'orders/accept-courier', 
                            'id' => $model['id']
                        ]),
                    ]);
                    $btnComplete = Html::tag('a', 'Завершить', ['class' => 'btn btn-success', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'orders/complete', 
                            'id' => $model['id']
                        ]),
                    ]);

                    // Админ
                    $btnCancel = Html::tag('button', 'Отменить', ['class' => 'btn btn-danger', 'data-toggle' => 'modal', 'data-target' => '#cancelOrderModal'.$model['id']]);
                    

                    $returnBtns = '';
                    // "Принять" у менеджера
                    if ($model->order_status == Orders::STATUS_NEW 
                            && Yii::$app->user->identity->role == User::ROLE_MANAGER){
                        $returnBtns = $btnAccept;
                    } 
                    // "Отменить" у админа
                    elseif ($model->order_status != Orders::STATUS_CANCELLED
                            && Yii::$app->user->identity->role == User::ROLE_ADMIN) {
                        $returnBtns = $btnCancel;
                    } 
                    // "Выбрать исполнителя" и "Выбрать курьера" у менеджера
                    elseif ($model->order_status == Orders::STATUS_PROCCESSING 
                            && Yii::$app->user->identity->role == User::ROLE_MANAGER
                            && $model['manager_id'] == Yii::$app->user->identity->id){
                        if ($model->executor_id == NULL)
                            $returnBtns = $btnPickExecutor . "<br><br>";
                        if ($model->courier_id == NULL)
                            $returnBtns .= $btnPickCourier;
                    } 
                    // "Принять" у исполнителя
                    elseif ($model->order_status == Orders::STATUS_PROCCESSING
                            && Yii::$app->user->identity->role == User::ROLE_EXECUTOR
                            && $model['executor_id'] == Yii::$app->user->identity->id
                            && $model->location == Orders::LOCATION_EXECUTOR_NEW){
                        $returnBtns = $btnAcceptExecutor;
                    }
                    // "Завершить" у исполнителя
                    elseif ($model->order_status == Orders::STATUS_PROCCESSING
                            && Yii::$app->user->identity->role == User::ROLE_EXECUTOR
                            && $model['executor_id'] == Yii::$app->user->identity->id
                            && $model->location == Orders::LOCATION_EXECUTOR_ACCEPTED){
                        $returnBtns = $btnCompleteExecutor;
                    }
                    // "Принять" у курьера
                    elseif ($model->order_status == Orders::STATUS_PROCCESSING
                            && Yii::$app->user->identity->role == User::ROLE_COURIER
                            && $model['courier_id'] == Yii::$app->user->identity->id
                            && $model->location == Orders::LOCATION_COURIER_NEW){
                        $returnBtns = $btnAcceptCourier;
                    }
                    // "Завершить" у курьера
                    elseif ($model->order_status == Orders::STATUS_PROCCESSING
                            && Yii::$app->user->identity->role == User::ROLE_COURIER
                            && $model['courier_id'] == Yii::$app->user->identity->id
                            && $model->location == Orders::LOCATION_COURIER_ACCEPTED){
                        $returnBtns = $btnComplete;
                    }

                    else {
                        $returnBtns = '';
                    }

                    return $returnBtns;
                }
            ],
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $allowedActions,
            ],
        ],
    ]); ?>
</div>


<?php 
//////////////
// МОДАЛКИ  //
//////////////
?>
<?php // Модалки для кнопки "Назначить исполнителя" ?>
<?php foreach($orders as $order): ?>
<!-- Modal -->
<div id="pickExecutorModal<?= $order['id'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Назначить исполнителя</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['orders/pick-executor'], 'method' => 'GET']); ?>
            <?= Html::dropDownList('executor_id', '', User::GetActiveUsersByRole(User::ROLE_EXECUTOR), ['class' => 'form-control']); ?>
            <?= Html::hiddenInput('id', $order['id']); ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<?php endforeach; ?>

<?php // Модалки для кнопки "Назначить курьера" ?>
<?php foreach($orders as $order): ?>
<!-- Modal -->
<div id="pickCourierModal<?= $order['id'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Укажите в комментарии, куда курьеру необходимо доставить заказ, контакты клиента и пр.</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['orders/pick-courier'], 'method' => 'GET']); ?>
            <?= Html::textInput('comment', '', ['class' => 'form-control']); ?>
            <br>
            <?= Html::dropDownList('courier_id', '', User::GetActiveUsersByRole(User::ROLE_COURIER), ['class' => 'form-control']); ?>
            <?= Html::hiddenInput('id', $order['id']); ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<?php endforeach; ?>

<?php // Модалки для кнопки "Принять" у исполнителя ?>
<?php foreach($orders as $order): ?>
<!-- Modal -->
<div id="acceptExecutorModal<?= $order['id'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Принять заказ</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['orders/accept-executor'], 'method' => 'GET']); ?>
            <?php if (Yii::$app->user->identity->role === User::ROLE_EXECUTOR
                && $order['location'] === Orders::LOCATION_EXECUTOR_NEW): ?>
                <?= "Количество краски (л): " . "<br>" . Html::dropDownList('stock_color_id', '', $mapColors, ['class' => 'form-control']) . "<br>" . Html::textInput('liters', '', ['class' => 'form-control']) ?>
                <?= Html::hiddenInput('id', $order['id']); ?>
            <?php endif; ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Принять', ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<?php endforeach; ?>

<?php // Модалки для кнопки "Отменить" ?>
<?php foreach($orders as $order): ?>
<!-- Modal -->
<div id="cancelOrderModal<?= $order['id'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Вы уверены, что хотите отменить этот заказ?</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['orders/cancel'], 'method' => 'GET']); ?>
            <?= Html::textInput('comment', '', ['class' => 'form-control']); ?>
            <?= Html::hiddenInput('id', $order['id']); ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Да, отменить заказ', ['class' => 'btn btn-warning']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<?php endforeach; ?>
