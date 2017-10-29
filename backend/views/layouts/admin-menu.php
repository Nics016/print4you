<?php 
use yii\helpers\Url;
use common\models\Orders;
use common\models\Requests;

$requests = Requests::find()->count();
?>

<ul id="main-menu" class="main-menu">
    <!-- add class "multiple-expanded" to allow multiple submenus to open -->
    <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
    <li class="has-sub opened active">
        <a href="layout-api.html">
            <i class="entypo-layout"></i>
            <span class="title">Заказы</span>
        </a>
        <ul>
            <li class="active">
                <a href="<?= Url::toRoute(['orders/index']) ?>">
                    <span class="title">Все</span>
                </a>
            </li>

            <li class="active">
                <a href="<?= Url::toRoute(['orders/create']) ?>">
                    <span class="title">Создать</span>
                </a>
            </li>

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
            <li>
                <a href="<?= Url::toRoute(['orders/cancelled']) ?>">
                    <span class="title">Отмененные</span>
                </a>
            </li>  
       
        </ul>
    </li>
    
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
            <li class="has-sub">
                <a href="<?= Url::toRoute(['common-user/index']) ?>">
                    <span class="title">Клиенты</span>
                </a>
                <ul>
                    <li><a href="<?= Url::toRoute(['common-user/index']) ?>"><span class="title">Все</span></a></li>
                    <li><a href="<?= Url::toRoute(['common-user/create']) ?>"><span class="title">Создать нового</span></a></li>
                </ul>
            </li>
            <li class="has-sub">
                <a href="<?= Url::toRoute(['stock/index']) ?>">
                    <span class="title">Склад</span>
                </a>
                <ul>
                    <li>
                        <a href="<?= Url::toRoute(['constructor-sklad/']) ?>">
                            <span class="title">Склад конструктора</span>
                        </a>
                    </li>
                    <li><a href="<?= Url::toRoute(['stock/index']) ?>"><span class="title">Наличие</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-requests/index']) ?>"><span class="title">Заявки</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-requests/create']) ?>"><span class="title">Новая заявка</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-colors/index']) ?>"><span class="title">Цвета краски</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-colors/create']) ?>"><span class="title">Новый цвет краски</span></a></li>
                </ul>
            </li>
            <li>
                <a href="<?= Url::toRoute(['user/statistics']) ?>">
                    <span class="title">Статистика</span>
                </a>
            </li> 
            <li>
                <a href="<?= Url::toRoute(['user-settings/update', 'id' => 1]) ?>">
                    <span class="title">Настройки email и ссылок</span>
                </a>
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
                    <span class="title">Последовательность категорий, размеры и материалы</span>
                </a>
                <a href="<?= Url::toRoute(['constructor-categories/']) ?>">
                    <span class="title">Категории</span>
                </a>
                <a href="<?= Url::toRoute(['constructor-products/']) ?>">
                    <span class="title">Товары и цвета</span>
                </a>
                <a href="<?= Url::toRoute(['constructor-print/']) ?>">
                    <span class="title">Управление печатью</span>
                </a>
                <a href="<?= Url::toRoute(['constructor-print-prices/']) ?>">
                    <span class="title">Цены на печать</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="<?= Url::toRoute(['pages-seo/']) ?>">
            <i class="entypo-feather"></i>
            <span class="title">Сео страниц</span>
        </a>
    </li>
    <li>
        <a href="<?= Url::toRoute(['requests/index']) ?>">
            <i class="entypo-mobile"></i>
            <span class="title">
                Заявки на звонок
                <em class="neworders-count"><?= $requests ?></em>
            </span>
        </a>
    </li>
    <li>
        <a href="<?= Url::toRoute(['reviews/']) ?>">
            <i class="entypo-comment"></i>
            <span class="title">Отзывы</span>
        </a>
    </li>  

</ul>