<?php

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $offices array of string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Print4you - Новый заказ';
?>

<main class="register">
	<div class="container">
		<h1>Оформление заказа</h1>
		<div class="row">
			<div class="col-sm-8 col-sm-push-2">
				<?php $form = ActiveForm::begin(); ?>
		            <?= $form->field($model, 'client_name')->textInput(['autofocus' => true, 'placeholder' => 'Иван Иванов']) ?>
		            <?= $form->field($model, 'phone')->textInput(['placeholder' => '9156667788']) ?>
		            <?= $form->field($model, 'delivery_required')->checkBox() ?>
		            <?= $form->field($model, 'address')->textInput(['placeholder' => 'г. Москва, пр-т Вернадского, д. 78, кв. 123']) ?>
		            <?= $form->field($model, 'delivery_office_id')->dropDownList($offices)->label('Адрес самовывоза') ?>
		            <?= $form->field($model, 'comment')->textArea() ?>
		            <br>
		            <div class="form-group" style="margin: 0 auto 50px; display:block">
		                <?= Html::submitButton('Оформить', ['class' => 'btn btn-success', 'style' => 'margin: 0 auto',]) ?>
		            </div>
		        <?php ActiveForm::end(); ?>
	        </div>
	    </div>
	</div>
</main>