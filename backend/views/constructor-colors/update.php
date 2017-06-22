<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */

$this->title = 'Редактировать цвет продукта: ' . $model->product->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Constructor Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="constructor-colors-update">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?= Html::img($model::getSmallFrontImageLink() . '/' . $model->small_front_image, [
		'alt' => 'Наш логотип',
		'width' => 320,
	]) ?>
	<?= Html::img($model::getSmallBackImageLink() . '/' . $model->small_back_image, [
		'alt' => 'Наш логотип',
		'width' => 320,
		'style' => 'margin-left: 20px;',
	]) ?>
	
	<br>
    <?= $this->render('_form', [
        'model' => $model,
        'sizes' => $sizes,
    ]) ?>
	
	<br>
	
	<?php if (count($color_storage) > 0): ?>
		<h3>Склад:</h3>

		<br>

		<table class="table table-striped table-bordered" style="max-width: 700px;">
			<thead>
				<tr>
					<th>Наименование размера</th>
					<th>Адрес склада</th>
					<th>Количество</th>
				</tr>
			</thead>

			<tbody>

				<?php 
				for ($i = 0; $i < count($color_storage); $i++): 
					$size = $color_storage[$i]['size']['size'];
					$adress = $color_storage[$i]['office']['address'];
					$count = $color_storage[$i]['count'];
				?>
					<tr>
						<td class="text-center" style="vertical-align: center;"><?= $size ?></td>
						<td class="text-center" style="vertical-align: center;"><?= $adress ?></td>
						<td class="text-center" style="vertical-align: center;"><?= $count ?></td>
					</tr>
				<?php endfor; ?>
			</tbody>
		</table>
		
		<br>
		<br>

	<?php else: ?>

		<h3>Склад еще не заполняли!</h3>

	<?php endif; ?>

	
	

</div>
