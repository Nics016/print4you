<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $loginError string */
/* @var $registerSuccess string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

use yii\widgets\ActiveForm;
use frontend\models\LoginForm;
use frontend\models\RequestCallForm;
use common\models\UserSettings;

$model = new LoginForm();
$modelRequestCall = new RequestCallForm();

// LK label and link
$lkLabel = Yii::$app->user->isGuest ? "Личный кабинет" 
    : Yii::$app->user->identity->username;
$lkLogoutLink = Html::tag('a', 'выйти', ['style' => 'margin-left: 5px', 
        'href' => Yii::$app->urlManager->createUrl([
            'site/logout'
        ]),
    ]);
$lkLink = Yii::$app->user->isGuest ? "#" 
    : Url::to(['site/cabinet']);
$lkDataTarget = Yii::$app->user->isGuest ? "#loginRegisterModal"
    : "";

// Email, vk, insta from UserSettings
$settingsData = UserSettings::getCurrentSettings();
$topEmail = $settingsData['email_index'];
$vkLink = $settingsData['vk_link'];
$instaLink = $settingsData['insta_link'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php $this->head() ?>
    <meta charset="<?= Yii::$app->charset ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
</head>
<body id=#main-wrap>
<?php $this->beginBody() ?>
    <header>
        <!-- TOPLINE -->
        <div class="topline">
            <div class="container clearfix"> 
                <a href="mailto:<?= $topEmail ?>" class="topline-elem1">
                    <img src="/img/topline-mail.png" alt="">
                    <span>
                        <?= $topEmail ?>
                    </span>
                </a>
                <a href="tel:89633325632" class="topline-elem2">
                    <img src="/img/topline-phone.png" alt="">
                    <span class="callback-phone">
                        +7 (963) 
                        <strong>332 56 32</strong>
                    </span>
                </a>
                <a href="#" class="topline-elem3" data-toggle="modal" data-target="#makeCallModal">Заказать звонок</a>
                <div class="topline-elem4" >   
                    <a href="<?= $lkLink ?>" data-toggle="modal" data-target= "<?= $lkDataTarget ?>">
                        <img src="/img/topline-lk.png" alt=""><span><?= $lkLabel ?></span>
                    </a>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        (<?= $lkLogoutLink ?> )
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- END OF TOPLINE -->

        <!-- TOPMENU -->
        <div class="topmenu">
            <div class="container clearfix">
                <a href="<?= Url::home() ?>" class="topmenu-left">
                    <img src="/img/header-logo.png">
                    <span>Печатаем и шьем <br> для вас</span>
                </a>
                <div class="topmenu-right">
                    <!-- TOP-RIGHT-ABOVE -->
                    <div class="topmenu-right-above clearfix">
                        <span class="topmenu-right-above-elem1">
                            Футболки Cанкт-Петербурга с принтом – дело наших рук!
                        </span>
                        <a href="https://www.google.com/maps?ll=59.934104,30.344623&z=16&t=m&hl=ru-RU&gl=RU&mapclient=embed&q=naberezhnaya+reki+Fontanki,+38+Sankt-Peterburg+191025" class="topmenu-right-above-elem2 clearfix" target="_blank">
                            <img src="/img/header-pin.png" alt="">
                            <span>
                                Наб. реки Фонтанки, 38 
Гостиный двор (в арке)
                            </span>
                        </a>
                        <a href="https://www.google.com/maps/place/Goncharnaya+ul.,+2,+Sankt-Peterburg,+Russia,+191036/@59.930385,30.363689,16z/data=!4m5!3m4!1s0x469631bb14d4731d:0x545b6687b2935d3d!8m2!3d59.9303848!4d30.3636887?hl=ru-RU" class="topmenu-right-above-elem3 clearfix" target="_blank">
                            <img src="/img/header-pin.png" alt="">
                            <span>
                                Площадь Восстания 
Гончарная, 2
                            </span>
                        </a>
                        <div class="topmenu-right-above-elem4">
                            <!-- <a href="#">
                                <img src="/img/header-search.png" alt="">
                            </a>
                            <a href="#">
                                <img src="/img/header-menu.png" alt="">
                            </a> -->
                        </div>
                    </div>
                    <!-- END OF TOP-RIGHT-ABOVE -->

                    <div class="topmenu-right-below clearfix">
                        <nav>
                            <a href="<?= Url::home() ?>" class='active'>Главная</a>
                            <span  class="submenu-container">
                                Инфо
                                <ul class="submenu">
                                    <li>
                                        <a href="<?= Url::to(['site/franchise']) ?>">Франшиза</a>
                                    </li> 
                                    <li>
                                        <a href="<?= Url::to(['site/contacts']) ?>">Контакты</a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['site/sale']) ?>">Акции</a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['site/nashi-gosti']) ?>">Наши гости</a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['site/about']) ?>">О нас</a>
                                    </li>
                                </ul>
                            </span>
                            <a href="<?= Url::to(['uslugi/']) ?>">Услуги</a>
                            <a href="<?= Url::to(['site/dostavka']) ?>">Оплата и доставка</a>
                            <a href="<?= Url::to(['/constructor']) ?>" class="red-label">Конструктор</a>
                            <a href="<?= Url::to(['reviews/']) ?>">Отзывы</a>
                            <a href="<?= Url::to(['cart/']) ?>">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                Корзина
                            </a>
                        </nav>        
                        <a href="<?= $instaLink ?>" class="topmenu-right-below-in" target="_blank">
                            <img src="/img/header-in.png" alt="">
                        </a>
                        <a href="<?= $vkLink ?>" class="topmenu-right-below-vk" target="_blank">
                            <img src="/img/header-vk.png" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF TOPMENU -->
    </header>

    <?= Alert::widget() ?>
    <?= $content ?> 

    <footer>
        <div class="container clearfix">
            <div class="footer-left">
                <div class="footer-left-contacts">
                    <p>Студия на Площади Восстания</p>
                    <a href="tel:89633325632">+7 (963) <strong>332 56 32</strong></a>
                    <p class="with-margin">Студия на Фонтанке</p>
                    <a href="tel:89633092848"><strong>309 28 48</strong></a>
                    <a href="#" class="footer-left-callMe" data-toggle="modal" data-target="#makeCallModal">Заказать звонок</a>
                </div>
            </div>
            <div class="footer-center">
                <a href="<?= Url::home() ?>">
                    <img src="/img/footer-logo.png" alt="" class="footer-center-print">
                </a>
                <span>Печатаем и шьем <br> для вас</span>
                <div class="footer-center-socials">
                    <a href="<?= $vkLink ?>" target="_blank"><img src="/img/footer-vk.png" alt=""></a>
                    <a href="<?= $instaLink ?>" target="_blank"><img src="/img/footer-in.png" alt=""></a>
                </div>
            </div>
            <div class="footer-right">
                <span>Последние фотографии</span>
                <div class="footer-right-photos">
                    <a href="/img/footer-photo1.png"><img src="/img/footer-photo1.png" alt=""></a>
                    <a href="/img/footer-photo2.png"><img src="/img/footer-photo2.png" alt=""></a>
                    <a href="/img/footer-photo3.png"><img src="/img/footer-photo3.png" alt=""></a>
                </div>
            </div>
        </div>

<!-- MODALS -->
<div id="loginRegisterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Войдите или зарегистрируйтесь</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['site/login'], 'method' => 'POST', 'id' => 'login-form', 'enableAjaxValidation'=> true, 'validateOnBlur' => false, 'validateOnChange' => false]); ?>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <br>
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'style' => 'margin-left: 20px',]) ?>

                <?= Html::tag('a', 'Регистрация', ['class' => 'btn btn-success', 'style' => 'margin-left: 5px', 
                        'href' => Yii::$app->urlManager->createUrl([
                            'site/register'
                        ]),
                    ]); ?>
            </div>
        <?php ActiveForm::end(); ?>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>


<div id="makeCallModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Заказать звонок</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => ['site/request-call-sent'], 'method' => 'POST']); ?>
            <div class="contacts-questions-form-left-line">
                <?= $form->field($modelRequestCall, 'name')->textInput(['autofocus' => true, 'placeholder' => 'Ваше имя'])->label(false) ?>
            </div>
            <div class="contacts-questions-form-left-line">
                <?= $form->field($modelRequestCall, 'phone')->textInput(['placeholder' => 'Ваш телефон'])->label(false) ?>
            </div>
            <div class="contacts-questions-form-right">
                <?= $form->field($modelRequestCall, 'comment')->textArea(['placeholder' => 'Примечание'])->label(false) ?>
                <br>
                <div class="clearfix">
                    <?= $form->field($modelRequestCall, 'form_type')->hiddenInput(['value' => RequestCallForm::FORM_TYPE_CALL])->label(false) ?>
                    <?= Html::submitInput('Отправить', ['style' => 'margin-top: 20px']) ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>

  </div>
</div>
<!-- END OF MODALS -->
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>