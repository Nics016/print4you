<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\data\ActiveDataProvider;

use common\models\Office;
use backend\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Клиент '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
            <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php else: ?>
            <?= Html::a('Создать', ['create'], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'id',
            [
                'label' => 'Имя пользователя',
                'value' => $model->firstname,
            ],
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            'firstname',
            'phone',
            'address',
            'sum_purchased_retail',
            'sum_purchased_gross',
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
                'label' => 'Создан',
                'format' => 'datetime',
                'attribute' => 'created_at',
            ],

        ],
    ]) ?>

</div>
