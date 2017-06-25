<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConstructorPrintPrices */

$this->title = 'Создать новую цену';
$this->params['breadcrumbs'][] = ['label' => 'Constructor Print Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-print-prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'materials' => $materials,
        'types' => $types,
        'sizes' => $sizes,
        'attendances' => $attendances,
    ]) ?>

</div>
