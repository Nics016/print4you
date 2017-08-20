<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<main class="main">
	<div class="line5">
		<div class="container">
			<h1 class="title">Технологии и цены</h1>
			<div class="underline"></div>
			<div class="elements clearfix">
				<div class="elements-item">
					<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-img-container">
						<img src="/img/services-pic1.jpg" alt="" class="elements-item-img">
					</a>
					<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-title">Цифровая печать</a>
					<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-text">(Прямая печать)</a>
					<a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="whiteBtn">
						Подробнее
					</a>
				</div>
				<div class="elements-item">
					<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-img-container">
						<img src="/img/services-pic2.jpg" alt="" class="elements-item-img">
					</a>
					<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-title">Печать плёнкой</a>
					<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-text">(Термоперенос)</a>
					<a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="whiteBtn">
						Подробнее
					</a>
				</div>
				<div class="elements-item">
					<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-img-container">
						<img src="/img/services-pic3.jpg" alt="" class="elements-item-img">
					</a>
					<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-title">Трафаретная печать</a>
					<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-text">(Шелкография)</a>
					<a href="<?= Url::to(['uslugi/shelkography']) ?>" class="whiteBtn">
						Подробнее
					</a>
				</div>
				<div class="elements-item">
					<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-img-container">
						<img src="/img/services-pic4.jpg" alt="" class="elements-item-img">
					</a>
					<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-title">печать на синтетике</a>
					<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-text">(Сублимация)</a>
					<a href="<?= Url::to(['uslugi/sublimation']) ?>" class="whiteBtn">
						Подробнее
					</a>
				</div>
			</div>
		</div>
	</div>
</main>