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

					<?php 
					for ($i = 0; $i < count($categories); $i++):
						$id = $categories[$i]['id'];
						$name = $categories[$i]['name'];
						$img = $categories[$i]['img'];
					?>
						<div class="elements-item">
							<a href="<?= Url::to(['constructor/', 'cat_id' => $id]) ?>">
								<img src="<?= $img ?>" alt="">
							</a>
							<a href="<?= Url::to(['constructor/', 'cat_id' => $id]) ?>" class="elements-item-title">
								<?= $name ?>
							</a>
							<a href="<?= Url::to(['constructor/', 'cat_id' => $id]) ?>" class="whiteBtn">
								Подробнее
							</a>
						</div>

					<?php endfor;?>
					
					
				</div>
				<a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn bigBtn">
					Смотреть весь ассортимент <strong> > </strong>
				</a>
			</div>
		</div>
	</main>