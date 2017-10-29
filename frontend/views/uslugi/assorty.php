<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<main class="assorty">
		<div class="line1">
			<div class="container">
				<h1 class="title">Наш ассортимент</h1>
				<div class="underline"></div>
				<div class="assorty-category clearfix">
					<a href="#" class="active">Розница</a>
					<a href="#">Оптом</a>
				</div>
			</div>
		</div>
		<div class="line2-tabs">
			<div class="line2-tabs-tab">
				<div class="line2">
					<div class="assorty-container clearfix" id="simple_price">
						<?php 
						$current_cat_id = null;
						for ($x = 0; $x < count($content); $x++) {
							$item = $content[$x];
							$colors = $item['colors'];
							$product_id = $item['id'];
							$alias = $item['alias'];
							$name = $item['name'];
							$description = $item['description'];

							$id_tag = false;
							$cat_id = $item['category_id'];
							if ($current_cat_id === null || $cat_id != $current_cat_id) {
								$current_cat_id = $cat_id;
								$id_tag = 'cat-' . $cat_id;
							}

							for ($y = 0; $y < count($colors); $y++) {
								$image = $colors[$y]['front_image'];
								$price = $colors[$y]['price'];
								$img_alt = $colors[$y]['img_alt'];

								echo $this->render('assorty_row', [
									'alias' => $alias,
									'name' => $name,
									'image' => $image,
									'count' => 1,
									'price' => $price,
									'description' => $description,
									'id_tag' => $id_tag,
									'alt' => $img_alt,
								]);
							}
						}
						?>
					</div> 
					<!-- END OF .container -->
				</div>
				<!-- END OF .line2 -->
			</div>
			<!-- ./tab -->


			<div class="line2-tabs-tab">
				<div class="line2">
					<div class="assorty-container clearfix" id="gross_price">
						<?php 
						$current_cat_id = null;
						for ($x = 0; $x < count($content); $x++) {
							$item = $content[$x];
							$colors = $item['colors'];
							$alias = $item['alias'];
							$name = $item['name'];
							$description = $item['description'];

							$id_tag = false;
							$cat_id = $item['category_id'];
							if ($current_cat_id === null || $cat_id != $current_cat_id) {
								$current_cat_id = $cat_id;
								$id_tag = 'cat-' . $cat_id;
							}

							for ($y = 0; $y < count($colors); $y++) {
								$image = $colors[$y]['front_image'];
								$gross_price = json_decode($colors[$y]['gross_price'], true);
								$gross_price = $gross_price[0]["price"];
								$img_alt = $colors[$y]['img_alt'];

								echo $this->render('assorty_row', [
									'alias' => $alias,
									'name' => $name,
									'image' => $image,
									'count' => $groos_count,
									'price' => $gross_price,
									'description' => $description,
									'id_tag' => $id_tag,
									'alt' => $img_alt,
								]);
							}
						}
						?>
					</div> 
					<!-- END OF .container -->
				</div>
				<!-- END OF .line2 -->
			</div>
			<!-- ./tab -->
		</div>
		<!-- ./tabs -->
		<div class="container">
			<button id="load-more-assorty">Показать еще</button>
		</div> 
		<input type="hidden" id="assorty-limit" value="<?= $limit ?>">
		<input type="hidden" id="assorty-offset" value="<?= $offset ?>">
	</main>