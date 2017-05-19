<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Print4you - Контакты';
?>

<main class="contacts">
        <div class="line1">
            <div class="container">
                <h1 class="title">Контакты</h1>
                <div class="contacts-map clearfix">
                    <div class="contacts-map-google graymap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1999.1059077423!2d30.361500015866994!3d59.9303847818732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469631bb14d4731d%3A0x545b6687b2935d3d!2sGoncharnaya+ul.%2C+2%2C+Sankt-Peterburg%2C+191036!5e0!3m2!1sen!2sru!4v1494866188314" width="615" height="415" frameborder="0" style="border:0" allowfullscreenscrolling="no" disableDefaultUI="true"></iframe>
                    </div>
                    <div class="contacts-map-info">
                        <h2>Студия печати PRINT4YOU!</h2>
                        <span>
                            На Фонтанке
                            (Центральный район) <br>
                            м.Гостиный двор (5 минут от метро) <br>
                            м. Маяковская (10 минут от метро) <br>
                            Набережная реки Фонтанки,38
                        </span>
                        <div class="contacts-map-info-element clearfix info-element1">
                            <img src="/img/contacts-alarm.png" alt="">
                            <span>
                                Часы работы: <br>
                                ПН-ВС - с 11:00 до 21:00 <br>
                                Без выходных.
                            </span>
                        </div>
                        <div class="contacts-map-info-element clearfix info-element2">
                            <img src="/img/contacts-phone.png" alt="">
                            <span>
                                Телефон - 309 28 48 (Добавочный 122) <br>
                                Почта - info@print4you.su
                            </span>
                        </div>
                    </div>
                </div>

                <div class="contacts-map clearfix">
                    <div class="contacts-map-google graymap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1998.881770650378!2d30.342434315867134!3d59.93410398187457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469631a7044cfbdd%3A0x536186ed326bfb6b!2snaberezhnaya+reki+Fontanki%2C+38%2C+Sankt-Peterburg%2C+191025!5e0!3m2!1sen!2sru!4v1494871194230" width="615" height="415" frameborder="0" style="border:0" allowfullscreenscrolling="no" disableDefaultUI="true"></iframe>
                    </div>
                    <div class="contacts-map-info">
                        <h2>Студия печати - цех  PRINT4YOU!</h2>
                        <span>
                            На Площади Восстания<br>
                            (Центральный район) <br>
                            м.Площадь Восстания (2 минуты от метро) <br>
                            ул. Гончарная,2
                        </span>
                        <div class="contacts-map-info-element clearfix info-element1">
                            <img src="/img/contacts-alarm.png" alt="">
                            <span>
                                Часы работы: <br>
                                ПН-ВС - с 11:00 до 21:00 <br>
                                Без выходных.
                            </span>
                        </div>
                        <div class="contacts-map-info-element clearfix info-element2">
                            <img src="/img/contacts-phone.png" alt="">
                            <span>
                                Телефон - 309 28 48 <br>
                                Почта - info@print4you.su
                            </span>
                        </div>
                    </div>
                </div>

                <div class="contacts-map clearfix">
                    <div class="contacts-map-google">
                        <img src="/img/contacts-othercity-map.png" alt="">
                    </div>
                    <div class="contacts-map-info">
                        <h2>Студия печати PRINT4YOU!</h2>
                        <span>
                            <strong>Печать на футболках в Великом Новгороде</strong>
                            ул.Мерецкова-Волосова 5 (У Кремля)
                        </span>
                        <div class="contacts-map-info-element clearfix info-element1">
                            <img src="/img/contacts-alarm.png" alt="">
                            <span>
                                Часы работы: <br>
                                Пн-Пв - с 10:00 до 20:00 <br>
                                Сб, Вс - с 10:00 до 18:00 <br>
                                Без выходных.
                            </span>
                        </div>
                        <div class="contacts-map-info-element clearfix info-element2">
                            <img src="/img/contacts-phone.png" alt="">
                            <span>
                                Телефон - +7 963 332 56 32 <br>
                                Почта - info@print4you.su
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="line2">
            <div class="container">
                <div class="contacts-questions">
                    <h2 class="title">
                        Есть вопросы? Задавайте!
                    </h2>
                    <div class="underline"></div>
                    <h3>
                        Поговорить, сделать заказ, обсудить сотрудничество
                    </h3>

                    <div class="contacts-questions-form-wrapper">
                        <form method="POST" class="contacts-questions-form clearfix">
                            <div class="contacts-questions-form-left">
                                <div class="contacts-questions-form-left-line">
                                    <input type="text" name="client_name" placeholder="Ваше имя">
                                    <img src="/img/topline-lk.png" alt="">
                                </div>
                                <div class="contacts-questions-form-left-line">
                                    <input type="text" name="client_phone" placeholder="Ваш телефон">
                                    <img src="/img/topline-phone.png" alt="">
                                </div>
                                <div class="contacts-questions-form-left-line">
                                    <input type="text" name="client_email" placeholder="Ваш Email">
                                    <img src="/img/topline-mail.png" alt="">
                                </div>
                            </div>
                            <div class="contacts-questions-form-right">
                                <div class="clearfix">
                                    <textarea name="client_msg" placeholder="Ваш вопрос"></textarea>
                                    <img src="/img/contacts-questions-form-msg.png" alt="">
                                </div>
                                <input type="submit" value="Отправить">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>