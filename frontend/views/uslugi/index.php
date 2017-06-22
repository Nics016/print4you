<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Print4you - Услуги';
?>

<main class="main">
		<div class="line5">
			<div class="container">
				<h1 class="title">Наши услуги</h1>
				<div class="underline"></div>
				<div class="elements clearfix">
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/cifrovaya']) ?>"><img src="/img/services-pic1.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-title">Цифровая печать</a>
						<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-text">(Прямая печать)</a>
						<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/termoperenos']) ?>"><img src="/img/services-pic2.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-title">Печать плёнкой</a>
						<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-text">(Термоперенос)</a>
						<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/shelkography']) ?>"><img src="/img/services-pic3.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-title">Трафаретная печать</a>
						<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-text">(Шелкография)</a>
						<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/sublimation']) ?>"><img src="/img/services-pic4.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-title">печать на синтетике</a>
						<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-text">(Сублимация)</a>
						<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic5.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Футболки мужские</a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic6.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Футболки женские</a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic7.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Свитшоты <br> и толстовки мужские</a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
					<div class="elements-item">
						<a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic8.jpg" alt=""></a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Свитшоты <br> и толстовки женские</a>
						<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
							Подробнее
						</a>
					</div>
				</div>
				<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn bigBtn">
					Смотреть весь ассортимент <strong> > </strong>
				</a>
			</div>
		</div>
	</main>