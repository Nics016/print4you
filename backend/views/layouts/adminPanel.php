<?php 
	use backend\assets\AppAsset;
	use yii\helpers\Html;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\widgets\Breadcrumbs;
	use common\widgets\Alert;
 ?>

<?=
    Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm();
 ?>
<link rel="icon" href="assets/images/favicon.ico">
<link rel="stylesheet" href="assets/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
<link rel="stylesheet" href="assets/css/font-icons/entypo/css/entypo.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
<link rel="stylesheet" href="assets/css/bootstrap.css">
<link rel="stylesheet" href="assets/css/neon-core.css">
<link rel="stylesheet" href="assets/css/neon-theme.css">
<link rel="stylesheet" href="assets/css/neon-forms.css">
<link rel="stylesheet" href="assets/css/custom.css">
<div class="page-container"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
    <div class="sidebar-menu">

        <div class="sidebar-menu-inner">
            
            <header class="logo-env">

                <!-- logo -->
                <div class="logo">
                    <a href="index.html">
                        <img src="assets/images/logo@2x.png" width="120" alt="" />
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
                <li class="active opened active has-sub">
                    <a href="index.html">
                        <i class="entypo-gauge"></i>
                        <span class="title">Главная</span>
                    </a>
                    <ul class="visible">
                        <li class="active">
                            <a href="index.html">
                                <span class="title">Пункт 1</span>
                            </a>
                        </li>
                        <li>
                            <a href="dashboard-2.html">
                                <span class="title">Пункт 2</span>
                            </a>
                        </li>
                        <li class="has-sub">
                            <a href="skin-black.html">
                                <span class="title">Цветовые схемы</span>
                            </a>
                            <ul>
                                <li>
                                    <a href="skin-black.html">
                                        <span class="title">Черная схема</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="skin-white.html">
                                        <span class="title">Белая схема</span>
                                    </a>
                                </li>                    
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="layout-api.html">
                        <i class="entypo-layout"></i>
                        <span class="title">Заказы</span>
                    </a>
                    <ul>
                        <li>
                            <a href="layout-api.html">
                                <span class="title">Все</span>
                            </a>
                        </li>
                        <li>
                            <a href="layout-api.html">
                                <span class="title">Новые</span>
                            </a>
                        </li>
                        <li>
                            <a href="layout-collapsed-sidebar.html">
                                <span class="title">На обработке</span>
                            </a>
                        </li>
                        <li>
                            <a href="layout-collapsed-sidebar.html">
                                <span class="title">Завершенные</span>
                            </a>
                        </li>     
                        <li>
                            <a href="layout-collapsed-sidebar.html">
                                <span class="title">Отмененные</span>
                            </a>
                        </li>                       
                    </ul>
                </li>
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

    <!-- Imported styles on this page -->
    <link rel="stylesheet" href="assets/js/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="assets/js/rickshaw/rickshaw.min.css">

    <!-- Bottom scripts (common) -->
    <script src="assets/js/gsap/TweenMax.min.js"></script>
    <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/joinable.js"></script>
    <script src="assets/js/resizeable.js"></script>
    <script src="assets/js/neon-api.js"></script>
    <script src="assets/js/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>


    <!-- Imported scripts on this page -->
    <script src="assets/js/jvectormap/jquery-jvectormap-europe-merc-en.js"></script>
    <script src="assets/js/jquery.sparkline.min.js"></script>
    <script src="assets/js/rickshaw/vendor/d3.v3.js"></script>
    <script src="assets/js/rickshaw/rickshaw.min.js"></script>
    <script src="assets/js/raphael-min.js"></script>
    <script src="assets/js/morris.min.js"></script>
    <script src="assets/js/toastr.js"></script>
    <script src="assets/js/neon-chat.js"></script>


    <!-- JavaScripts initializations and stuff -->
    <script src="assets/js/neon-custom.js"></script>


    <!-- Demo Settings -->
    <script src="assets/js/neon-demo.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>