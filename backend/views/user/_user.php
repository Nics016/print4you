<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

    <?= $form->field($user, 'username')->textInput(['maxlength' => 32, 'minlength' => 3])->label('Имя пользователя') ?>

    <?= $form->field($user, 'password')->passwordInput(['maxlength' => 32, 'minlength' => 5])->label('Пароль') ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => true])->label('Email') ?>

    <?= $form->field($user, 'role')->textInput()->label('Client, manager или administrator') ?>
    
