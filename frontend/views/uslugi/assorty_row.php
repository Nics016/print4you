<?php 
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="assorty-row clearfix">
	<a href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>">
		<?= Html::img($image, ['alt' => $name]) ?>
	</a>
	<div class="assorty-row-description">
		<div class="clearfix">
			<a class='assorty-row-description-title' href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>"><?= $name ?></a>
			<a href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>" class="assorty-row-description-price-link">
				<span class="assorty-row-description-price">
					<?= $price ?></span> 
					<div class="assorty-row-description-rub">	
				</div>
			</a>
		</div>
		<span class="assorty-row-description-text">
			<?= $description ?>
		</span>
		<a href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>" class="assorty-row-description-btn">Печать от <?= $count ?> шт.</a>
	</div>
</div>