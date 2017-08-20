<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<main class="main">
	<div class="line5">
		<div class="container">
			<h1 class="title">Текстиль</h1>
			<div class="underline"></div>
			<div class="elements clearfix">

			<?php 
			for ($i = 0; $i < count($categories); $i++):
				$id = $categories[$i]['id'];
				if ($id == $skip_id) continue;
				$name = $categories[$i]['name'];
				$img = $categories[$i]['img'];
				$alt = $categories[$i]['img_alt'];
			?>
				<div class="elements-item">
					<a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="elements-item-img-container">
						<img src="<?= $img ?>" alt="<?= $alt ?>" class="elements-item-img">
					</a>
					<a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="elements-item-title">
						<?= $name ?>
					</a>
					<a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="whiteBtn">
						Подробнее
					</a>
				</div>

			<?php endfor;?>

			</div>
		</div>
	</div>
</main>