<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Создать пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="user-form">

	    <?php $form = ActiveForm::begin([
	    ]); ?>

		    <?= $this->render('_user', [
		        'user' => $model,
		        'form' => $form
		    ]) ?>

		<div class="form-group">
	        <?= Html::submitButton('Создать пользователя', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
