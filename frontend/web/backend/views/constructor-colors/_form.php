<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="constructor-colors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color_value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'front_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'back_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sizes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
