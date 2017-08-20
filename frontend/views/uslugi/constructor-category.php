<?php

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
		<h1 class="main-title"><?= $model['name'] ?></h1>
		<span class="category-description"><?= $model['description'] ?></span>

		<div class="category-products clearfix">
			
			<?php 
			for ($x = 0; $x < count($model['categoryProducts']); $x++): 
				$product = $model['categoryProducts'][$x];
				$name = $product['name'];

				for ($y = 0; $y < count($product['colors']); $y++):
					$color = $product['colors'][$y];
					$img = $color['front_image'];
					$product_id = $color['product_id'];
					$alt = $color['img_alt'];
					$color_name = $color['name'];
			?>
			<a class="category-product" href="<?= Url::to(['/constructor/', 'product_id' => $product_id]) ?>">
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