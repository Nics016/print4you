<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Обновить пользователя: ' . $model->username;
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_user', [
    'model' => $model
    ]) ?>

</div>
