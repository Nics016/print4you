<?php

/* @var $this yii\web\View */
/* @var $model app\common\models\CommonUser */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Print4you - Спасибо за заказ';
?>

<main class="register">
	<h1>
		Ваш заказ был создан успешно
	</h1>

	<h2>
		Вы можете отслеживать статус вашего заказа в <strong><a href="<?= Url::to(['site/cabinet']) ?>">личном кабинете</a></strong>
	</h2>
</main>