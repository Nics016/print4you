<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\CommonUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js'=>[],
                ],
                'yii\validators\ValidationAsset' => [
                    'depends' => [
                        'frontend\assets\jQueryAsset',
                        'frontend\assets\AppAsset',
                        'yii\web\YiiAsset',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                'cabinet' => 'site/cabinet',
                'checkout' => 'cart/checkout',
                'order-created' => 'cart/order-created',
                'request-call-sent' => 'site/request-call-sent',
                'dostavka' => 'site/dostavka',
                'franchise' => 'site/franchise',
                'register' => 'site/register',
                'contacts' => 'site/contacts',
                'register-success' => 'site/register-success',
                'about' => 'site/about',
            ],
        ],
        
    ],
    'params' => $params,
];