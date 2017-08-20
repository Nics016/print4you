<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/bootstrap.min.css',
        '/css/jquery.bxslider.min.css',
        '/font-awesome/css/font-awesome.min.css',
        '/js/lightgallery/css/lightgallery.min.css',
    ];

    public $js = [
        '/js/bootstrap.min.js',
        '/js/lightgallery/js/lightgallery.min.js',
        '/js/jquery.bxslider.min.js',
        '/js/maskedinput.js',
    ];
    public $depends = [
        'frontend\assets\jQueryAsset',
        'yii\web\YiiAsset',
    ];

    public function __construct()
    {   
        $site_css = filemtime(Yii::getAlias('@frontend') . '/web/css/site.css');
        $styles_css = filemtime(Yii::getAlias('@frontend') . '/web/css/styles.css');
        $main_js = filemtime(Yii::getAlias('@frontend') . '/web/js/main.js');

        $this->css[] = [
            '/css/styles.css?v=' . $styles_css,
        ];

        $this->css[] = [
            '/css/site.css?v=' . $site_css, // у этого стиля приоритете больше!
        ];

        $this->js[] = [
            '/js/main.js?v=' . $main_js,
        ];
    }
}
