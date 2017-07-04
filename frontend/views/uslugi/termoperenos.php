<?php

/* @var $this yii\web\View */

use frontend\components\ReviewsWidget;
use yii\helpers\Url;

$this->title = 'Print4you - Услуги - Термоперенос';
?>
<main class="shelkography termoperenos">
		<!-- LINE1 -->
		<div class="line1">
			<div class="container clearfix">
				<h2>Печать пленкой</h2>
				<div class="line1-left clearfix">
					<div class="line1-left-description">
						<h1>Термоперенос</h1>
						<p>Наша студия печати предлагает услуги качественного термопереноса (печать пленкой) 
изображений, логотипов и других материалов на текстиль, изделия из кожи и кожзаменителей.  <br>
Термоперенос отлично подходит для печати на футболках, в том числе  <br>
при минимальных тиражах. </p>
					</div>
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
		
		<!-- PLUSES -->
		<div class="pluses">
			<h3>Основные преимущества термопереноса:</h3>
			<div class="underline"></div>
			<div class="pluses-elements clearfix">
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus1.png" alt="">
					<h4 class="title">Высокая точность <br> при печати пленкой</h4>
					<p>Термоперенос используется для 
печати на футболках, где требуется 
отличное исполнение рисунка – 
качественно и без искажений 
будут перенесены даже самые 
тонкие линии.</p>
				</div>
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus2.png" alt="">
					<h4 class="title">Качество</h4>
					<p>Качество ограничено лишь 
исходным материалом. Если 
предоставляется фотография, 
то и на текстиль, футболку будет 
перенесен фотографический 
уровень исходного материала.</p>
				</div>
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus3.png" alt="">
					<h4 class="title">Тираж от <br>
1 экземпляра</h4>
					<p>Возможно выполнение даже 
минимальных тиражей</p>
				</div>
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus4.png" alt="">
					<h4 class="title">Доступная цена</h4>
					<p>Сроки и цены приятно удивят</p>
				</div>
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus5.png" alt="">
					<h4 class="title">Работа с <br>
разными тканями</h4>
					<p>Технология термопереноса подходит 
для любых тканей, в том числе и тех, 
для которых не подходят иные методы 
нанесения. Так, например, методом 
термопереноса можно сделать нанесение 
на футболку из льна, холста, сетчатой 
ткани и так далее.</p>
				</div>
				<div class="pluses-elements-item">
					<img src="/img/termoperenos-plus6.png" alt="">
					<h4 class="title">Стойкость <br>
изображения</h4>
					<p>Выдерживает десятки циклов 
стирки</p>
				</div>
			</div>
		</div>
		<!-- END OF PLUSES -->

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
					<a href="<?= Url::to(['uslugi/assorty']) ?>" class="line4-right-makeOrder">Заказать</a>
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