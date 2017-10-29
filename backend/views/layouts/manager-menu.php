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

            <li class="has-sub">
                <a href="<?= Url::toRoute(['stock/index']) ?>">
                    <span class="title">Склад</span>
                </a>
                <ul>
                    <li><a href="<?= Url::toRoute(['stock/index']) ?>"><span class="title">Наличие</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-requests/index']) ?>"><span class="title">Заявки</span></a></li>
                    <li><a href="<?= Url::toRoute(['stock-requests/create']) ?>"><span class="title">Новая заявка</span></a></li>
                </ul>
            </li>                   
        </ul>
    </li>
    

    <li>
        <a href="<?= Url::toRoute(['common-user/create']) ?>">
            <i class="entypo-user"></i>
            <span class="title">Создать пользователя</span>
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


</ul>