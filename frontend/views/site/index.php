<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Print for you! Закажи свой оригинальный принт';
?>
<main class="main">
        <!-- LINE1 -->
        <div class="line1">
            <div class="container clearfix">
                <div class="left">
                    <h1>Печать</h1>
                    <h2>На футболках</h2>
                    <h3>Срочная печать фото на футболках и другой одежде <br> за 15 минут оптом и в розницу в <strong>Print<b>4</b>you</strong></h3>
                    <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">Сделать заказ</a>
                    <div class="left-elements">
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item1.png" alt="">
                            <span>Быстро - Печать на футболках <br>
                            занимает всего 15 минут</span>
                        </div>
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item2.png" alt="">
                            <span>Качественно - Футболки на заказ <br>
                            выдержат более 60 стирок</span>
                        </div>
                        <div class="left-elements-item clearfix">
                            <img src="/img/main-line1-item3.png" alt="">
                            <span>Доступно - Самая лучшая цена <br> 
                            в Санкт-Петербурге</span>
                        </div>
                    </div>
                </div>
                <img src="/img/main-line1-david.png" alt="" class="right">
            </div>
        </div>
        <!-- END OF LINE1 -->

        <!-- LINE2 -->
        <div class="line2">
            <div class="container">
                <div class="info-box clearfix">
                    <div class="info-box-btns">
                        <img src="/img/main-line2-right.png" alt="" class="info-box-btns-right">
                        <img src="/img/main-line2-left.png" alt="" class="info-box-btns-left">
                    </div>
                </div>              
            </div>
        </div>
        <!-- END OF LINE2 -->

        <!-- LINE3 -->
        <div class="line3">
            <div class="container">
                <h2 class="subtitle">Почему именно</h2>
                <h1 class="title withbg">Print4you?</h1>
                <div class="underline"></div>
                <div class="elements clearfix">
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item1.png" alt="">
                        <div class="elements-item-info">
                            <h2>Быстро</h2>
                            <span>
                                Печать фото, надписей, <br>
                                логотипов, рисунков, номера <br>
                                и любых изображений <br>
                                на футболках за 15 минут
                            </span>
                        </div>
                    </div>
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item2.png" alt="">
                        <div class="elements-item-info">
                            <h2>Качественно</h2>
                            <span>
                                Футболка с нанесением <br>
                                выдержит более 60-ти стирок. <br>
                                Собственный пошив футболок <br>
                                под печать. Используем только <br>
                                качественные методы печати: <br>
                                Прямая печать на футболках. 
                                Печать футболок методом 
                                шелкография
                            </span>
                        </div>
                    </div>
                    <div class="elements-item clearfix">
                        <img src="/img/main-line3-item3.png" alt="">
                        <div class="elements-item-info">
                            <h2>Доступно</h2>
                            <span>
                                Самая низкая цена печати на <br> 
                                футболках в СПб. Печать 
                                на футболках недорого оптом 
                                и в розницу
                            </span>
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
                <h1 class="title">Наши услуги</h1>
                <div class="underline"></div>
                <div class="elements clearfix">
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic1.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Цифровая печать</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-text">(Прямая печать)</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic2.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Печать плёнкой</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-text">(Термоперенос)</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>"><img src="/img/services-pic3.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-title">Трафаретная печать</a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="elements-item-text">(Шелкография)</a>
                        <a href="<?= Url::to(['uslugi/shelkography']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic4.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">печать на синтетике</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-text">(Сублимация)</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic5.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Футболки мужские</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic6.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Футболки женские</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic7.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Свитшоты <br> и толстовки мужские</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                    <div class="elements-item">
                        <a href="<?= Url::to(['uslugi/assorty']) ?>"><img src="/img/services-pic8.jpg" alt=""></a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="elements-item-title">Свитшоты <br> и толстовки женские</a>
                        <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn">
                            Подробнее
                        </a>
                    </div>
                </div>
                <a href="<?= Url::to(['uslugi/assorty']) ?>" class="whiteBtn bigBtn">
                    Смотреть весь ассортимент <strong> > </strong>
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
                        </h2>
                        <h1 class="title">
                            Наша специализация
                        </h1>
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
                    <img src="/img/main-line6-photo.png" alt="" class="cities-left">
                    <div class="cities-right">
                        <h2 class="title">
                            Футболки Санкт-Петербурга <br>
                            с принтом – <strong>дело наших рук!</strong>
                        </h2>
                        <p>PRINT4YOU выполняет яркую печать на футболках в городах:</p>
                        <div class="cities-right-list">
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-red.png" alt="">
                                <span><strong>Санкт-Петербург</strong></span>
                            </div>
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-red.png" alt="">
                                <span><strong>Великий Новгород</strong></span>
                            </div>
                            <div class="cities-right-list-item">
                                <img src="/img/main-line6-circle-gray.png" alt="">
                                <span><strong>Москва</strong> - открытие в 2017 г.</span>
                            </div>
                        </div>
                        <a href="<?= Url::to(['site/contacts']) ?>" class="whiteBtn">Узнать адрес</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF LINE6 -->
    </main>