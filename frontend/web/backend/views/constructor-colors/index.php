<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Constructor Colors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Constructor Colors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'color_value',
            'front_image',
            'back_image',
            // 'sizes',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
