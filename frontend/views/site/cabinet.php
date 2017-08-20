<?php

/* @var $this yii\web\View */
/* @var $model common\models\CommonUser */
/* @var $orders array of common\models\Orders */
/* @var $discountVal integer */
/* @var $discountGrossVal integer */

use yii\helpers\Html;


// common cabinet.js
$js_file_name = Yii::getAlias('@frontend') . '/web/js/cabinet.js';
$this->registerJsFile('/js/cabinet.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => 'frontend\assets\jQueryAsset',
]);

$firstname = Yii::$app->user->identity->firstname ?? '';
$phone = Yii::$app->user->identity->phone ?? '';
$address = Yii::$app->user->identity->address ?? '';
$email = Yii::$app->user->identity->email ?? '';

?>

<main class="lk">
	<div class="line1">
		<div class="container">
			<h1 class="title">Личный кабинет</h1>
			<div class="content">
				<div class="content-titlebox clearfix">
					<img src="/img/lk-circle.png" alt="">
					<h2 id="username" data-toggle="modal" data-target="#cabinet-modal">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						<span class="firstname"><?= $model['firstname'] ?></span>
					</h2>
				</div>
				<div class="content-orders clearfix">
					<div class="content-orders-left">
						<h2>История заказов</h2>
						<table>
							<tr>
								<th>Дата</th>
								<th>Заказ</th>
								<th>Цена</th>
								<th>Цена за доставку</th>
								<th>Статус</th>
							</tr>
							<?php 
							if ($orders): 
								foreach($orders as $order): 
									$formatter = Yii::$app->formatter;
									$order_price = $order['price'] - ($order['price'] / 100 * $order['discount_percent']);
									$status = '';
									switch($order['order_status']){
				                        case $order::STATUS_NEW:
				                            $status = 'В очереди';
				                            break;

				                        case $order::STATUS_PROCCESSING:
				                            $status = 'В обработке';
				                            break;

				                        case $order::STATUS_COMPLETED:
				                            $status = 'Завершен';
				                            break;

				                        case $order::STATUS_CANCELLED:
				                            $status = 'Отменен';
				                            break;
				                    }
							?>
							<tr>
								<td><?= $formatter->format($order['created_at'], 'date') ?></td>
								<td>№<?= $order['id'] ?></td>
								<td><?= $formatter->format($order_price , 'integer') ?> руб.</td>
								<td><?= $formatter->format($order['delivery_price'] , 'integer') ?> руб.</td>
								<?php if ($order['order_status'] != $order::STATUS_NOT_PAID): ?>
									<td><strong><?= $status ?></strong></td>
								<?php else: ?>
									<td>
										<?= Html::a('Требует оплаты', ['/order-pay/', 'id' => $order['id']], [
											'class' => 'order-need-pay',
										]) ?>	
									</td>
								<?php endif; ?>
							</tr>
						<?php endforeach; endif; // orders ?>
						</table>
					</div>
					<div class="content-orders-right">
						<h2>Ваша скидка</h2>
						<h3 style="margin-top: 40px">Розничная - <strong><?= $discountVal ?>%</strong></h3>
						<h3>Оптовая - <strong><?= $discountGrossVal ?>%</strong></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<!-- SETINGS Modal -->
<div class="modal fade" id="cabinet-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="myModalLabel">Настройки аккаунта</h4>
		</div>
		<div class="modal-body">

			<form id="cabinet-form">

				<div class="form-group">
					<label class="control-label" for="firstname">Имя</label>
					<input type="text" class="form-control" id="firstname" placeholder="Степан" value="<?= $firstname ?>" autocomplete="off">
					<div class="help-block"></div>
				</div>
				
				<div class="form-group">
					<label class="control-label" for="email">Email</label>
					<input type="text" class="form-control" id="email" placeholder="example@mail.com" value="<?= $email ?>" autocomplete="off">
					<div class="help-block"></div>
				</div>

				<div class="form-group">
					<label class="control-label" for="phone">Телефон</label>
					<input type="text" class="form-control masked-phone" id="phone" placeholder="+7 (999) 999-99-99" value="<?= $phone ?>" autocomplete="off">
					<div class="help-block"></div>
				</div>

				<div class="form-group">
					<label class="control-label" for="adress">Адрес</label>
					<input type="text" class="form-control" id="adress" placeholder="г. Москва, Проспект Вернадского, 78" value="<?= $address ?>" autocomplete="off">
					<div class="help-block"></div>
				</div>

			</form>

		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

			<button type="button" 
					class="btn btn-info" 
					data-dismiss="modal"
					data-toggle="modal" 
					data-target="#password-modal">Изменить пароль</button>

			<button type="button" id="form-submit" class="btn btn-success cabinet-form-submit">
				<i class="fa fa-spinner fa-spin loading"></i>
				Сохранить
			</button>
		</div>
		</div>
	</div>
</div>

<!-- NEW PASSWORD Modal -->

<div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Изменить пароль</h4>
			</div>
			<div class="modal-body">

				<form id="password-form">

					<div class="form-group">
						<label class="control-label" for="old-password">Старый пароль</label>
						<input type="password" class="form-control" id="old-password">
						<div class="help-block"></div>
					</div>
					
					<div class="form-group">
						<label class="control-label" for="new-password">Новый пароль</label>
						<input type="password" class="form-control" id="new-password">
						<div class="help-block"></div>
					</div>

					<div class="form-group">
						<label class="control-label" for="repeat-password">Повторите пароль</label>
						<input type="password" class="form-control" id="repeat-password">
						<div class="help-block"></div>
					</div>

				</form>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>

				<button type="button" id="password-form-submit" class="btn btn-success cabinet-form-submit">
					<i class="fa fa-spinner fa-spin loading"></i>
					Сохранить
				</button>
			</div>

		</div>
	</div>
</div>

<!-- SUCCESS MODAL -->

<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<span class="h2">Ваши данные успешно изменены!</span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>
	</div>
</div>