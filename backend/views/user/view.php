<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

use common\models\Office;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Пользователь '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'label' => 'Имя пользователя',
                'value' => $model->username,
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
                'label' => 'Дата создания',
                'attribute' => 'created_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->created_at);
                }
            ],
            [
                'label' => 'Дата обновления',
                'attribute' => 'updated_at',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->updated_at);
                }
            ],
        ],
    ]) ?>

</div>
