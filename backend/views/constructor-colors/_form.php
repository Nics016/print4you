<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Редактор категорий и размеров';

$css_file_name = Yii::getAlias('@backend') . '/web/css/constructor-colors.css';
$this->registerCssFile('/css/constructor-colors.css?v='. @filemtime($css_file_name));

$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-colors.js';
$this->registerJsFile('/js/constructor-colors.js?v=' . @filemtime($js_file_name), [
    'position' => \yii\web\View::POS_END,
    'depends' => [
        'yii\web\JqueryAsset',
        'yii\validators\ValidationAsset',
    ],
]);

?>

<div class="constructor-colors-form">

    <?php $form = ActiveForm::begin([
        'id' => 'constructor-colors-form'
    ]); ?>

    <?= $form->field($model, 'name')->label('Имя цвета')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'color_value')->label('Значение цвета')->textInput(['type' => 'color']) ?>

    <?= $form->field($model, 'price')->label('Цена')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'frontImage')->label('Лицевая сторона')->fileInput() ?>

    <br>
    <?= $form->field($model, 'gross_price', ['enableClientValidation' => false])->label(false)->textInput([
        'id' => 'gross-price',
        'type' => 'hidden',
    ]) ?>

    <label id="gross-price-label">Оптовая цена</label>

    <div class="gross-prices-container clearfix">
        
        <div class="gross-prices clearfix">
            
            <?php 
            if (!$model->isNewRecord):
                $gross_price = json_decode($model->gross_price, true);
                for ($i = 0; $i < count($gross_price); $i++):
                    $from = $gross_price[$i]['from'];
                    $to = $gross_price[$i]['to'];
                    $price = $gross_price[$i]['price'];
            ?>
                    <div class="gross-price">
                        <div class="gross-top">
                            <input type="number" class="gross-min" min="1" value="<?= $from ?>" placeholder="От">
                            <input type="number" class="gross-max" min="1" value="<?= $to ?>" placeholder="До">
                        </div>
                        <div class="gross-bottom">
                            <input type="number" class="gross-value" min="1" value="<?= $price ?>" placeholder="Цена">
                            <button class="btn btn-danger remove-gross-price">Удалить</button>
                        </div>
                    </div>
            <?php
                endfor;
            endif;
            ?>
            
            <div class="add-btn-container">
                <button class="btn btn-primary glyphicon glyphicon-plus" id="add-gross-price"></button>
            </div>
            

        </div>

        
    </div>
    <br>


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
