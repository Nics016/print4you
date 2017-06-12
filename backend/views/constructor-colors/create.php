<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */

$this->title = 'Создать цвет товара: "' . $product->name . '"';
$this->params['breadcrumbs'][] = ['label' => 'Цвета товара', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'product' => $product,
        'sizes' => $sizes,
    ]) ?>

</div>
