<?php
	use yii\helpers\Html;
	use yii\grid\GridView;

	$this->title = 'Статистика';

	// @var integer $totalClients
	// @var array $clientsByDate
	// @var integer $totalOrdersAll
	// @var integer $totalOrdersCompleted
	// @var $dataProviderManagers yii\data\ActiveDataProvider
	// @var $dataProviderOffices yii\data\ActiveDataProvider
 ?>
<h1><?= Html::encode($this->title) ?></h1>
<h2>Статистика по клиентам</h2>
<h3>Всего клиентов: <?= $totalClients ?></h3>
<div id="usersChart"></div>
<div id="usersChart1"></div>
<style>
	table.clientsNoOrders {
		display: none;
	}
	table.clientsNoOrders tr td,
	table.clientsNoOrders tr th{
		padding: 10px 20px;
		font-size: 14px;
		color: #000;
	}
</style>
<script>
	new Morris.Line({
		// ID of the element in which to draw the chart.
		element: 'usersChart',
		// Chart data records -- each entry in this array corresponds to a point on
		// the chart.
		data: [
		<?php foreach($clientsByDate as $date => $clientsNum): ?>
		{ day: '<?= $date ?>', value: <?= $clientsNum ?> },
		<?php endforeach; ?>
		],
		// The name of the data record attribute that contains x-values.
		xkey: 'day',
		// A list of names of data record attributes that contain y-values.
		ykeys: ['value'],
		// Labels for the ykeys -- will be displayed when you hover over the
		// chart.
		labels: ['Клиенты']
	});

	$(document).ready(function(){
		// toggle displaying tables
		$("#showClientsNoOrders").bind("click", function(e){
			e.preventDefault();
			$("table.clientsNoOrders").slideToggle();
		});
	});
</script>
<h3>Клиенты, не сделавшие заказ:</h3>
<a href="#" id="showClientsNoOrders">Показать</a>
<table class="clientsNoOrders">
	<tr>
		<th>#</th>
		<th>Клиент</th>
		<th>Номер телефона</th>
	</tr>
	<?php $i = 1; ?>
	<?php foreach($clientsWithoutOrders as $client): ?>
		<tr>
			<td><?= $i ?></td>
			<td><?= $client['id'] ?></td>
			<td><?= $client['phone'] ?></td>
		</tr>
	<?php $i++; ?>
	<?php endforeach; ?>
</table>

<h2>Статистика по заказам</h2>
<h4>Всего заказов: <?= $totalOrdersAll ?></h4>
<h4>Всего <strong>завершенных</strong> заказов: <?= $totalOrdersCompleted ?></h4>
<h3>Заказы по менеджерам</h3>
<?= GridView::widget([
    'dataProvider' => $dataProviderManagers,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        
        [
        	'label' => 'Менеджер',
        	'attribute' => 'manager_name',
        ],
        [
        	'label' => 'Количество заказов',
        	'attribute' => 'num_orders',
        ],
    ],
]); ?>
<h3>Заказы по точкам</h3>
<?= GridView::widget([
    'dataProvider' => $dataProviderOffices,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        
        [
        	'label' => 'Офис',
        	'attribute' => 'office_address',
        ],
        [
        	'label' => 'Количество заказов',
        	'attribute' => 'num_orders',
        ],
    ],
]); ?>