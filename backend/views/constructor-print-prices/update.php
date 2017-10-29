<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorPrintPrices */

$this->title = 'Обновить цену на печать: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Print Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="constructor-print-prices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'materials' => $materials,
        'types' => $types,
        'sizes' => $sizes,
        'attendances' => $attendances,
    ]) ?>

</div>
