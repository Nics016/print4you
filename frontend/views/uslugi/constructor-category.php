<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $model['name'];

$this->registerMetaTag([
    'name' => 'title',
    'content' => $model['seo_title'] ?? $model['name'],
]);

$this->registerMetaTag([
    'name' => 'description',
    'content' => $model['seo_description'] ?? $model['name'],
]);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => $model['seo_keywords'] ?? $model['name'],
]);

?>

<main id="constructor-category">
	<div class="container">

		<?php if ($model['id'] == 33 || $model['id'] == 35): ?>
				
			<div class="price-image-container">
				<?= Html::img('@web/img/futbolki-price.jpg', [
					'alt' => 'Футболки прайс'
				]) ?>
			</div>

		<?php elseif($model['id'] == 49 ): ?>

			<div class="price-image-container">
				<?= Html::img('@web/img/kruzhki-price.jpg', [
					'alt' => 'Кружки прайс'
				]) ?>
			</div>
		
		<?php else: ?>

			<div class="price-image-container">
				<?= Html::img('@web/img/tekstil-price.jpg', [
					'alt' => 'Текстиль прайс'
				]) ?>
			</div>

		<?php endif; ?>

		<h1 class="main-title"><?= $model['name'] ?></h1>
		<span class="category-description"><?= $model['description'] ?></span>

		<div class="category-products clearfix">
			
			<?php 
			for ($x = 0; $x < count($model['categoryProducts']); $x++): 
				$product = $model['categoryProducts'][$x];
				$name = $product['name'];
				$alias = $product['alias'];

				for ($y = 0; $y < count($product['colors']); $y++):
					$color = $product['colors'][$y];
					$img = $color['front_image'];
					$alt = $color['img_alt'];
					$color_name = $color['name'];
			?>
			<a class="category-product" href="<?= Url::to(['constructor/index', 'alias' => $alias]) ?>">
				<div class="img-container">
					<img src="<?= $img ?>" alt="<?= $alt ?>">
				</div>
				
				<span class="product-title"><?= $name ?></span>

				<span class="product-color">Цвет: <?= $color_name ?></span>

			</a>
			<?php 
				endfor;
			endfor; 
			?>


		</div>

	</div>
</main>