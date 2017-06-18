<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<?php if ($exception->statusCode == 404): ?>
    <main>
        <div class="container">
            <h1 style="font-family: sportsWorldRegular; text-align: center;">Страницы не существует</h1>
            <img style="display: block; margin: 10px auto" src="/img/404-pic.png" alt="">
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