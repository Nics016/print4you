<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сео страниц';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pages-seo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новые метатеги', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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
                                'data-confirm' => 'Точно удалить?',
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
