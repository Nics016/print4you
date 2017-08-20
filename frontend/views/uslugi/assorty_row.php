<?php 
use yii\helpers\Html;
use yii\helpers\Url;


?>

<div class="assorty-row" <?= $id_tag === false ? '' : "id='$id_tag'" ?>>


	<a href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>" class="assorty-row-link">
		<div class="assorty-row-image-container">
			<?= Html::img($image, ['alt' => $alt]) ?>
		</div>
		<span class="assorty-product-name"><?= $name ?></span>
	</a>

	<div class="assorty-row-description">
		
		<span class="assorty-row-description-text">
			<?= $description ?>
		</span>
		<a href="<?= Url::to(['constructor/', 'product_id' => $product_id]) ?>" class="assorty-row-description-btn">
			Печать от <?= $count ?> шт.
		</a>
	</div>
</div>