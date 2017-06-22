<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\User;

use common\models\Office;

/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */

// getting offices from db and creating array for ddlist for them
$records = Office::Find()->all();
$offices = [];
foreach ($records as $record){
    $offices[(int)$record['id']] = $record['address'];
}

?>
<div class="user-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => 32, 'minlength' => 3, 'autocomplete' => 'off'])->label('Имя пользователя') ?>
        
       <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'firstname')->textInput() ?>
        <?= $form->field($model, 'secondname')->textInput() ?>
        <?= $form->field($model, 'phone')->textInput() ?>
        <?= $form->field($model, 'address')->textInput() ?>
        <?= $form->field($model, 'password')->passwordInput(['value' => '', 'maxlength' => 32, 'minlength' => 5, 'autocomplete' => 'off'])->label('Пароль') ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off'])->label('Email') ?>

        <?= $form->field($model, 'status')->dropDownList([
            User::STATUS_ACTIVE => 'Активный',
            User::STATUS_DELETED => 'Удален',
        ])->label('Статус') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать клиента' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
    
