<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\models\PagesSeo;
use common\models\ConstructorProducts;
use common\models\ConstructorCategories;
use common\models\ConstructorPrintSizes;

use frontend\components\basket\Basket;
use frontend\components\basket\ConstructorProduct;

class ConstructorController extends Controller {


	public function behaviors()
	{
	    return [
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'get-products'  => ['post'],
	                'add-to-cart'  => ['post'],
	            ],
	        ],
	    ];
	}


	public function actionAddToCart()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$basket = Basket::init(new ConstructorProduct());
		return [
			'status' => $basket->addAjaxProduct(),
			'count' => $basket->getPositionsCount(),
		]; 
				
	}

	public function actionGetProducts() {

		Yii::$app->response->format = Response::FORMAT_JSON;

		return ConstructorCategories::getConstructorArray();
	}

	public function actionIndex($alias = null, $cat_id = false) {
		$print_sizes = json_encode(ConstructorPrintSizes::find()->asArray()->all());
        $product_id = 0;

        if ($alias !== null) {
            $product = ConstructorProducts::find()->where(['alias' => $alias])->asArray()->one();
            
            if (!empty($product))
                $product_id = $product['id'];
        }


		return $this->render('index', [
			'print_sizes' => $print_sizes,
			'set_product' => (int)$product_id,
			'set_cat' => (int)$cat_id,
            'title' => $this->registerSeo($product_id, $cat_id, 'index'),
		]);
	}


	// регистрирует сео пола
    // возращает динамический h1 тэг
    private function registerSeo($product_id, $cat_id, $view_name = null) 
    {
        $seo = PagesSeo::findOne(['page_id' => 4]);

        $h1_title = 'Конструктор';

        $title = $seo->title ?? 'Конструктор';
        $description = $seo->description ?? '';
        $keywords = $seo->keywords ?? '';

        if ($product_id !== false) {

        	$model = ConstructorProducts::findOne(['id' => (int)$product_id]);
        	if ($model != null) {
        		$title = $model->seo_title ? $model->seo_title : $title;
        		$description = $model->seo_description ? $model->seo_description : $description;
        		$keywords = $model->seo_keywords ? $model->seo_keywords : $keywords;
        	}
        	 

        } else if ($cat_id !== false) {

        	$model = ConstructorCategories::findOne(['id' => (int)$cat_id]);
        	if ($model != null) {
        		$title = $model->seo_title ? $model->seo_title : $title;
        		$description = $model->seo_description ? $model->seo_description : $description;
                $keywords = $model->seo_keywords ? $model->seo_keywords : $keywords;
        		$h1_title = $model->h1_tag_title ? $model->h1_tag_title : $h1_title; 
        	}

        }

        Yii::$app->view->title = $title;

        Yii::$app->view->registerMetaTag([
            'name' => 'title',
            'content' => $title,
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $description ?? '',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $keywords,
        ]);

        // добавим заголовк последней модификации исходя из редактирования файлов
        if ($view_name === null) return;

        $path = $this->getViewPath() . '/' . $view_name . '.php';
        if (file_exists($path)) {

            $mt = filemtime($path);
            $mt_str = gmdate('D, d M Y H:i:s', $mt).' GMT';
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) 
                    && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mt)
                header('HTTP/1.1 304 Not Modified');
            else
                header('Last-Modified: '.$mt_str);
            
        } 

        return $h1_title;
    }
}
