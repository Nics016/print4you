<?php 
use yii\helpers\Html;

$this->title = 'Акции';

?>

<main id="sale">

	<h1 class="sale-main-title">Акции</h1>
	
	<div class="sale-slider-container">
		
		<ul id="sale-slider">
			<li>
				<?= Html::img('@web/assets/images/first-order.jpg') ?>
			</li>
			<li>
				<?= Html::img('@web/assets/images/delivery.jpg') ?>
			</li>
			<li>
				<?= Html::img('@web/assets/images/prizes.jpg') ?>
			</li>
			<li>
				<?= Html::img('@web/assets/images/20_sale.jpg') ?>
			</li>
		</ul>

	</div>

	<div class="sales-container">

		<div class="sale clearfix">
			<div class="sale-image">
				<?= Html::img('@web/assets/images/first-order.jpg') ?>
			</div>
			<div class="sale-info">
				<span class="sale-title">-30% на первый заказ!</span>
				<span class="sale-text">
					Приносите чек или старую футболку которую вы печатали в других компаниях и получайте -30% на розничный заказ или дополнительно -10% от оптового прайса.
					<br>
					<br>
					Главная задача акции, доказать, что лучшая компания по печати на футболках это PRINT4YOU!
				</span>
			</div>
		</div>

		<div class="sale clearfix">
			<div class="sale-image">
				<?= Html::img('@web/assets/images/prizes.jpg') ?>
			</div>
			<div class="sale-info">
				<span class="sale-title">Не покупай ВЕЛОСИПЕД мы тебе его ПОДАРИМ</span>
				<span class="sale-text">
					Теперь, при покупке от 800 рублей, каждый из Вас получает шанс выиграть приятные сюрпризы
					<br>
					Условия конкурса:
					1. Приобрести товар в любой из наших студий, на сумму от 800P;
					2.Сделать репост записи вконтакте
					3. Сохранить карточку участника до завершения розыгрыша!!!
					<br>
					<br>
					Итоги розыгрыша будут 15 июля
				</span>
			</div>
		</div>

		<div class="sale clearfix">
			<div class="sale-image">
				<?= Html::img('@web/assets/images/20_sale_2.jpg') ?>
			</div>
			<div class="sale-info">
				<span class="sale-title">-20% в Субботу и Воскресение</span>
				<span class="sale-text">
					Для наших гостей по выходным дням скидка -20% от 1 штуки. Для получения скидки Вам нужно предъявить скидочную карту и сделать репост записи в нашей группе ВКОНТАКТЕ
					<br>
					<br>
					Скидка распространяется только на наш текстиль
				</span>
			</div>
		</div>

		<div class="sale clearfix">
			<div class="sale-image">
				<?= Html::img('@web/assets/images/sale_design.jpg') ?>
			</div>
			<div class="sale-info">
				<span class="sale-title">Бесплатный дизайн для Вас</span>
				<span class="sale-text">
					В наших студиях Вам помогут доработать или разработать дизайн для печати вашего рисунка,надписи - БЕСПЛАТНО до 15 минут!
					<br>
					<br>
					Акция действует во всех студиях печати!
				</span>
			</div>
		</div>

		<div class="sale clearfix">
			<div class="sale-image">
				<?= Html::img('@web/assets/images/delivery.jpg') ?>
			</div>
			<div class="sale-info">
				<span class="sale-title">Бесплатная доставка при заказе от 25.000 тыс. руб.</span>
				<span class="sale-text">
					Для оптовых клиентов - Бесплатная доставка! Чтобы получить бесплатную доставку Вашего заказа, его сумма должна составить 25.000 тыс. руб.
				</span>
			</div>
		</div>

	</div>
	
	<span class="about-sales">Так же вы можете присылать свои пожелания по скидкам и акциям нам на почту - info@print4you.su</span>

</main>