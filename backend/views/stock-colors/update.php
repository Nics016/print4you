<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\StockColors */

$this->title = 'Обновить краску: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Stock Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-colors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
