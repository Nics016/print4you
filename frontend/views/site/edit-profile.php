<?php

/* @var $this yii\web\View */
/* @var $model app\common\models\CommonUser */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Print4you - Изменить данные';
?>

<main class="register">
	<div class="container">
		<h1>Изменить данные</h1>
		<div class="row">
			<div class="col-sm-8 col-sm-push-2">
				<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
		            <?= $form->field($model, 'firstname')->textInput() ?>
		            <?= $form->field($model, 'secondname')->textInput() ?>
		            <?= $form->field($model, 'phone')->textInput() ?>
		            <?= $form->field($model, 'email')->textInput() ?>
		            <?= $form->field($model, 'address')->textInput() ?>
		            <?= $form->field($model, 'password')->passwordInput() ?>
		            <br>
		            <div class="form-group" style="margin: 0 auto 50px; display:block">
		                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-info', 'style' => 'margin: 0 auto',]) ?>
		                <?= Html::a('Отмена', ['site/cabinet'], ['class' => 'btn btn-primary']) ?>
		            </div>
		        <?php ActiveForm::end(); ?>
	        </div>
	    </div>
	</div>
</main>