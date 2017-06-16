<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="constructor-colors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->label('Имя цвета')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'color_value')->label('Значение цвета')->textInput(['type' => 'color']) ?>

    <?= $form->field($model, 'frontImage')->label('Лицевая сторона')->fileInput() ?>

    <?= $form->field($model, 'backImage')->label('Задняя сторона')->fileInput() ?>
    
    <?php 
    $checkboxes = [];

    for ($i = 0; $i < count($sizes); $i++) {
        $checkboxes["" . $sizes[$i]['id'] .""] = $sizes[$i]['size'];
    }
    echo $form->field($model, 'colorSizes')->checkboxList($checkboxes);
    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
