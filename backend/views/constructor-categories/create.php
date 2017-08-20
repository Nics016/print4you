<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ConstructorCategories */

$this->title = 'Создать категорию';
$this->params['breadcrumbs'][] = ['label' => 'Constructor Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
