<?php 
use yii\helpers\Url;
use common\models\Orders;
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
        </ul>
    </li>

</ul>