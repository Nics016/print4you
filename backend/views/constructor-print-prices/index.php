<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цены на печать';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-print-prices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать цену', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'type_id',
                'label' => 'Тип печати',
                'value' => function ($data) {
                    return $data->type->name;
                }
            ],
            [
                'attribute' => 'material_id',
                'label' => 'Материал товара',
                'value' => function ($data) {
                    return $data->material->name;
                }
            ],
            [
                'attribute' => 'size_id',
                'label' => 'Размер принта',
                'value' => function ($data) {
                    return $data->size->name;
                }
            ],
            [
                'attribute' => 'price',
                'label' => 'Розничная цена',
                'value' => function ($data) {
                    return $data->price . ' руб.';
                }
            ],
            [
                'attribute' => 'color',
                'label' => 'Цветность',
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
