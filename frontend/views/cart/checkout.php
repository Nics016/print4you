<?php

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $offices array of string */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

// common checkout.js
$js_file_name = Yii::getAlias('@frontend') . '/web/js/checkout.js';
$this->registerJsFile('/js/checkout.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => 'frontend\assets\jQueryAsset',
]);

$firstname = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->firstname;
$phone = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->phone;
$address = Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->address;

?>

<main class="register">
	<div class="container">
		<h1>Оформление заказа</h1>
		<div class="row">
			<div class="col-sm-8 col-sm-push-2">
				
				<form id="checkout-form">

					<div class="form-group">
						<label class="control-label" for="firstname">Имя</label>
						<input type="text" class="form-control" id="firstname" placeholder="Напишите свое имя" value="<?= $firstname ?>">
						<div class="help-block"></div>
					</div>

					<div class="form-group">
						<label class="control-label" for="phone">Телефон:</label>
						<input type="text" class="form-control masked-phone" id="phone" value="<?= $phone ?>" placeholder="+7 (999) 999-99-99">
						<div class="help-block"></div>
					</div>
					
					<div class="form-group">
						<label class="radio-inline" class="control-label">
							<input type="radio" class="delivery-radio" name="delivery-type" value="delivery" checked>
							Доставка
						</label>

						<label class="radio-inline" class="control-label">
							<input type="radio" class="delivery-radio" name="delivery-type" value="self">
							Самовывоз
						</label>
					</div>
					
					<div id="delivery-container">
						
						<div class="form-group">
							<label class="control-label" for="delivery-distance">Местоположение адреса</label>
							<select class="form-control" id="delivery-distance">
								<?php 
								for ($i = 0; $i < count ($delivery_distances); $i++):
									$name = $delivery_distances[$i]['name'];
									$price = $delivery_distances[$i]['price'];
								?>
									<option value="<?= $i ?>"><?= $name ?>(<?= $price ?>р)</option>
								<?php endfor; ?>

							</select>
						</div>

						<div class="form-group">
							<label class="control-label" for="delivery-adress">Адрес доставки</label>
							<input type="text" class="form-control" id="delivery-adress" placeholder="г. Санкт-Петербург, ул. Центральная, 7" value="<?= $address ?>">
							<div class="help-block"></div>
						</div>

					</div>

					<div id="self-delivery-container" style="display: none;">
						
						<div class="form-group">
							<label class="control-label" for="office">Адрес самовывоза</label>
								<select class="form-control" id="office">
									<?php 
										foreach ($offices as $key => $value) 
											echo "<option value='$key'> $value </option>";
									?>
								</select>
						</div>

					</div>

					<div class="form-group">
						<label class="control-label" for="comment">Комментарий</label>
						<textarea id="comment" rows="4" class="form-control" placeholder="Ваш комментарий..."></textarea>
						<div class="help-block"></div>
					</div>

					<div class="payment-info">
						<span class="info">
							После оформления заказа Вас перенаправит на страницу оплаты
						</span>

					</div>

					<button type="submit" id="form-submit" class="btn btn-success">
						<i class="fa fa-spinner fa-spin loading"></i>
						Оформить заказ
					</button>

				</form>

	        </div>
	    </div>
	</div>

</main>