<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Print4you - Услуги - Ассортимент';
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
					<div class="container" id="simple_price">
						<?php 
							for ($i = 0; $i < count($content); $i++) {
								$item = $content[$i];
								echo $this->render('assorty_row', [
									'name' => $item['name'],
									'image' => $item['firstColor']['image'],
									'count' => 1,
									'price' => $item['firstColor']['price'],
									'description' => $item['description'],
								]);
							}
						?>
					</div> 
					<!-- END OF .container -->
				</div>
				<!-- END OF .line2 -->
			</div>
			<!-- ./tab -->

<!-- ------------------------------------ -->

			<div class="line2-tabs-tab">
				<div class="line2">
					<div class="container" id="gross_price">
						<?php 
							for ($i = 0; $i < count($content); $i++) {
								$item = $content[$i];
								echo $this->render('assorty_row', [
									'name' => $item['name'],
									'image' => $item['firstColor']['image'],
									'count' => $groos_count,
									'price' => $item['firstColor']['gross_price'],
									'description' => $item['description'],
								]);
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