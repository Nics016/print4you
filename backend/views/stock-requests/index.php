<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\User;
use backend\models\StockRequestItems;
use backend\models\StockColors;
use common\models\Office;
use common\models\ConstructorStorage;
use common\models\ConstructorColors;
use common\models\ConstructorProducts;
use common\models\ConstructorSizes;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки на товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая заявка', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Пользователь',
                'attribute' => 'user_id',
                'value' => function($model){
                    $manager = User::findIdentity($model['user_id']);
                    return $manager['username'];
                }
            ],
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
                'label' => 'Элементы заявки',
                'value' => function($model){
                    $answ = "";
                    $requestItems = StockRequestItems::find()
                        ->where(['stock_request_id' => $model['id']])
                        ->all();
                    if ($requestItems) {
                        $requestColors = [];
                        $requestGoods = [];

                        foreach ($requestItems as $item) {
                            if ($item['stock_color_id']){
                                $requestColors[] = $item;
                            }
                            if ($item['constructor_storage_id']){
                                $requestGoods[] = $item;
                            }
                        }

                        if (count($requestColors) > 0) {
                            $answ .= Html::tag('h4', 'Краски');
                            $answ .= "<ol>";
                            foreach ($requestColors as $color) {
                                $modelColor = StockColors::findOne(['id' => $color['stock_color_id']]);
                                $liText = $modelColor['name'] . " - " 
                                    . $color['stock_color_litres'] . " л" . "<br>";
                                $answ .= Html::tag('li', $liText);
                            }
                            $answ .= "</ol>";
                        }

                        if (count($requestGoods) > 0) {
                            $answ .= Html::tag('h4', 'Товары');
                            $answ .= "<ol>";
                            foreach ($requestGoods as $good) {
                                $modelContructorStorage = ConstructorStorage::findOne(['id' => $good['constructor_storage_id']]);
                                $modelColor = ConstructorColors::FindOne(['id' => $modelContructorStorage['color_id']]);
                                $modelProduct = ConstructorProducts::FindOne(['id' => $modelColor['product_id']]);
                                $modelSize = ConstructorSizes::FindOne(['id' => $modelContructorStorage['size_id']]);
                                $itemName = $modelProduct['name'] 
                                    . ' - ' . $modelColor['name'] 
                                    . ' - ' . $modelSize['size'];
                                $liText = $itemName . " - " 
                                    . $good['constructor_storage_count'] . " шт" . "<br>";
                                $answ .= Html::tag('li', $liText);
                            }
                            $answ .= "</ol>";
                        }
                    } // if request items

                    if ($answ === ""){
                        $answ = "Нет"; 
                    }
                    return $answ;
                    
                },
                'format' => 'html',
            ],
            [
                'label' => '',
                'format' => 'html',
                'value' => function($model){
                    if (Yii::$app->user->identity->role === User::ROLE_ADMIN){
                        if (!$model->applied){
                            $btnApply = Html::a('Одобрить', ['accept-request', 'id' => $model->id], ['class' => 'btn btn-success']);
                            return $btnApply;
                        }
                        else { // already applied
                            return "Товары добавлены на склад";
                        }
                    }                        
                    
                    return $model->applied ? "Товары добавлены на склад" : "В ожидании";
                }
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
