<?php 
use common\models\Orders;
use yii\helpers\Html;

$this->title = "Оплата заказа №$order_id ";
?>

<div class="container">
	
	<h1 class="total-pay-title"><?= Html::encode($this->title) ?></h1>

	<span class="total-pay">
		Итого к оплате:
		<span class="price"><?= $order_price ?> руб.</span>
		<?php if ($delivery_price > 0): ?>
			<span class="deliver-price"> + <?= $delivery_price ?> руб. за доставку</span>
		<?php endif; ?>
		<br>
		<br>
		<div class="payment-info">
			<span class="info">
				Комиссия с оплаты составляет 7%.
			</span>
		</div>
		
	</span>

	<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="order-pay-form">  

		<input type="hidden" name="receiver" value="<?= Orders::YANDEX_CARD ?>"> 
		<input type="hidden" name="quickpay-form" value="shop">  
		<input type="hidden" name="targets" value="Транзакция оп заказу № <?= $order_id ?>">
		<input type="hidden" name="label" value="<?= $order_id ?>"> 
		<input type="hidden" name="sum" value="<?= $sum ?>" data-type="number">
		<input type="hidden" name="successURL" value="<?= $success_url ?>">
		
		<span class="form-title">Способ оплаты</span>
		
		<div class="payment-types-container clearfix">
			<label style="float: left;">
				<input type="radio" name="paymentType" value="PC">Яндекс.Деньгами
			</label>    
			<label style="float: right;">
				<input type="radio" name="paymentType" value="AC" checked>Банковской картой
			</label>
		</div>
		
			<input type="submit" class="submit-btn" value="Оплатить (<?= $sum ?>руб.)" >
			<?= Html::a('Оплатить без комиссии (' . $clean_order_price .'руб.)', ['site/dostavka'], [
				'class' => 'submit-btn', 
			]); ?>	
		
	</form>
	
</div>

