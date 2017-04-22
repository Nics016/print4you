<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\Orders;
use backend\models\User;
use yii\widgets\ActiveForm;
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
                'label' => 'Действие',
                'format' => 'raw',
                'value' => function($model){
                    // Если статус заказа "новый", то показываем менеджеру кнопку "Принять",
                    // "в обработке" и заказ текущего менеджера, то показываем "Завершить",
                    // админу показываем кнопку "Отменить"
                    $acceptBtn = Html::tag('button', 'Принять', ['class' => 'btn btn-info', 'data-toggle' => 'modal', 'data-target' => '#acceptOrderModal'.$model['id']]);
                    $cancelBtn = Html::tag('button', 'Отменить', ['class' => 'btn btn-danger', 'data-toggle' => 'modal', 'data-target' => '#cancelOrderModal'.$model['id']]);
                    $completeBtn = Html::tag('a', 'Завершить', ['class' => 'btn btn-success', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'orders/complete', 
                            'id' => $model['id']
                        ]),
                    ]);

                    if ($model->order_status == Orders::STATUS_NEW 
                            && Yii::$app->user->identity->role == User::ROLE_MANAGER){
                        return $acceptBtn;
                    } elseif ($model->order_status != Orders::STATUS_CANCELLED
                            && Yii::$app->user->identity->role == User::ROLE_ADMIN) {
                        return $cancelBtn;
                    } elseif ($model->order_status == Orders::STATUS_PROCCESSING 
                            && Yii::$app->user->identity->role == User::ROLE_MANAGER
                            && $model['manager_id'] == Yii::$app->user->identity->id){
                        return $completeBtn;
                    } else {
                        return '';
                    }
                }
            ],
            // 'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $allowedActions,
            ],
        ],
    ]); ?>
</div>

<?php // Модалки для кнопки "Принять" ?>
<?php foreach($orders as $order): ?>
<!-- Modal -->
<div id="acceptOrderModal<?= $order['id'] ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Укажите в комментарии, к какому дню и времени данный заказ будет готов</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(); ?>
            <?= Html::textInput('comment-name', '', ['class' => 'form-control', 'id' => 'comment-id'.$order['id']]); ?>
        <?php ActiveForm::end(); ?>
        <a href="<?= Yii::$app->urlManager->createUrl(['orders/accept', 'id' => $order['id'], 'comment' => '']) ?>" class="btn btn-info" id="accept-link<?= $order['id'] ?>">Принять заказ</a>
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
        <a href="<?= Yii::$app->urlManager->createUrl(['orders/cancel', 'id' => $order['id']]) ?>" class="btn btn-danger">Да, отменить заказ</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<?php endforeach; ?>

<script>
    // Скрипт добавления текста комментария в ссылку (GET-запрос)
    // при нажатии на кнопку "принять"
    var ordersIds = [];
    var allowSubmit = false;

    <?php foreach ($orders as $order): ?>
        ordersIds.push(<?= $order['id'] ?>)
    <?php endforeach; ?>
    $(document).ready(function(){
        for(var i = 0; i < ordersIds.length; i++){
            jQuery('#accept-link' + ordersIds[i]).bind('click', function (event) {
                $("#click-me-id").click();
                event.preventDefault();
                var oldHref = $(this).attr('href');
                var commentText = $(this).parent().children("form").children("input[type=text]").val();
                var newHref = oldHref + commentText;
                window.location = newHref;
            });
        }
    });
    
</script>