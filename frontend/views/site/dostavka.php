<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

// use common\models\CommonUser;

$this->title = 'Print4you - Оплата и доставка';
// var_dump(CommonUser::getDiscount());
?>

<main class="dostavka">
		<div class="line1">
			<div class="container">
				<h1 class="title">
					Оплата и доставка
				</h1>
				<img src="/img/dostavka-moneybag.png" alt="" class="moneybag">
				<div class="schedule">
					<span class="schedule-title">Расчетный счет:</span> <span class="schedule-num">40802810832260001017</span>
					<span class="schedule-info">
						Наличными или картой можно оплатить, <br>
						сделав заказ на сайте
					</span>
				</div>
			</div>
		</div>

		<!-- LINE4 -->
		<div class="line4">
			<div class="container clearfix">
				<div class="line4-left">
					<img src="/img/line4-postman.png" alt="">
				</div>
				<div class="line4-right">
					<h3>
						Мы работаем
					</h3>
					<h2>
						По всей России!
					</h2>
					<span class="line4-right-text1">
						Ваш заказ доставят прямо к двери в течении <br>
2 часов с момента оформления заказа!
					</span>
					<div class="line4-right-text2">
						<p>
							Стоимость доставки по Санкт-Петербургу в пределах КАД - <br><strong>350 рублей!</strong>
						</p>
						<p>
							За пределами СПб рассчитывается индивидуально. Вы можете <br>
забрать Ваш заказ с нашей студии:
							<a href="#">Контакты</a>
						</p>
						<p>
							Так же мы отправляем ваши заказы по всему миру! <br>
Стоимость доставки по России - <strong>350 рублей</strong> <br>
Остальные страны и города - <strong>450 рублей</strong><br>
Чтобы заказать доставку - Пишите нам или звоните: 
						</p>
						<a href="tel:88129819484" class="line4-right-text2-number">309 28 48</a>
					</div>
					<a href="<?= Url::to(['uslugi/assorty']) ?>" class="line4-right-makeOrder">Заказать</a>
				</div>
			</div>
		</div>
		<!-- END OF LINE4 -->
	</main>