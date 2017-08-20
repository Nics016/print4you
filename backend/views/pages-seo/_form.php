<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PagesSeo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-seo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $array = [];

    for ($i = 0; $i < count($model::PAGES_ARRAY); $i++) {
    	$id = $model::PAGES_ARRAY[$i]['id'];
    	$name = $model::PAGES_ARRAY[$i]['name'];
    	$array[$id] = $name;
    }

    echo $form->field($model, 'page_id')->dropDownList($array);
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
