<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

// use common\models\CommonUser;

?>

<main class="dostavka">
		<div class="line1">
			<div class="container">
				<h1 class="title">
					Оплата и доставка
				</h1>
				<!-- <img src="/img/dostavka-moneybag.png" alt="" class="moneybag"> -->
				
				<div class="schedule">
					<h2 class="schedule-title">Наличные:</h2>
					
					<div class="shedule-content clearfix">

						<div class="shedule-left">
							<img src="/img/oplata-money.jpg" class="schedule-img-icon" alt="oplata-money">
						</div>
						
						<div class="shedule-right">

							<span class="schedule-info">
								Вы можете оплатить свой заказ за наличные.
								<br> 
								Для этого нужно приехать к нам в одну из студий и оплатить Ваш заказ!
								<br>
								Заказ будет передан в работу после оплаты!
								<br>
								<span class="schedule-link"><?= Html::a('Контакты', ['site/contacts']) ?></span>
							</span>

						</div>
					</div>

					
				</div>

				<div class="schedule">
					<h2 class="schedule-title">Перевод на карту Сбербанка:</h2>

					<div class="shedule-content clearfix">

						<div class="shedule-left">
							<img src="/img/oplata-sberbank.jpg" class="schedule-img-icon" alt="oplata-sberbank">
						</div>
						
						<div class="shedule-right">
							<span class="schedule-info">
								Если у вас есть карта сбербанка то вы можете перевести сумму вашего заказа на наш номер карты!
								<br> 
								<span class="cart-number">НОМЕР КАРТЫ - 5469 5500 2457 4003</span>
								<br>
								Получатель - Антонов Игорь Валентинович
								<br>
								Укажите в комментарии номер вашего заказа!
							</span>
						</div>

					</div>
				</div>

				<div class="schedule">
					<h2 class="schedule-title">Реквизиты для безналичной оплаты:</h2>

					<div class="shedule-content clearfix">

						<div class="shedule-left">
							<img src="/img/oplata-bank.jpg" class="schedule-img-icon" alt="">
						</div>

						<div class="shedule-right">
							<table class="schedule-table">
								<tbody>
									<tr>
										<td>Наименование</td>
										<td>ИП АНТОНОВ ИГОРЬ ВАЛЕНТИНОВИЧ</td>
									</tr>

									<tr>
										<td>Юридический адрес</td>
										<td>195297, РОССИЯ, САНКТ-ПЕТЕРБУРГ, ПР-КТ СУЗДАЛЬСКИЙ, 83</td>
									</tr>

									<tr>
										<td>ИНН</td>
										<td>780437034628</td>
									</tr>

									<tr>
										<td>ОГРН</td>
										<td>316784700148639</td>
									</tr>

									<tr>
										<td>КПП</td>
										<td></td>
									</tr>

									<tr>
										<td>Расчетный счет</td>
										<td>40802810600000149474</td>
									</tr>

									<tr>
										<td>Банк</td>
										<td>АО «Тинькофф Банк»</td>
									</tr>

									<tr>
										<td>Юридический адрес <br>банка</td>
										<td>Москва, 123060, 1-й Волоколамский проезд, д. 10, стр. 1</td>
									</tr>

									<tr>
										<td>Корр. счет банка</td>
										<td>0101810145250000974</td>
									</tr>

									<tr>
										<td>ИНН банка</td>
										<td>7710140679</td>
									</tr>
								</tbody>
							</table>

							<span class="schedule-info">
								Наличными или картой можно оплатить, <br>
								сделав заказ на сайте
							</span>
						</div>
					</div>
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
					<a href="<?= Url::to(['constructor/']) ?>" class="line4-right-makeOrder">Заказать</a>
				</div>
			</div>
		</div>
		<!-- END OF LINE4 -->
	</main>