<?php 
use yii\helpers\Url;
?>

<div class="full-price-container">
	<span class="checkout-label">Итог:</span>

	<?php if ($discount > 0): ?>
		<span class="full-price price-line-through">
			<span id="full-price"><?= $basket_price ?></span> руб.
		</span>
		<span class="discount-price">
			<span id="discount-price">
				<?= $basket_price / 100 * (100 - $discount)?>	
			</span> руб.
		</span>

	<?php else: ?>
		<span class="full-price">
			<span id="full-price"><?= $basket_price ?></span> руб
		</span>

	<?php endif; ?>

</div>
<a href="<?= Url::to(['checkout']) ?>" class="checkout-link">Оформить</a>