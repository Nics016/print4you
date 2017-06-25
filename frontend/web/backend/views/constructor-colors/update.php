<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */

$this->title = 'Update Constructor Colors: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="constructor-colors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
