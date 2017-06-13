<?php
return [
    'language' => 'ru',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;port=5432;dbname=print4you',
            'username' => 'postgres',
            'password' => '333777aaa',
            'charset' => 'UTF8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'formatter' => [
            'dateFormat' => 'dd/MM/yyyy',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
       ],
    ],
];
