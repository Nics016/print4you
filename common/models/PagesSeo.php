<?php

namespace common\models;

use Yii;


class PagesSeo extends \yii\db\ActiveRecord
{   
  
    public static function tableName()
    {
        return 'pages_seo';
    }

    public function rules()
    {
        return [
            [['page_id'], 'integer'],
            [['page_id'], 'required'],
            [['page_id'], 'unique', 'message' => 'Вы уже заполняли теги для такой страницы!'],
            [['title', 'keywords', 'description'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Страница',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
        ];
    }



    const PAGES_ARRAY = [
        [
            'id' => 1,
            'name' => 'Главная',
        ],
        [
            'id' => 2,
            'name' => 'Услуги',
        ],
        [
            'id' => 3,
            'name' => 'Оплата и доставка',
        ],
        [
            'id' => 4,
            'name' => 'Конструктор',
        ],
        [
            'id' => 5,
            'name' => 'Отзывы',
        ],
        [
            'id' => 6,
            'name' => 'Франшиза',
        ],
        [
            'id' => 7,
            'name' => 'Контакты',
        ],
        [
            'id' => 8,
            'name' => 'Акции',
        ],
        [
            'id' => 9,
            'name' => 'Наши гости',
        ],
        [
            'id' => 10,
            'name' => 'О нас',
        ],
        [
            'id' => 11,
            'name' => 'Корзина',
        ],
        [
            'id' => 12,
            'name' => 'Прямая печать',
        ],
        [
            'id' => 13,
            'name' => 'Термоперенос',
        ],
        [
            'id' => 14,
            'name' => 'Шелкография',
        ],
        [
            'id' => 15,
            'name' => 'Сублимация',
        ],
        [
            'id' => 16,
            'name' => 'Регистрация',
        ],
        [
            'id' => 17,
            'name' => 'Оформление',
        ],
        [
            'id' => 18,
            'name' => 'Линый кабинет',
        ],
        [
            'id' => 19, 
            'name' => 'Ассортимент',
        ],
        [
            'id' => 20, 
            'name' => 'Быстро реализуем Вашу идею, перенесём на текстиль в течение 15 минут',
        ],
        [
            'id' => 21, 
            'name' => 'Качественно-гарантия печати - более 60-ти стирок',
        ],
        [
            'id' => 22, 
            'name' => 'Доступно-стабильно низкие цены',
        ],
        [
            'id' => 23,
            'name' => 'Дешево VS недорого: как понимать?',
        ],
        [
            'id' => 24,
            'name' => 'Сертификат',
        ],
        [
            'id' => 25,
            'name' => 'Оборудование',
        ],
        [
            'id' => 26,
            'name' => 'Наши клиенты',
        ],
        [
            'id' => 27,
            'name' => 'Технологии и цены',
        ],
        [
            'id' => 28,
            'name' => 'Текстиль',
        ],
        [
            'id' => 29,
            'name' => 'Политика конфидициальности',
        ],
    ];
}
