<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары конструктора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новый товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Имя',
                'format' => 'html',
                'value' => function ($data) {

                    $html = '<p>' . $data->name .'</p>';
                    $html .= Html::a('Цвета продукта', ['constructor-colors/', 'id' => $data->id],  ['class' => 'btn btn-primary']);

                    return $html;
                }
            ],
            [
               'attribute' => 'description',
               'format' => 'ntext',
                'label' => 'Описание',
                'value' => function ($data) {
                    return $data->description;
                } 
            ],
            //'image',
            [
               'attribute' => 'price',
                'label' => 'Цена',
                'value' => function ($data) {
                    return $data->price . ' Р';
                } 
            ],
            [
                'attribute' => 'is_published',
                'label' => 'Опубликовано?',
                'value' => function ($data) {
                    return $data->is_published ? 'Опубликовано' : 'Не опубликовано';
                }
            ],
            [
                'attribute' => 'category_id',
                'label' => 'Категория',
                'value' => function ($data) {
                    return $data->category->name;
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
                                'data-confirm' => 'Удалить товар?',
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
