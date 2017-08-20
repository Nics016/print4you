<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории Конструктора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="constructor-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'label' => 'Имя',
            ],
            [
               'attribute' => 'description',
               'format' => 'html',
                'label' => 'Описание',
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
                                'data-confirm' => 'Удалить категорию?',
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
