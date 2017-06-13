<?php 
	use backend\assets\AppAsset;
	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use common\widgets\Alert; 

    use yii\helpers\Url;
    use backend\models\User;
    use common\models\Orders;

    AppAsset::register($this);
 ?>


 <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?php $this->beginPage() ?>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- <script src="/assets/js/jquery-1.11.3.min.js"></script> -->
    <script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
    <?php $this->head() ?>
    <link rel="icon" href="/assets/images/favicon.ico">
    <!-- <link rel="stylesheet" href="/assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css"> -->
    <link rel="stylesheet" href="/assets/css/font-icons/entypo/css/entypo.css">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/neon-core.css">
    <link rel="stylesheet" href="/assets/css/neon-theme.css">
    <link rel="stylesheet" href="/assets/css/neon-forms.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
    <link rel="stylesheet" href="/assets/css/print4you-adminpanel.css">

    <!-- MORRIS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

</head>

<body class="page-body  page-fade">
<?php $this->beginBody() ?>
<?=
    Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm();
 ?>
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    <div class="sidebar-menu">

        <div class="sidebar-menu-inner">
            
            <header class="logo-env">

                <!-- logo -->
                <div class="logo">
                    <a href="<?= Url::home() ?>" style="font-size: 20px;">
                        Print4you admin
                    </a>
                </div>

                <!-- logo collapse icon -->
                <div class="sidebar-collapse">
                    <a href="#" class="sidebar-collapse-icon"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

                                
                <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
                <div class="sidebar-mobile-menu visible-xs">
                    <a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
                        <i class="entypo-menu"></i>
                    </a>
                </div>

            </header>

            <ul id="main-menu" class="main-menu">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
                <li class="has-sub opened active">
                    <a href="layout-api.html">
                        <i class="entypo-layout"></i>
                        <span class="title">Заказы</span>
                    </a>
                    <ul>
                    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
                        <li class="active">
                            <a href="<?= Url::toRoute(['orders/index']) ?>">
                                <span class="title">Все</span>
                            </a>
                        </li>
                    <?php endif; ?>
                        <li>
                            <a href="<?= Url::toRoute(['orders/new']) ?>">
                                <span class="title">Новые 
                                    <?php $newOrders = Orders::getNewOrdersCount(Yii::$app->user) ?>
                                    <?php if ($newOrders != ""): ?>
                                        <em class="neworders-count">
                                            <?= $newOrders ?>
                                        </em>
                                    <?php endif; ?>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::toRoute(['orders/proccessing']) ?>">
                                <span class="title">В обработке</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Url::toRoute(['orders/completed']) ?>">
                                <span class="title">Завершенные</span>
                            </a>
                        </li>     
                    <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
                        <li>
                            <a href="<?= Url::toRoute(['orders/cancelled']) ?>">
                                <span class="title">Отмененные</span>
                            </a>
                        </li>  
                    <?php endif; ?>                     
                    </ul>
                </li>
                
            <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
                <li class="has-sub">
                    <a href="layout-api.html">
                        <i class="entypo-monitor"></i>
                        <span class="title">Управление</span>
                    </a>
                    <ul>
                        <li class="has-sub">
                            <a href="layout-api.html">
                                <span class="title">Пользователи</span>
                            </a>
                            <ul>
                                <li><a href="<?= Url::toRoute(['user/index']) ?>"><span class="title">Все</span></a></li>
                                <li><a href="<?= Url::toRoute(['user/create']) ?>"><span class="title">Создать нового</span></a></li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a href="layout-api.html">
                                <span class="title">Офисы</span>
                            </a>
                            <ul>
                                <li><a href="<?= Url::toRoute(['office/index']) ?>"><span class="title">Все</span></a></li>
                                <li><a href="<?= Url::toRoute(['office/create']) ?>"><span class="title">Создать новый</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?= Url::toRoute(['user/statistics']) ?>">
                                <span class="title">Статистика</span>
                            </a>
                        </li>             
                    </ul>
                    <ul>
                        <li class="has-sub">
                            <a href="<?= Url::toRoute(['common-user/index']) ?>">
                                <span class="title">Клиенты</span>
                            </a>
                            <ul>
                                <li><a href="<?= Url::toRoute(['common-user/index']) ?>"><span class="title">Все</span></a></li>
                                <li><a href="<?= Url::toRoute(['common-user/create']) ?>"><span class="title">Создать нового</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="layout-api.html">
                        <i class="entypo-cog"></i>
                        <span class="title">Конструктор</span>
                    </a>
                    <ul>
                        <li>
                            <a href="<?= Url::toRoute(['constructor-categories-sizes/']) ?>">
                                <span class="title">Категории и размеры</span>
                            </a>
                            <a href="<?= Url::toRoute(['constructor-products/']) ?>">
                                <span class="title">Товары и цвета</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif ?>
            </ul>
        </div>
    </div>
    <div class="main-content">
        <?= $content ?>   
        
        <!-- Footer -->
        <footer class="main">
            
            &copy; 2017 | Сайт разработали Степан Куштуев и Никита Абрашнев
        
        </footer>
    </div>
</div>
    <!-- NEON SCRIPTS -->
    <!-- Imported styles on this page -->
    <link rel="stylesheet" href="/assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/assets/js/rickshaw/rickshaw.min.css">

    <!-- Bottom scripts (common) -->
    <script src="/assets/js/gsap/TweenMax.min.js"></script>
    <script src="/assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script src="/assets/js/bootstrap.js"></script>
    <script src="/assets/js/joinable.js"></script>
    <script src="/assets/js/resizeable.js"></script>
    <script src="/assets/js/neon-api.js"></script>
    <script src="/assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>


    <!-- Imported scripts on this page -->
    <script src="/assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
    <script src="/assets/js/jquery.sparkline.min.js"></script>
    <script src="/assets/js/rickshaw/vendor/d3.v3.js"></script>
    <script src="/assets/js/rickshaw/rickshaw.min.js"></script>
    <script src="/assets/js/raphael-min.js"></script>
    <script src="/assets/js/morris.min.js"></script>
    <script src="/assets/js/toastr.js"></script>
    <script src="/assets/js/neon-chat.js"></script>


    <!-- JavaScripts initializations and stuff -->
    <script src="/assets/js/neon-custom.js"></script>

    <!-- Demo Settings -->
    <script src="/assets/js/neon-demo.js"></script>
    <!-- END OF NEON-SCRIPTS -->

    <script>
        ///////////////////////////////////////////////////////////
        // Скрипт подсветки текущей страницы белым цветом в меню //
        ///////////////////////////////////////////////////////////

        // Убираем все классы "active" и "opened"
        $('.active').removeClass("active");
        $('.opened').removeClass("opened");

        // Получаем URL текущей открытой страницы без параметров
        var curHref = window.location.href.toString()
            .split(window.location.host)[1]
                .split('&')[0];

        // Проходим по каждой ссылке в меню. Если она = открытой, 
        // помечаем её классом "active", а все li над ней, которые имеют 
        // класс has-sub, помечаем классами "active" и "opened"
        $('.sidebar-menu a').each(function(){
            var curMenuLinkURL = $(this).attr('href');
            if (curMenuLinkURL == curHref){
                $(this).parent().addClass("active");
                var parentLi = $(this).parent().parent().parent();
                while (parentLi.is(".has-sub")){
                    parentLi.addClass("active opened");
                    parentLi = parentLi.parent().parent();
                    if (parentLi.is("div"))
                        break;
                }
                return false;
            }
        });

    </script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>