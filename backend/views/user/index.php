<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зарегистрированные пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // Для валидации действия user/delete ?>
    <?= Html::csrfMetaTags() ?>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Имя пользователя',
                'attribute' => 'username',
            ],
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            [
                'label' => 'Статус',
                'attribute' => 'status',
                'value' => function($model){
                    switch($model['status']){
                        case $model::STATUS_ACTIVE:
                            return "Активный";
                            break;

                        case $model::STATUS_DELETED:
                            return "Удален";
                            break;
                    }
                }
            ],
            [
                'label' => 'Роль',
                'attribute' => 'role',
                'value' => function($model){
                    switch($model['role']){
                        case $model::ROLE_MANAGER:
                            return "Менеджер";
                            break;

                        case $model::ROLE_ADMIN:
                            return "Администратор";
                            break;

                        case $model::ROLE_COURIER:
                            return "Курьер";
                            break;

                        case $model::ROLE_EXECUTOR:
                            return "Исполнитель";
                            break;
                    }
                }
            ],
            [
                'label' => 'Дата создания',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
