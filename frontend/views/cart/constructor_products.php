<?php
use yii\helpers\Html;
use frontend\components\Basket;
?>


<div class="constructor-product-row clearfix">
	
	<input type="hidden" class="product-id" value="<?= $product['id'] ?>">

	<div class="remove-icon-container">
		<?= Html::img('@web/img/remove-cart-item.png', [
			'alt' => 'Удалить товар из корзины'
		]) ?>
	</div>

	<div class="constructor-product-images clearfix">
		<div class="constructor-product-image">
			<?= Html::img($product['front_image'], [
				'alt' => 'Лицевая сторона'
			]) ?>
		</div>

		<div class="constructor-product-image">
			<?= Html::img($product['back_image'], [
				'alt' => 'Обратная сторона'
			]) ?>
		</div>
	</div>

	<div class="constructor-product-meta-container">
		<span class="constructor-product-name"><?= $product['name'] ?></span>

		<div class="constructor-product-meta clearfix">
			<span class="constructor-product-meta-label">Цвет: </span>
			<div class="constructor-product-meta-content">
				<span class="constructor-product-value"><?= $product['color'] ?></span>
			</div>		
		</div>

		<div class="constructor-product-meta clearfix">
			<span class="constructor-product-meta-label">Количество: </span>
			<div class="constructor-product-meta-content">
				<div class="product-count-container">
					<button class="pop-product">-</button>
					<input class="product-count" type="number" 
							min="<?= Basket::PRODUCT_MIN_COUNT ?>" 
							max="<?= Basket::PRODUCT_MAX_COUNT ?>" 
							value="<?= $product['count'] ?>"
					/>
					<button class="push-product">+</button>
				</div>		
			</div>
		</div>

		<div class="constructor-product-meta clearfix">
			<span class="constructor-product-meta-label">Размер: </span>
			<div class="constructor-product-meta-content">
				<select class="constructor-product-sizes">
					<?php 
					for($i = 0; $i < count($product['avaliable_sizes']); $i++):
						$size_id = $product['avaliable_sizes'][$i]['size_id'];
						$size_name = $sizes[$size_id]['size'];
						$is_selected = $size_id == $product['current_size'] ? 'selected' : false;
					?>
						<option value="<?= $size_id ?>" <?= $is_selected ?>> <?= $size_name ?></option>
					<?php endfor; ?>
				</select>
			</div>
		</div>

	</div>

	<div class="product-price-container">
		<?= $this->render('constructor_product_price', [
			'price' => $product['price'],
			'discount_price' => $product['discount_price'],
			'count' => $product['count'],
		]) ?>
	</div>

	<div class="product-row-overlay-container remove-product-container">
		<div class="product-overlay-remove-container">
			<span class="product-remove-title">Точно удалить товар?</span>
			<div class="product-remove-btns clearfix">
				<button class="remove-product remove-product-left" data-action="remove">Да</button>
				<button class="remove-product remove-product-right" data-action="close">Нет</button>
	
			</div>
		</div>
	</div>

	<div class="product-row-overlay-container loading-product-container">
		<div class="product-overlay-loading-container">
			<div class="loading-icon-container">
				<?= Html::img('@web/img/cart-loading-icon.png', [
					'alt' => 'Загрузка, подождите'
				]) ?>
			</div>
			<span class="loading-text">Меняем цвет, подождите...</span>
		</div>
	</div>

</div>