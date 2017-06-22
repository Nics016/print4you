<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserSettings */

$this->title = 'Create User Settings';
$this->params['breadcrumbs'][] = ['label' => 'User Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
