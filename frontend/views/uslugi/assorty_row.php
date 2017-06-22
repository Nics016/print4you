<?php 
use yii\helpers\Html;
?>

<div class="assorty-row clearfix">
	<a href="#">
		<?= Html::img($image, ['alt' => $name]) ?>
	</a>
	<div class="assorty-row-description">
		<div class="clearfix">
			<a class='assorty-row-description-title' href="#"><?= $name ?></a>
			<a href="#" class="assorty-row-description-price-link">
				<span class="assorty-row-description-price">
					<?= $price ?></span> 
					<div class="assorty-row-description-rub">	
				</div>
			</a>
		</div>
		<span class="assorty-row-description-text">
			<?= $description ?>
		</span>
		<a href="#" class="assorty-row-description-btn">Печать от <?= $count ?> шт.</a>
	</div>
</div>