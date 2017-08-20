<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PagesSeo */

$title = 'Неизвестно';

for ($i = 0; $i < count($model::PAGES_ARRAY); $i++) {
    if ($model->page_id == $model::PAGES_ARRAY[$i]['id']) {
        $title = $model::PAGES_ARRAY[$i]['name'];
        break;
    }
}

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Pages Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-seo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Точно удалить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [  
                'label' => 'Страница',
                'attribute' => 'page_id',
                'value' => function ($data) {
                    for ($i = 0; $i < count($data::PAGES_ARRAY); $i++) {
                        if ($data->page_id == $data::PAGES_ARRAY[$i]['id'])
                            return $data::PAGES_ARRAY[$i]['name'];
                    }

                    return 'Неизвестно';
                }
            ],
            'title',
            'keywords',
            'description',
        ],
    ]) ?>

</div>
