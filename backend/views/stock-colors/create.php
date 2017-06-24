<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\StockColors */

$this->title = 'Новая краска';
$this->params['breadcrumbs'][] = ['label' => 'Stock Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-colors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
