<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\models\ConstructorCategories;

use frontend\components\Basket;

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
		$product_id = Yii::$app->request->post('product_id');
		$color_id = Yii::$app->request->post('color_id');
		$size_id = Yii::$app->request->post('size_id');
		$front_base64 = Yii::$app->request->post('front_base64');
		$back_base64 = Yii::$app->request->post('back_base64');
		$status = Basket::init()
					->addConstructorProduct($product_id, $color_id, $size_id, $front_base64, $back_base64);
		return ['status' => $status]; 
				
	}

	public function actionGetProducts() {

		Yii::$app->response->format = Response::FORMAT_JSON;

		return ConstructorCategories::getConstructorArray();
	}

	public function actionIndex() {
		return $this->render('index');
	}

}
