<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Print4you - Франшиза';
?>

<main class="franchise">
		<!-- LINE1 -->
		<div class="line1">
			<div class="container">
				<div class="title-line-bg"></div>
				<!-- TITLE-LINE -->
				<div class="title-line clearfix">
					<div class="title-line-left">
						<h2 class="subtitle">
							Печать на футболках print4you
						</h2>
						<h1 class="title">
							Франшиза
						</h1>
					</div>

					<div class="title-line-right">
						<h3>Почему PRINT4YOU?</h3>
						<div class="title-line-right-item clearfix">
							<img src="/img/franchise-dash.png" alt="">
							<span>Развитие бизнеса за 1 год</span>
						</div>
						<div class="title-line-right-item clearfix">
							<img src="/img/franchise-dash.png" alt="">
							<span>Потребительский поток с 1 месяца</span>
						</div>
					</div>
				</div>
				<!-- END OF TITLE-LINE -->

				<img src="/img/franchise-banner.jpg" alt="" class="banner">

				<!-- PLUSES -->
				<div class="pluses">
					<div class="pluses-plus">
						<div class="pluses-plus-title-line clearfix">
							<img src="/img/franchise-01.png" alt="">
							<h3 class="title">
								Инвестиции для регионов <br>
								390.000 тысяч рублей
							</h3>
						</div>
						<div class="pluses-plus-info">
							<ul>
								<li>
									Ежемесячные отчисления - от 20.000 со второго месяца
								</li>	
								<li>Организация - 370.000 тысяч рублей</li>
								<li>Число рабочих мест - 1 место</li>
								<li>Площадь - 15 м2</li>
								<li>Средний чек - 550 рублей</li>
								<li>Чистая прибыль за месяц - 100.000 - 250.000</li>		
							</ul>
						</div>
					</div>

					<div class="pluses-plus">
						<div class="pluses-plus-title-line clearfix">
							<img src="/img/franchise-02.png" alt="">
							<h3 class="title">
								Инвестиции для крупных городов России <br>
								890.000 тысяч рублей
							</h3>
						</div>
						<div class="pluses-plus-info">
							<ul>
								<li>Ежемесячные отчисления - от 50.000 со второго месяца</li>
								<li>Организация - 840.000 тысяч рублей</li>
								<li>Число рабочих мест - 2 место</li>
								<li>Площадь - 40 м2</li>
								<li>Средний чек - 950 рублей</li>
								<li>Чистая прибыль за месяц - 600.000 - 1.500.000</li>	
							</ul>
						</div>
					</div>
				</div>
				<!-- END OF PLUSES -->
			</div>
		</div>
		<!-- END OF LINE1 -->
		<div class="line2">
			<div class="container">
				<div class="request">
					<h2 class="title">
						Заявка на франшизу
					</h2>
					<div class="underline"></div>
					<h3>В поле "примечание" укажите ваш город</h3>

					<div class="contacts-questions-form-wrapper">
						<form method="POST" class="contacts-questions-form clearfix">
							<div class="contacts-questions-form-left">
								<div class="contacts-questions-form-left-line">
									<input type="text" name="client_name" placeholder="Ваше имя">
									<img src="/img/topline-lk.png" alt="">
								</div>
								<div class="contacts-questions-form-left-line">
									<input type="text" name="client_phone" placeholder="Ваш телефон">
									<img src="/img/topline-phone.png" alt="">
								</div>
								<div class="contacts-questions-form-left-line">
									<input type="text" name="client_email" placeholder="Ваш Email">
									<img src="/img/topline-mail.png" alt="">
								</div>
							</div>
							<div class="contacts-questions-form-right">
								<div class="clearfix">
									<textarea name="client_msg" placeholder="Примечание"></textarea>
									<img src="/img/contacts-questions-form-msg.png" alt="">
								</div>
								<input type="submit" value="Отправить">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>