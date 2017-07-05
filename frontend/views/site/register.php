<?php

/* @var $this yii\web\View */
/* @var $model app\common\models\CommonUser */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Print4you - Регистрация';
?>

<main class="register">
	<div class="container">
		<h1>Регистрация</h1>
		<div class="row">
			<div class="col-sm-8 col-sm-push-2">
				<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
		            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
		            <?= $form->field($model, 'password')->passwordInput() ?>
		            <?= $form->field($model, 'email')->textInput(['placeholder' => 'info@print4you.su']) ?>
		            <?= $form->field($model, 'firstname')->textInput(['placeholder' => 'Иван']) ?>
		            <?= $form->field($model, 'secondname')->textInput(['placeholder' => 'Иванов']) ?>
		            <?= $form->field($model, 'phone')->textInput(['placeholder' => '9127776655']) ?>
		            <?= $form->field($model, 'address')->textInput(['placeholder' => 'СПб, м. Гостиный двор, Набережная реки Фонтанки, 38']) ?>
		            <br>
		            <div class="form-group" style="margin: 0 auto 50px; display:block">
		                <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'style' => 'margin: 0 auto',]) ?>
		            </div>
		        <?php ActiveForm::end(); ?>
	        </div>
	    </div>
	</div>
</main>