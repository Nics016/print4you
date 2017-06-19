<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\components\Basket;

$this->title = 'Корзина';

// common style.css
$css_file_name = Yii::getAlias('@frontend') . '/web/css/cart.css';
$this->registerCssFile('/css/cart.css?v='. filemtime($css_file_name));

// common cart.js
$js_file_name = Yii::getAlias('@frontend') . '/web/js/cart.js';
$this->registerJsFile('/js/cart.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => [
		'frontend\assets\jQueryAsset'
	],
])

?>

<div class="container" id="cart-main">
	
	<?php if (empty($basket)): ?>
		<?= $this->render('empty_cart')?>
	<?php else: ?>
	
	<h3 class="your-order">Ваш заказ: </h3>

	<?php
	// для удобства переформируем массива размеров (такой вид используется при рендере продукта товара их конструктора)
	$constructor_sizes = ArrayHelper::index($constructor_sizes, 'id');

	for ($i = 0; $i < count($basket); $i++) {
		if ($basket[$i]['product_type'] == Basket::PRODUCT_CONSTRUCTOR_TYPE) {

			echo $this->render('constructor_products', [
				'product' => $basket[$i],
				'sizes' => $constructor_sizes,
			]);
		}
	}
	?>
	<div id="checkout-container" class="clearfix">
		<?= $this->render('checkout_price', [
			'basket_price' => $basket_price,
			'discount' => $discount,
		]) ?>
	</div>	
	<?php endif; ?>
</div>