<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorProducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="constructor-products-form">
    
    <?php 
    if (!$model->isNewRecord):
        $image = $model::getSmallImagesLink() . '/' . $model->small_image;
    ?>
        <img src="<?= $image ?>" width="320" alt="">
        <br>
        <br>
    <?php endif;?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->label('Имя')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'description')->label('Описание')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'imageFile')->label('Картинка')->fileInput() ?>

    <?= $form->field($model, 'print_offset_x')->label('Отступ принта слева')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'print_offset_y')->label('Отступ принта сверху')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'print_width')->label('Ширина принта')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'print_height')->label('Высота принта')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'is_published')->label('Опубликовать?')->checkbox() ?>
    
    <?php 
    $dropdown_categories  = [];

    for ($i = 0; $i < count($categories); $i++)
        $dropdown_categories[$categories[$i]['id']] = $categories[$i]['name'];

    echo $form->field($model, 'category_id')->label('Категория')->dropDownList($dropdown_categories);

    ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
