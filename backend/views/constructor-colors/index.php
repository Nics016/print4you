<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Цвета товара "' . $product->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать цвет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Имя цвета',
            ],
            [
               'attribute' => 'color_value',
               'label' => 'Цвет', 
               'value' => function ($data) {
                    return 
                        '<div style="width: 50px; height: 20px background: ' . $data->color_value .';">
                        </div>';
               }
            ],
            //'front_image',
            //'back_image',
            // 'sizes',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
