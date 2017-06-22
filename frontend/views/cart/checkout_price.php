<?php 
use yii\helpers\Url;
?>

<div class="full-price-container">
	<span class="checkout-label">Итог:</span>

	<span class="full-price">
		<span id="full-price"><?= $basket_price ?></span> руб
	</span>

</div>
<a href="<?= Url::to(['checkout']) ?>" class="checkout-link">Оформить</a>