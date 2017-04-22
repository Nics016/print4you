<?php
	use yii\helpers\Html;

	$this->title = 'Статистика';
 ?>
<h1><?= Html::encode($this->title) ?></h1>
<h2>Прирост новых клиентов</h2>
<div id="usersChart" style="height: 250px;"></div>
<script>
	new Morris.Line({
		// ID of the element in which to draw the chart.
		element: 'usersChart',
		// Chart data records -- each entry in this array corresponds to a point on
		// the chart.
		data: [
		{ day: '20/04', value: 20 },
		{ day: '21/04', value: 10 },
		{ day: '25/04', value: 5 },
		{ day: '26/04', value: 5 },
		{ day: '27/04', value: 20 }
		],
		// The name of the data record attribute that contains x-values.
		xkey: 'day',
		// A list of names of data record attributes that contain y-values.
		ykeys: ['value'],
		// Labels for the ykeys -- will be displayed when you hover over the
		// chart.
		labels: ['Клиенты']
	});
</script>