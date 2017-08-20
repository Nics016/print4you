<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\PagesSeo */

$this->title = 'Создать метатеги';
$this->params['breadcrumbs'][] = ['label' => 'Pages Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-seo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
