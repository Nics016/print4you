<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\CommonUser;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'user_id',
                'label' => 'Пользователь',
                'format' => 'html',
                'value' => function ($data) {
                    $html = "Гость";
                    if ($data->user_id) {
                        $user = CommonUser::findIdentity($data->user_id);
                        if ($user) {
                            $html = '<p>' . $user->firstname . '</p>';
                        }
                    }
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
                                'data-confirm' => 'Удалить отзыв?',
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
