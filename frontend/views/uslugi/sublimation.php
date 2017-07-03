<?php

/* @var $this yii\web\View */

use frontend\components\ReviewsWidget;

$this->title = 'Print4you - Услуги - Сублимация';
?>
<main class="shelkography sublimation">
		<!-- LINE1 -->
		<div class="line1">
			<div class="container clearfix">
				<h2>Печать на синтетике</h2>
				<div class="line1-left clearfix">
					<div class="line1-left-description">
						<h1>Сублимация</h1>
						<p>Наша студия на профессиональном оборудовании 
с правильным температурным режимом и соблюдением 
всех технологий осуществляет сублимационную печать 
на футболках, синтетических тканях. Отличный способ 
нанесения на футболки, которые не боятся термообработки 
(цвета печати не смешиваются с цветами ткани). 
Реально фотографическое качество с очень высокой 
устойчивостью к стирке, солнцу, загрязнениям.</p>
						<p>
							Обращайтесь в нашу студию. Недорого и качественно, 
быстро выполним сублимацию на футболках. 
При необходимости поможем с дизайном и выбором 
футболок под нанесение, осуществим доставку 
готовой продукции.
						</p>
					</div>
				</div>
				<div class="line1-right">
					<h3>Внимание!</h3>
					<p>Не стоит пробовать совершать сублимационной перенос 
в домашних условиях с помощью утюга. Для закрепления 
специальной бумаги нужно оборудование, в домашних 
условиях его нет, а потому бумага будет давать 
микроперемещения, что обязательно сделает рисунок 
смазанным. Из-за неравномерного прижима и прогрева 
поверхности теряется сочность красок, равномерность 
цветов.Как правило, после такой сублимации футболка 
едва ли выдерживает больше 3-4 стирок, далее просто 
полностью теряет вид.</p>
				</div>
			</div>
		</div>
		<!-- END OF LINE1 -->

		<!-- LINE2 -->
		<div class="line2">
			<div class="container">
				<div class="line2-video" onclick="playVideo(1)" id="video-1">
					
				</div>
			</div>
		</div>
		<!-- END OF LINE2 -->

		<?= ReviewsWidget::widget() ?>

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
						<a href="tel:88129819484" class="line4-right-text2-number">981 94 84</a>
					</div>
					<a href="#" class="line4-right-makeOrder">Заказать</a>
				</div>
			</div>
		</div>
		<!-- END OF LINE4 -->

		<!-- LINE5 -->
		<div class="line5">
			<div class="container">
				<img src="/img/line5-tshirt.png" alt="" class="line5-tshirt">
				<h2>Примеры работ</h2>
				<div class="line5-underline"></div>
				<ul class="line5-slider">
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo2.jpg" alt=""></li>
					<li><img src="/img/line5-photo3.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo2.jpg" alt=""></li>
					<li><img src="/img/line5-photo3.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo1.jpg" alt=""></li>
					<li><img src="/img/line5-photo4.jpg" alt=""></li>
					<li><img src="/img/line5-photo2.jpg" alt=""></li>
				</ul>
			</div>
		</div>
		<!-- END OF LINE5 -->
	</main>