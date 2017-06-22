
<?php 
// если нет скидки на продукт
if ($price == $discount_price): 
?>

	<div class="product-price">
		<div class="product-price-count-container">
			<span class="product-price-count"><?= $count ?></span>
			<span class="product-price-value">шт. × 
				<span class="new-product-price"><?= $price ?></span> 
			руб.</span>
		</div>
		<span class="product-price-sum">
			<span class="product-price-sum-value"><?= $count * $price ?></span>
			<span> руб.</span>
		</span>
	</div>

<?php 
// если есть скидка на продукт
else: 
?>
	<div class="product-price">
		<div class="product-price-count-container">
			<span class="product-price-count"><?= $count ?></span>
			<span class="product-price-value">шт. × 
				<span class="new-product-price"><?= $discount_price ?></span> 
			руб.</span>
		</div>
		<span class="product-price-sum text-through-line">
			<span class="product-price-sum-value"><?= $count * $price ?></span>
			<span> руб.</span>
		</span>
		<span class="product-price-sum">
			<span class="product-price-sum-value"><?= $count * $discount_price ?></span>
			<span> руб.</span>
		</span>
	</div>
<?php endif; ?>
