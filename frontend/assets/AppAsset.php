<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.css',
        '/css/jquery.bxslider.css',
        '/css/styles.css',
        '/font-awesome/css/font-awesome.min.css',
        '/js/lightgallery/css/lightgallery.css',
    ];

    public $js = [
        '/js/bootstrap.min.js',
        '/js/lightgallery/js/lightgallery.js',
        '/js/jquery.bxslider.min.js',
    ];
    public $depends = [
        'frontend\assets\jQueryAsset',
    ];

    public function __construct()
    {
        $this->css[] = [
            '/css/site.css?v=' . time(), // у этого стиля приоритете больше!
        ];
        $this->js[] = [
            '/js/main.js?v=' . time(),
        ];
    }
}
