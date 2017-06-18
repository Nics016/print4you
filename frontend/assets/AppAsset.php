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
        'css/site.css',
        '/css/jquery.bxslider.css',
        '/css/styles.css',
        '/css/lightGallery.css',
        '/css/bootstrap.css',
    ];
    public $js = [
        '/js/bootstrap.min.js',
        '/js/lightgallery.js',
        '/js/jquery.bxslider.min.js',
        '/js/main.js?v=2',
    ];
    public $depends = [
        'frontend\assets\jQueryAsset'
    ];
}
