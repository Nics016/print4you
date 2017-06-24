<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StockRequests */
/* @var $form yii\widgets\ActiveForm */
/* @var $mapOffices */
?>

<div class="stock-requests-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'office_id')->dropDownList($mapOffices) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
