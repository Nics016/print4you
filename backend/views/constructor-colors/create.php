<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */

$this->title = 'Create Constructor Colors';
$this->params['breadcrumbs'][] = ['label' => 'Constructor Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
