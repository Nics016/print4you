<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
        <input type="text" style="display:none">
        <input type="password" style="display:none">

        <?= $form->field($model, 'username')->textInput(['maxlength' => 32, 'minlength' => 3, 'autocomplete' => 'off'])->label('Имя пользователя') ?>

        <?= $form->field($model, 'password')->passwordInput(['value' => '', 'maxlength' => 32, 'minlength' => 5, 'autocomplete' => 'off'])->label('Пароль') ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off'])->label('Email') ?>

        <?= $form->field($model, 'role')->dropDownList([
            User::ROLE_CLIENT => 'Клиент',
            User::ROLE_MANAGER => 'Менеджер',
            User::ROLE_ADMIN => 'Администратор',
        ])->label('Роль') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать пользователя' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
    
