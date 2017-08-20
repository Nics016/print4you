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
                'yii\widgets\ActiveFormAsset' => [
                    'depends' => [
                        'frontend\assets\jQueryAsset',
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
                'order-pay' => 'cart/order-pay',
                'request-call-sent' => 'site/request-call-sent',
                'dostavka' => 'site/dostavka',
                'franchise' => 'site/franchise',
                'register' => 'site/register',
                'contacts' => 'site/contacts',
                'sale' => 'site/sale',
                'nashi-gosti' => 'site/nashi-gosti',
                'nashi-clienty' => 'site/nashi-clienty',
                'forgot-password' => 'site/forgot-password',
                'register-success' => 'site/register-success',
                'constructor-category/<cat_id:\d+>/' => 'uslugi/constructor-category',
                'about' => 'site/about',
                '/reviews/page/<page:\d+>' => 'reviews/index',
                '/reviews/' => 'reviews/index',
            ],
        ],
        
    ],
    'params' => $params,
];