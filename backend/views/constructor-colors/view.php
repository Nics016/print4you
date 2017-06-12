<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorColors */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Colors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-colors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить цвет?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Вернуться', ['index', 'id' => $product->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'label' => 'Имя цвета',
            ],
            [
                'attribute' => 'color_value',
                'label' => 'Значение цвета',
                'format' => 'html',
                'value' => function ($data) {
                    return 
                        '<div style="border: 1px solid black; width: 50px; height: 20px; 
                            background: ' . $data->color_value .';">
                        </div>';
                }
            ],
            [
                'label' => 'Имя товара',
                'value' => function ($data) {
                    return $data->product->name;
                }
            ],
            [
                'label' => 'Размеры',
                'value' => function ($data) {
                    $sizes = '';

                    for ($i = 0; $i < count($data->sizes); $i++) {
                        if ($i + 1 == count($data->sizes))
                            $sizes .= $data->sizes[$i]->size;
                        else
                            $sizes .= $data->sizes[$i]->size . ', ';
                    }

                    return $sizes;
                }
            ],

            [
                'label' => 'Лицевая сторона',
                'format' => 'html',
                'value' => function ($data) {
                    $image = $data::getSmallFrontImageLink() . '/' . $data->small_front_image;

                    return "<img src='$image' width='320'/>";
                }
            ],

            [
                'label' => 'Обратная сторона',
                'format' => 'html',
                'value' => function ($data) {
                    $image = $data::getSmallBackImageLink() . '/' . $data->small_back_image;

                    return "<img src='$image' width='320'/>";
                }
            ],
            
        ],
    ]) ?>

</div>
