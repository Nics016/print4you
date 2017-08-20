<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Reviews */

$this->title = 'Отзыв: #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Reviews', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить отзыв?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'user_id',
                'label' => 'Пользователь',
                'format' => 'html',
                'value' => function ($data) {
                    $html = '<p>' . $data->user->firstname . '</p>';
                    return $html;
                }
            ],
            [
                'attribute' => 'text',
                'label' => 'Текст отзыва',
            ],
            [
                'attribute' => 'is_like',
                'label' => 'Оценка',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->is_like ? 
                           '<h2 class="glyphicon glyphicon-thumbs-up"></h2>' 
                           :
                           '<h2 class="glyphicon glyphicon-thumbs-down"></h2>';
                }
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Дата',
                'format' => ['date', 'php:Y-m-d в H:i:s'],
            ],
            [
                'attribute' => 'is_published',
                'label' => 'Опубликовано?',
                'format' => 'boolean',
            ],
        ],
    ]) ?>

</div>
