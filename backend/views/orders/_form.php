<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Orders;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php // TODO: убрать этот ddlist - нельзя задавать статус таким способом ?>
    <?= $form->field($model, 'order_status')->dropDownList([
            Orders::STATUS_NEW => 'Новый',
            Orders::STATUS_PROCCESSING => 'В обработке',
            Orders::STATUS_COMPLETED => 'Завершен',
            Orders::STATUS_CANCELLED => 'Отменен',
        ])->label('Статус') ?>

    <?= $form->field($model, 'client_name')->textInput()->label('Имя клиента') ?>
    <?= $form->field($model, 'phone')->textInput()->label('Номер телефона клиента') ?>
    <?= $form->field($model, 'address')->textInput()->label('Адрес доставки') ?>
    <?= $form->field($model, 'price')->textInput()->label('Цена') ?>

    <?= $form->field($model, 'manager_id')->textInput()->label('ID менеджера') ?>
    <?= $form->field($model, 'client_id')->textInput()->label('ID клиента') ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true])->label('Комментарий') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
