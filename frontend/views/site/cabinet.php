<?php

/* @var $this yii\web\View */
/* @var $model common\models\CommonUser */
/* @var $orders array of common\models\Orders */

use yii\helpers\Html;

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
											<td><?= $formatter->format($order['price'], 'integer') ?> руб.</td>
											<td><strong><?= $status ?></strong></td>
										</tr>
									<?php endforeach; // orders ?>
								<?php endif; // orders ?>
							</table>
						</div>
						<img src="/img/lk-discount.png" alt="" class="content-orders-right">
					</div>
				</div>
			</div>
		</div>
	</main>