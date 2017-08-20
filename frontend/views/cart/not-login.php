<?php 
use yii\helpers\Url;
use yii\helpers\Html;
?>

<span class="empty-cart">Чтобы сделать заказ войдите или зарегистрируйтесь!</span>
<div class="empty-links-container clearfix">	
	<?= Html::a('Вход', ['#'], [
		'class' => 'empty-link empty-link-left',
		'data-toggle' => 'modal',
		'data-target' => '#loginRegisterModal',
	]) ?>

	<?= Html::a('Регистрация', ['/register/', 'redirect' => Url::to(['/checkout/'], true)], [
		'class' => 'empty-link empty-link-right',
	]) ?>
</div>