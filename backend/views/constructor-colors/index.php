<?php

use yii\helpers\Html;
use yii\grid\GridView;


$this->title = 'Цвета товара "' . $product->name . '"';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать цвет', ['create', 'product_id' => $product->id], ['class' => 'btn btn-success']) ?>
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
               'format' => 'html', 
               'value' => function ($data) {
                    return 
                        '<div style="border: 1px solid black; width: 50px; height: 20px; 
                            background: ' . $data->color_value .';">
                        </div>';
               }
            ],

            [
                'class' => yii\grid\ActionColumn::className(),
                'template' => '{view} {update} {delete}',
                'buttons' => [

                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            '<span class="glyphicon glyphicon glyphicon-trash"></span>', 
                            $url, 
                            [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => 'Удалить цвет товара?',
                                'data-method' => 'post',
                                'data-pjax' => '0',
                            ]
                        );
                    },
                ],
            
            ],
        ],
    ]); ?>
</div>
