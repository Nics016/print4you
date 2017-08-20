<?php

/* @var $this yii\web\View */

use frontend\components\ReviewsWidget;
use yii\helpers\Url;

?>
<main class="shelkography sublimation">
		<!-- LINE1 -->
		<div class="line1">
			<div class="container clearfix">
				<h1>
					<span class="first-title">Печать на синтетике</span>
					<span class="second-title">Сублимация</span>
				</h1>
				<div class="line1-left clearfix">
					<div class="line1-left-description">
						
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
					<strong>Внимание!</strong>
					<p>
						Не стоит пробовать совершать сублимационной перенос 
						в домашних условиях с помощью утюга. Для закрепления 
						специальной бумаги нужно оборудование, в домашних 
						условиях его нет, а потому бумага будет давать 
						микроперемещения, что обязательно сделает рисунок 
						смазанным. Из-за неравномерного прижима и прогрева 
						поверхности теряется сочность красок, равномерность 
						цветов.Как правило, после такой сублимации футболка 
						едва ли выдерживает больше 3-4 стирок, далее просто 
						полностью теряет вид.
						<br>
						
					</p>
				</div>


			</div>

			<div class="container clearfix">
				<div class="line1-right" style="margin-top: 20px;">
					<strong>Внимание!</strong>
					<p>
						Не рекомендуем данный вид печати, так как он наносится только на синтетическую ткань.
					</p>
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
					<a href="<?= Url::to(['constructor/']) ?>" class="line4-right-makeOrder">Заказать</a>
				</div>
			</div>
		</div>
		<!-- END OF LINE4 -->

		<?= \frontend\widgets\OurWorksSlider::widget() ?>
	</main>