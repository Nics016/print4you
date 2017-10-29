<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorProducts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить товар?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Цвета товара', ['constructor-colors/', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Картинка',
                'format' => 'html',
                'value' => function ($data) {
                    $image = $data::getSmallImagesLink() . '/' . $data->small_image;

                    return "<img src='$image' width='320'/>";
                }
            ],
            [   
                'attribute' => 'name',
                'label' => 'Название товара', 
            ],
            [
                'attribute' => 'alias',
                'label' => 'Алиас',
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание товара',
                'format' => 'ntext',
            ],
            [
                'label' => 'Категория товара',
                'value' => function ($data) {
                    return $data->category->name;
                }
            ],
            [
                'attribute' => 'seo_title',
                'label' => 'SEO Title',
            ],
            [
                'attribute' => 'seo_description',
                'label' => 'SEO Description',
            ],
            [
                'attribute' => 'seo_keywords',
                'label' => 'SEO Keywords',
            ],
            [
                'attribute' => 'img_alt',
                'label' => 'Alt Картинки',
            ],
        ],
    ]) ?>

</div>
