<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearchConstructorProducts */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Склад конструктора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Название',
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'small_image',
                'label' => 'Картинка',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data::getSmallImagesLink() . '/' . $data->small_image, [
                        'alt' => $data->name,
                        'width' => '200',
                    ]);
                }
            ],
            [
                'label' => 'Действие',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a('Цвета товара', ['constructor-sklad/colors', 'product_id' => $data->id], [
                        'class' => 'btn btn-primary',
                    ]);
                }
            ],
        ],
    ]); ?>
</div>
