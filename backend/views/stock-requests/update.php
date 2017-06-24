<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockRequests */

$this->title = 'Обновить заявку: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-requests-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
