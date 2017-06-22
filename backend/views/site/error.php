<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
$this->context->layout = 'main';
?>
<?php if ($exception->statusCode == 404): ?>
    <main>
        <div class="container">
            <h1 style="font-family: sportsWorldRegular; text-align: center;">Страницы не существует</h1>
            <a href="<?= Url::home() ?>" style="display: block; text-align: center; font-size: 20px; color:#97F; text-decoration: none;">Вернуться на главную</a>
            <img style="display: block; margin: 0px auto" src="/img/404-pic.png" alt="">
        </div>
    </main>
<?php else: ?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Ошибка выше появилась при обработке вашего запроса.
    </p>
    <p>
        Пожалуйста, сообщите нам, если вы считаете, что это внутренняя ошибка сервера. Спасибо.
    </p>

</div>
<?php endif; ?>