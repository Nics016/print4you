<?php 

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\components\basket\Basket;
use common\models\ConstructorCategories;

$mug_alias = ConstructorCategories::find()->select('alias')->where(['id' => 49])->one();
?>

<div class="topmenu">
    <div class="container clearfix">
        <a href="<?= Url::home() ?>" class="topmenu-left">
            <img src="/img/header-logo.png" alt="logo">
            <span>Печатаем и шьем <br> для вас</span>
        </a>
        <div class="topmenu-right">
            <!-- TOP-RIGHT-ABOVE -->
            <div class="topmenu-right-above clearfix">
                <span class="topmenu-right-above-elem1">
                    Быстро - Печать на футболках занимает всего 15 минут!
                </span>
                <noindex>
                    <a href="https://www.google.com/maps?ll=59.934104,30.344623&z=16&t=m&hl=ru-RU&gl=RU&mapclient=embed&q=naberezhnaya+reki+Fontanki,+38+Sankt-Peterburg+191025" class="topmenu-right-above-elem2 clearfix" target="_blank" rel="nofollow">
                        <img src="/img/header-pin.png" alt="location">
                        <span>
                            М. Гостиный двор <br>
                            Наб.Реки Фонтанки 38 (в арке)
                        </span>
                    </a>
                    <a href="https://www.google.com/maps/place/Goncharnaya+ul.,+2,+Sankt-Peterburg,+Russia,+191036/@59.930385,30.363689,16z/data=!4m5!3m4!1s0x469631bb14d4731d:0x545b6687b2935d3d!8m2!3d59.9303848!4d30.3636887?hl=ru-RU" class="topmenu-right-above-elem3 clearfix" target="_blank" rel="nofollow">
                        <img src="/img/header-pin.png" alt="location">
                        <span>
                            М.Площадь Восстания <br>
                            Гончарная,2
                        </span>
                    </a>
                </noindex>
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
                        <a href="<?= Url::to(['uslugi/']) ?>">Услуги</a>
                        <ul class="submenu">
                            <li>
                                <?= Html::a('Технологии и цены', ['uslugi/technologii-i-ceny']) ?>
                            </li>
                            <li>
                                <?= Html::a('Кружки', ['uslugi/constructor-category', 'alias' => $mug_alias->alias]) ?>
                            </li>
                            <li>
                                <?= Html::a('Текстиль', ['uslugi/tekstil']) ?>
                            </li>
                            <li>
                                <?= Html::a('Сертификат', ['info/sertifikat']) ?>
                            </li> 
                        </ul>
                    </span>

                    <a href="<?= Url::to(['site/dostavka']) ?>">Оплата и доставка</a>
                    <a href="<?= Url::to(['/constructor']) ?>" class="red-label">Конструктор</a>
                    <a href="<?= Url::to(['reviews/']) ?>">Отзывы</a>
                    <a href="<?= Url::to(['site/franchise']) ?>">Франшиза</a>
                </nav>   

                <nav>
                    <a href="<?= Url::to(['site/contacts']) ?>">Контакты</a>
                    <a href="<?= Url::to(['site/sale']) ?>">Акции</a>
                    <a href="<?= Url::to(['site/nashi-clienty']) ?>">Наши клиенты</a>
                    <a href="<?= Url::to(['site/nashi-gosti']) ?>">Наши гости</a>
                    <a href="<?= Url::to(['info/oborudovanie']) ?>">Оборудование</a>

                    <span  class="submenu-container">
                        <a href="<?= Url::to(['site/about']) ?>">О нас</a>
                        <ul class="submenu">
                            <li>
                                <?= Html::a('Быстро реализуем Вашу идею, перенесём на текстиль в течение 15 минут', ['info/za-15-minut']) ?>
                            </li>
                            <li>
                                <?= Html::a('Качественно-гарантия печати - более 60-ти стирок', ['info/futbolki-optom']) ?>
                            </li>
                            <li>
                                <?= Html::a('Доступно-стабильно низкие цены', ['info/deshevo-print4you']) ?>
                            </li>
                            <li>
                                <?= Html::a('Дешево VS недорого: как понимать?', ['info/nedorogo']) ?>
                            </li>
                        </ul>
                    </span>
                    

                    <a href="<?= Url::to(['cart/']) ?>">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        Корзина
                        <?php $basket_count = Basket::init()->getPositionsCount()?>
                        <span id="basket-count">
                        <?= $basket_count > 0 ? " ( $basket_count ) " : '' ?>
                        </span>
                    </a>
                </nav>
                
                <noindex>
                    <a href="<?= $instaLink ?>" class="topmenu-right-below-in" target="_blank" rel="nofollow">
                        <img src="/img/header-in.png" alt="instagram">
                    </a>
                    <a href="<?= $vkLink ?>" class="topmenu-right-below-vk" target="_blank" rel="nofollow">
                        <img src="/img/header-vk.png" alt="vk">
                    </a>
                </noindex>
            </div>
        </div>
    </div>
        </div>