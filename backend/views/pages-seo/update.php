<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PagesSeo */

$this->title = 'Обновить метатеги';
$this->params['breadcrumbs'][] = ['label' => 'Pages Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pages-seo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
