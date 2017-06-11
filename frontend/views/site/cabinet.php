<?php

/* @var $this yii\web\View */

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
						<h2>Александр Иванов</h2>
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
								<tr>
									<td>15/02/2017</td>
									<td>№341</td>
									<td>2 000 руб.</td>
									<td><strong>В очереди</strong></td>
								</tr>
								<tr>
									<td>25/01/2017</td>
									<td>№329</td>
									<td>1 750 руб.</td>
									<td><strong>Отправлен</strong></td>
								</tr>
								<tr>
									<td>08/01/2017</td>
									<td>№314</td>
									<td>900 руб.</td>
									<td><strong>Отправлен</strong></td>
								</tr>
								<tr>
									<td>28/12/2016</td>
									<td>№297</td>
									<td>1 500 руб.</td>
									<td><strong>Отправлен</strong></td>
								</tr>
							</table>
						</div>
						<img src="/img/lk-discount.png" alt="" class="content-orders-right">
					</div>
				</div>
			</div>
		</div>
	</main>