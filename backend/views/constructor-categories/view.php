<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ConstructorCategories */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Constructor Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-categories-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить категорию?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Вернуться', ['constructor-categories/'], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'name',
                'label' => 'Имя',
            ],
            [
                'attribute' => 'alias',
                'label' => 'Алиас',
            ],
            [
               'attribute' => 'description',
               'format' => 'html',
                'label' => 'Описание',
            ],
            [
                'attribute' => 'img',
                'label' => 'Картинка',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img($data::getImagesLink() . '/' . $data->img, [
                        'width' => 320,
                    ]);
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
