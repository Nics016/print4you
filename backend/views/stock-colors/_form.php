<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Office;

/* @var $this yii\web\View */
/* @var $model backend\models\StockColors */
/* @var $form yii\widgets\ActiveForm */
$models = Office::find()->asArray()->all();
$map = ArrayHelper::map($models, 'id', 'address');
?>

<div class="stock-colors-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'liters')->textInput(['value' => 0]) ?>

    <?= $form->field($model, 'office_id')->dropDownList($map) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
