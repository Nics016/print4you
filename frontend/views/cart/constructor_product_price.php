
<?php 
// если нет скидки на продукт
if ($price == $discount_price): 
?>

	<div class="product-price">
		<div class="product-prices">
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

		<div class="about-price-block">
			<span class="about-price-span-label">Цена за товар</span>
			<span class="about-price-span-value">1шт. × <?= $product_price ?> руб.</span>

			<span class="about-price-span-label">Цена за печать:</span>
	
			<span class="about-price-span-value">Лицевая сторона:</span>
			<span class="about-price-span-value">1шт. × <?= +$front_print_price ?> руб.</span>

			<span class="about-price-span-value">Обратная сторона:</span>
			<span class="about-price-span-value">1шт. × <?= +$back_print_price ?> руб.</span>

			<?php 
			for ($i = 0; $i < count($additional_sides); $i++): 
				$side_name = $additional_sides[$i]['side_name'];
				$print_price = $additional_sides[$i]['print_price'];
			?>
				<span class="about-price-span-value"><?= $side_name ?>:</span>
				<span class="about-price-span-value">1шт. × <?= +$print_price ?> руб.</span>
			<?php endfor; ?>


		</div>

		<span class="about-price" data-action="open">Подробнее о цене</span>
	</div>
	

<?php 
// если есть скидка на продукт
else: 
?>
	<div class="product-price">
		<div class="product-prices">
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

		<div class="about-price-block">
			<span class="about-price-span-label">Цена за товар</span>
			<span class="about-price-span-value">1шт. × <?= $product_price ?> руб.</span>

			<span class="about-price-span-label">Цена за печать:</span>
	
			<span class="about-price-span-value">Лицевая сторона:</span>
			<span class="about-price-span-value">1шт. × <?= +$front_print_price ?> руб.</span>

			<span class="about-price-span-value">Обратная сторона:</span>
			<span class="about-price-span-value">1шт. × <?= +$back_print_price ?> руб.</span>

			<?php 
			for ($i = 0; $i < count($additional_sides); $i++): 
				$side_name = $additional_sides[$i]['side_name'];
				$print_price = $additional_sides[$i]['print_price'];
			?>
				<span class="about-price-span-value"><?= $side_name ?>:</span>
				<span class="about-price-span-value">1шт. × <?= +$print_price ?> руб.</span>
			<?php endfor; ?>

		</div>

		<span class="about-price" data-action="open">Подробнее о цене</span>
	</div>
<?php endif; ?>
