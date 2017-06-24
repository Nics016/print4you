<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StockRequests */
/* @var $mapOffices */
/* @var $mapStockColors */
/* @var $mapGoods */
/* @var $modelsColors */
/* @var $modelsGoods */

$this->title = 'Новая заявка';
$this->params['breadcrumbs'][] = ['label' => 'Stock Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-requests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
		'mapOffices' => $mapOffices,
		'mapStockColors' => $mapStockColors,
		'mapGoods' => $mapGoods,
		'modelsColors' => $modelsColors,
		'modelsGoods' => $modelsGoods,
    ]) ?>

</div>
