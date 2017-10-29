<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$css_file_name = Yii::getAlias('@backend') . '/web/css/constructor-print-prices.css';
$this->registerCssFile('/css/constructor-print-prices.css?v='. @filemtime($css_file_name));

$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-print-prices.js';
$this->registerJsFile('/js/constructor-print-prices.js?v=' . @filemtime($js_file_name), [
    'position' => \yii\web\View::POS_END,
    'depends' => [
        'yii\web\JqueryAsset',
        'yii\validators\ValidationAsset',
    ],
]);
?>

<div class="constructor-print-prices-form">

    <?php $form = ActiveForm::begin([
        'id' => 'constructor-prices-form'
    ]); ?>
    
    <?php 
    // сформируем dropdow для типа печати
    $types_dropdown = [];
    for ($i = 0; $i < count($types); $i++) 
        $types_dropdown[$types[$i]['id']] = $types[$i]['name'];

    echo $form->field($model, 'type_id')->label('Тип печати')->dropDownList($types_dropdown);

    // сформируем dropdow для материала товара
    $materials_dropdown = [];
    for ($i = 0; $i < count($materials); $i++) 
        $materials_dropdown[$materials[$i]['id']] = $materials[$i]['name'];

    echo $form->field($model, 'material_id')->label('Материал товара')->dropDownList($materials_dropdown);

    // сформируем dropdow для размера печати
    $sizes_dropdown = [];
    for ($i = 0; $i < count($sizes); $i++) 
        $sizes_dropdown[$sizes[$i]['id']] = $sizes[$i]['name'];

    echo $form->field($model, 'size_id')->label('Размер печати')->dropDownList($sizes_dropdown);
    ?>
    
    <br>
    <?= $form->field($model, 'gross_price', ['enableClientValidation' => false])->label(false)->textInput([
        'id' => 'gross-price',
        'type' => 'hidden',
    ]) ?>

    <?= $form->field($model, 'gross_price_white', ['enableClientValidation' => false])->label(false)->textInput([
        'id' => 'gross-price2',
        'type' => 'hidden',
    ]) ?>


    <?= $form->field($model, 'price')->label('Розничная цена')->textInput(['autocomplete' => 'off']) ?>

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

    <label id="gross-price-label2">Оптовая цена (белый текстиль)</label>

    <div class="gross-prices-container2 clearfix">
        
        <div class="gross-prices2 clearfix">
            
            <?php 
            if (!$model->isNewRecord):
                $gross_price_white = json_decode($model->gross_price_white, true);
                for ($i = 0; $i < count($gross_price_white); $i++):
                    $from = $gross_price_white[$i]['from'];
                    $to = $gross_price_white[$i]['to'];
                    $price = $gross_price_white[$i]['price'];
            ?>
                    <div class="gross-price2">
                        <div class="gross-top">
                            <input type="number" class="gross-min2" min="1" value="<?= $from ?>" placeholder="От">
                            <input type="number" class="gross-max2" min="1" value="<?= $to ?>" placeholder="До">
                        </div>
                        <div class="gross-bottom">
                            <input type="number" class="gross-value2" min="1" value="<?= $price ?>" placeholder="Цена">
                            <button class="btn btn-danger remove-gross-price2">Удалить</button>
                        </div>
                    </div>
            <?php
                endfor;
            endif;
            ?>
            
            <div class="add-btn-container2">
                <button class="btn btn-primary glyphicon glyphicon-plus" id="add-gross-price2"></button>
            </div>
            

        </div>

        
    </div>
    <br>

    <?= $form->field($model, 'min_count')->label('Минимальное количество товара')->textInput(['autocomplete' => 'off']) ?>


    <?= $form->field($model, 'color')->label('Цветность')->textInput(['autocomplete' => 'off']) ?>
    
    <?php 
    $checkboxes = [];

    for ($i = 0; $i < count($attendances); $i++) {
        $checkboxes["" . $attendances[$i]['id'] .""] = $attendances[$i]['name'];
    }
    echo $form->field($model, 'priceAttendances')->checkboxList($checkboxes);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
