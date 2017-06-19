<?php

/* @var $this yii\web\View */
/* @var $model common\models\CommonUser */
/* @var $orders array of common\models\Orders */
/* @var $discountVal integer */
/* @var $discountGrossVal integer */

use yii\helpers\Html;
use common\models\Orders;

$this->title = 'Print4you - Личный кабинет';
?>

<main class="lk">
		<div class="line1">
			<div class="container">
				<h1 class="title">Личный кабинет</h1>
				<div class="content">
					<div class="content-titlebox clearfix">
						<img src="/img/lk-circle.png" alt="">
						<h2><?= $model['firstname'] ?> <?= $model['secondname'] ?></h2>
					</div>
					<div class="content-orders clearfix">
						<div class="content-orders-left">
							<h2>История заказов</h2>
							<table>
								<tr>
									<th>Дата</th>
									<th>Заказ</th>
									<th>Цена</th>
									<th>Статус</th>
								</tr>
								<?php if ($orders): ?>
									<?php foreach($orders as $order): ?>
										<?php $formatter = Yii::$app->formatter ?>
										<?php $discountedPrice = Orders::calculateDiscountPrice($order['price'], $order['discount_percent']); ?>
										<?php 
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
											<td><?= $formatter->format($discountedPrice, 'integer') ?> руб.</td>
											<td><strong><?= $status ?></strong></td>
										</tr>
									<?php endforeach; // orders ?>
								<?php endif; // orders ?>
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