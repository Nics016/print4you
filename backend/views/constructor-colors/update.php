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

</div>
