<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<main class="main">
        <!-- LINE1 -->
        <div class="line1">
            <div class="container clearfix">
                <div class="left">
                    <h1>Печать <span>На футболках</span></h1>
                    <h3>
                        Срочная печать фото на футболках и другой одежде 
                        <br> 
                        за 15 минут оптом и в розницу в
                        <span class="strong">Print<b>4</b>you</span>
                    </h3>
                    <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">Сделать заказ</a>
                    <div class="left-elements">
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item1.png" alt="item1">
                            <span>Быстро - Печать на футболках <br>
                            занимает всего 15 минут</span>
                        </div>
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item2.png" alt="item2">
                            <span>Качественно - Футболки на заказ <br>
                            выдержат более 60 стирок</span>
                        </div>
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item3.png" alt="item3">
                            <span>Доступно - Самая лучшая цена <br> 
                            в Санкт-Петербурге</span>
                        </div>
                    </div>
                </div>
                <img src="/img/main-line1-david.png" alt="david" class="right">
            </div>
        </div>
        <!-- END OF LINE1 -->

        <!-- LINE2 -->
        <div class="main-slider-container clearfix">
            <div class="sales-slider-container">
                <ul class="sales-slider">
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/first-order.jpg" alt="first-order">
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/20_sale.jpg" alt="20_sale">
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/sale_design.png" alt="sale_design">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="sales-slider-container">
                <ul class="sales-slider">
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/delivery.png" alt="delivery">
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/konez-leta.jpg" alt="konez-leta">
                        </a>
                    </li>
                    <li>
                        <a href="<?= Url::to(['/sale/']) ?>" class="sale-slider-link" target="_blank">
                            <img src="/assets/images/20_sale_2.jpg" alt="20_sale_2">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END OF LINE2 -->

        <!-- LINE3 -->
        <div class="line3">
            <div class="container">
                <h2 class="subtitle">
                    Почему именно
                    <span class="title withbg">Print4you?</span>
                </h2>
                <div class="underline"></div>
                <div class="elements clearfix">
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item1.png" alt="">
                        <div class="elements-item-info">
                            <h3>Быстро</h3>
                            <p>
                                Печать фото, надписей, <br>
                                логотипов, рисунков, номера <br>
                                и любых изображений <br>
                                на футболках за 15 минут
                            </p>
                        </div>
                    </div>
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item2.png" alt="">
                        <div class="elements-item-info">
                            <h3>Качественно</h3>
                            <p>
                                Футболка с нанесением <br>
                                выдержит более 60-ти стирок. <br>
                                Собственный пошив футболок <br>
                                под печать. Используем только <br>
                                качественные методы печати: <br>
                                Прямая печать на футболках. 
                                Печать футболок методом 
                                шелкография
                            </p>
                        </div>
                    </div>
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item3.png" alt="">
                        <div class="elements-item-info">
                            <h3>Доступно</h3>
                            <p>
                                Самая низкая цена печати на <br> 
                                футболках в СПб. Печать 
                                на футболках недорого оптом 
                                и в розницу
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- END OF LINE3 -->

        <!-- LINE4 -->
        <div class="line4">
            <div class="container">
                <div class="line4-video" onclick="playVideo(1)" id="video-1">                   
                </div>
            </div>
        </div>
        <!-- END OF LINE4 -->

        <!-- LINE5 -->
        <div class="line5">
            <div class="container">
                <h2 class="title">Наши услуги</h2>
                <div class="underline"></div>
                <div class="elements clearfix">

                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-img-container">
                            <img src="/img/services-pic1.jpg" alt="cifrovaya" class="elements-item-img">
                        </a>
                        <a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-title">Цифровая печать</a>
                        <a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="elements-item-text">(Прямая печать)</a>
                        <a href="<?= Url::to(['uslugi/cifrovaya']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>

                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-img-container">
                            <img src="/img/services-pic2.jpg" alt="termoperenos" class="elements-item-img">
                        </a>
                        <a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-title">Печать плёнкой</a>
                        <a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="elements-item-text">(Термоперенос)</a>
                        <a href="<?= Url::to(['uslugi/termoperenos']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>

                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-img-container">
                            <img src="/img/services-pic3.jpg" alt="shelkography" class="elements-item-img">
                        </a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-title">Трафаретная печать</a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-text">(Шелкография)</a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>

                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-img-container">
                            <img src="/img/services-pic4.jpg" alt="sublimation" class="elements-item-img">
                        </a>
                        <a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-title">печать на синтетике</a>
                        <a href="<?= Url::to(['uslugi/sublimation']) ?>" class="elements-item-text">(Сублимация)</a>
                        <a href="<?= Url::to(['uslugi/sublimation']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <?php 
                    for ($i = 0; $i < count($categories); $i++):
                        $id = $categories[$i]['id'];
                        $name = $categories[$i]['name'];
                        $img = $categories[$i]['img'];
                        $alt = $categories[$i]['img_alt'];
                    ?>
                        <div class="elements-item">
                            <a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="elements-item-img-container">
                                <img src="<?= $img ?>" alt="<?= $alt ?>" class="elements-item-img">
                            </a>
                            <a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="elements-item-title">
                                <?= $name ?>
                            </a>
                            <a href="<?= Url::to(['uslugi/constructor-category', 'cat_id' => $id]) ?>" class="whiteBtn">
                                Подробнее
                            </a>
                        </div>

                    <?php endfor;?>
                </div>
                <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn bigBtn">
                    Смотреть весь ассортимент <span class="strong"> > </span>
                </a>
            </div>
        </div>
        <!-- END OF LINE5 -->

        <!-- LINE6 -->
        <div class="line6">
            <div class="container">
                <img src="/img/line5-tshirt.png" alt="" class="tshirt">
                <div class="title-block clearfix">
                    <div class="title-block-left">
                        <h2 class="subtitle">
                            Печать на футболках в СПБ
                            <span class="title">Наша специализация</span>
                        </h2>
                    </div>
                    <span class="title-block-right">
                        Принципы успешной деятельности - удачное соотношение <br>
                        цены и качества производимого продукта. Мы преуспели <br>
                        в обоих критериях, выпуская футболки на заказ 
                    </span>
                </div>
                <span class="subtitle-block">
                    Наша студия осуществляет печать на футболках методом прямого переноса (цифровая) и термопереноса. <br> 
                    Любая сложность работ, самые сжатые сроки. Индивидуально и с высоким качеством на любых объемах печати. <br>
                    Нас выгодно отличают:
                </span>
                <!-- ELEMENTS -->
                <div class="elements clearfix">
                    <div class="elements-item">
                        <span class="elements-item-num">01</span>
                        <div class="elements-item-info">
                            <h3>Качественный <br>сервис</h3>
                            <span>
                                Индивидуально подойдем <br>
                                к каждому клиенту. Не работаем <br>
                                на поток. Нужно напечатать <br>
                                1 футболку? или 1000? <br>
                                В любом случае предложим <br>
                                индивидуальный подход <br>
                                и полностью удовлетворим <br>
                                все пожелания
                            </span>
                        </div>
                    </div>
                    <div class="elements-item">
                        <span class="elements-item-num">02</span>
                        <div class="elements-item-info">
                            <h3>Профессиональное <br>оборудование</h3>
                            <span>
                                Мы не печатаем кустарным <br>
                                способом на самодельных <br>
                                аппаратах. Качество печати <br>
                                такое же, как и в ведущих <br>
                                студиях Европы при совершенно <br>
                                смешных ценах.
                            </span>
                        </div>
                    </div>
                    <div class="elements-item">
                        <span class="elements-item-num">03</span>
                        <div class="elements-item-info">
                            <h3>Качественный текстиль <br>и расходные материалы.</h3>
                            <span>
                                Мы не экономим на футболках. <br>
                                Наши футболки приятны к телу, <br>
                                их не стыдно одеть на любое <br>
                                мероприятие! Выдерживают <br>
                                огромное количество стирок.
                            </span>
                        </div>
                    </div>
                    <div class="elements-item">
                        <span class="elements-item-num">04</span>
                        <div class="elements-item-info">
                            <h3>Можно выбрать <br>готовые принты</h3>
                            <span>
                                Огромный ассортимент футболок <br>
                                с надписями, готовыми принтами. <br>
                                Детские, стильные мужские, <br>
                                элегантные женские футболки <br>
                                в СПб с печатью высочайшего <br>
                                уровня.
                            </span>
                        </div>
                    </div>
                    <div class="elements-item">
                        <span class="elements-item-num">05</span>
                        <div class="elements-item-info">
                            <h3>Помощь <br>в индивидуальном <br>дизайне</h3>
                            <span>
                                Совершенно бесплатно
                            </span>
                        </div>
                    </div>
                    <div class="elements-item">
                        <span class="elements-item-num">06</span>
                        <div class="elements-item-info">
                            <h3>Мы любим Вас <br>И свою работу</h3>
                            <span>
                                
                            </span>
                        </div>
                    </div>
                </div>
                <!-- END OF ELEMENTS -->
                <div class="cities clearfix">
                    <img src="/img/main-line6-photo.jpg" alt="" class="cities-left">
                    <div class="cities-right">
                        <h2 class="title">
                            Футболки Санкт-Петербурга <br>
                            с принтом – <span class="strong">дело наших рук!</span>
                        </h2>
                        <p>PRINT4YOU выполняет яркую печать на футболках в городах:</p>
                        <div class="cities-right-list">
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-red.png" alt="">
                                <span><span class="strong">Санкт-Петербург</span></span>
                            </div>
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-red.png" alt="">
                                <span><span class="strong">Великий Новгород</span></span>
                            </div>
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-gray.png" alt="">
                                <span><span class="strong">Москва</span> - открытие в 2017 г.</span>
                            </div>
                        </div>
                        <a href="<?= Url::to(['site/contacts']) ?>" class="whiteBtn">Узнать адрес</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF LINE6 -->
    </main>