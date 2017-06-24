<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Office;
use common\models\ConstructorColors;
use common\models\ConstructorProducts;
use common\models\ConstructorSizes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цвета краски';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-colors-index">

    <h1>Наличие на складе</h1>
    <h2>Краска</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderColors,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'label' => 'Офис',
                'attribute' => 'office_id',
                'value' => function($model){
                    $record = Office::FindOne(['id' => $model['office_id']]);

                    if ($record)
                        return $record['address'];                        
                    
                    return "Офис не задан";
                }
            ],
            'name',
            'liters',            
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'template' => '{view}',
            // ],
        ],
    ]); ?>
    <h2>Товары</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderItems,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'label' => 'Офис',
                'attribute' => 'office_id',
                'value' => function($model){
                    $record = Office::FindOne(['id' => $model['office_id']]);

                    if ($record)
                        return $record['address'];                        
                    
                    return "Офис не задан";
                }
            ],
            [
                'label' => 'Товар',
                'value' => function($model){
                    $modelColor = ConstructorColors::FindOne(['id' => $model['color_id']]);
                    $record = ConstructorProducts::FindOne(['id' => $modelColor['product_id']]);

                    if ($record)
                        return $record['name'];                        
                    
                    return "Не задан";
                }
            ],
            [
                'label' => 'Цвет товара',
                'attribute' => 'color_id',
                'value' => function($model){
                    $record = ConstructorColors::FindOne(['id' => $model['color_id']]);

                    if ($record)
                        return $record['name'];                        
                    
                    return "Не задан";
                }
            ],
            [
                'label' => 'Размер',
                'attribute' => 'size_id',
                'value' => function($model){
                    $record = ConstructorSizes::FindOne(['id' => $model['size_id']]);

                    if ($record)
                        return $record['size'];                        
                    
                    return "Не задан";
                }
            ],
            'count',
            
            // [
            //     'class' => 'yii\grid\ActionColumn',
            //     'template' => '{view}',
            // ],
        ],
    ]); ?>
</div>
