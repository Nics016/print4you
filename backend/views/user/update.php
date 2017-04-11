<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Обновить пользователя: ' . $model->id;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    	<div class="user-form">

	    <?php $form = ActiveForm::begin([
	    ]); ?>

		    <?= $this->render('_user', [
		        'user' => $model,
		        'form' => $form
		    ]) ?>

		<div class="form-group">
	        <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
