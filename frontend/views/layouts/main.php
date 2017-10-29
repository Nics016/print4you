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

use frontend\components\basket\Basket;

$model = new LoginForm();
$modelRequestCall = new RequestCallForm();

// LK label and link
$lkLabel = Yii::$app->user->isGuest ? "Личный кабинет" 
    : Yii::$app->user->identity->firstname;
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
                <a href="mailto:<?= $topEmail ?>" class="topline-elem1" rel="nofollow">
                    <img src="/img/topline-mail.png" alt="mail">
                    <span>
                        <?= $topEmail ?>
                    </span>
                </a>
                <a href="tel:89633325632" class="topline-elem2" rel="nofollow">
                    <img src="/img/topline-phone.png" alt=phone"">
                    <span class="callback-phone">
                        +7 (812) 
                        <strong>309 28 48</strong>
                    </span>
                </a>
                <a href="#" class="topline-elem3" data-toggle="modal" data-target="#makeCallModal" rel="nofollow">Заказать звонок</a>
                <div class="topline-elem4" >   
                    <a href="<?= $lkLink ?>" data-toggle="modal" data-target= "<?= $lkDataTarget ?>" rel="nofollow">
                        <img src="/img/topline-lk.png" alt="lk">
                        <span id="head-username"><?= $lkLabel ?></span>
                    </a>
                    <?php if (!Yii::$app->user->isGuest): ?>
                        (<?= $lkLogoutLink ?> )
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- END OF TOPLINE -->

        <!-- TOPMENU -->
        <?= $this->render('topmenu', [
            'instaLink' => $instaLink,
            'vkLink' => $vkLink,
        ]); ?>
        <!-- END OF TOPMENU -->
    </header>

    <?= Alert::widget() ?>
    <?= $content ?> 

    <footer>
        <div class="container clearfix">
            <div class="footer-left">
                <div class="footer-left-contacts">
                    <p>Наш номер телефона</p>
                    <a href="tel:88123092848"><strong>309 28 48</strong></a>
                    <p class="with-margin">Студия на Площади Восстания</p>
                    <a href="<?= Url::to(['site/contacts']) ?>">
                        М.Площадь Восстания <br>
                        Гончарная,2
                    </a>
                    <p class="with-margin">Студия на Фонтанке</p>
                    <a href="<?= Url::to(['site/contacts']) ?>">
                        М. Гостиный двор <br>
                        Наб.Реки Фонтанки 38 (в арке)
                    </a>
                    <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">Сделать заказ</a>
                </div>
            </div>
            <div class="footer-center">
                <a href="<?= Url::home() ?>">
                    <img src="/img/footer-logo.png" alt="" class="footer-center-print">
                </a>
                <span>Печатаем и шьем <br> для вас</span>
                <div class="footer-center-socials">
                    <noindex>
                    <a href="<?= $vkLink ?>" target="_blank" rel="nofollow">
                        <img src="/img/footer-vk.png" alt="vk">
                    </a>
                    <a href="<?= $instaLink ?>" target="_blank" rel="nofollow">
                        <img src="/img/footer-in.png" alt="instagram">
                    </a>
                    </noindex>
                </div>
            </div>
            <div class="footer-right">
                <span>Последние фотографии</span>
                <div class="footer-right-photos">
                    <a href="/assets/images/guest_1.jpg" rel="nofollow" class="footer-img-gallery">
                        <img src="/assets/images/guest_1.jpg">
                    </a>
                    <a href="/assets/images/guest_4.jpg" rel="nofollow" class="footer-img-gallery">
                        <img src="/assets/images/guest_4.jpg">
                    </a>
                    <a href="/assets/images/guest_3.jpg" rel="nofollow" class="footer-img-gallery">
                        <img src="/assets/images/guest_3.jpg">
                    </a>

                </div>

                <a href="<?= Url::to(['site/confidential']) ?>" class="confidential-link">
                    Политика конфиденциальности
                </a>
            </div>
        </div>

<!-- MODALS -->
<div id="loginRegisterModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <?php if (Yii::$app->user->isGuest): ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Войдите или зарегистрируйтесь</h4>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'validateOnChange' => true]); ?>
            <?= $form->field($model, 'phone')->textInput([
                'class' => 'form-control masked-phone login-phone',
                'autocomplete' => 'off',
            ]) ?>
            <?= $form->field($model, 'password')->passwordInput([
                'class' => 'form-control login-password',
            ]) ?>
            <?= $form->field($model, 'rememberMe')->checkbox([
                'class' => 'login-remember',
            ]) ?>
            <div class="login-error">Неправильный телефон или пароль</div>
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Войти', [
                    'class' => 'btn btn-primary', 
                    'style' => 'margin-left: 20px',
                ]) ?>
                
                <?= Html::a('Регистрация', ['site/register'], [
                    'class' => 'btn btn-success modal-register-btn', 
                    'style' => 'margin-left: 5px', 
                ])?>

                <?= Html::a('Забыли пароль?', ['site/forgot-password'], [
                    'class' => 'btn btn-info', 
                    'style' => 'margin-left: 5px', 
                ])?>

            </div>
        <?php ActiveForm::end(); ?>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
    <?php endif; ?>

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
                <?= $form->field($modelRequestCall, 'phone')->textInput([
                    'placeholder' => 'Ваш телефон',
                    'class' => 'form-control masked-phone'
                ])->label(false) ?>
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
    
    <noindex>
        <script type="text/javascript" src="//vk.com/js/api/openapi.js?142"></script> 
    </noindex>

    <!-- VK Widget --> 
    <!-- <div id="vk_community_messages"></div> 
    <script type="text/javascript"> 
    VK.Widgets.CommunityMessages("vk_community_messages", 23234681, {expanded: "1",tooltipButtonText: "Задайте вопрос или сделайте заказ прямо сейчас!"}); 
    </script> 
    </script> 
    Put this script tag to the <head> of your page 
    <script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script> 
    
    <script type="text/javascript"> 
    VK.init({apiId: 5494307, onlyWidgets: true}); 
    </script> -->
	
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter28610796 = new Ya.Metrika2({
                    id:28610796,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    ut:"noindex"
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/28610796?ut=noindex" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>