<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\models\ConstructorCategories;

class ConstructorController extends Controller {


	public function behaviors()
	{
	    return [
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'get-products'  => ['post'],
	            ],
	        ],
	    ];
	}

	public function actionGetProducts() {

		Yii::$app->response->format = Response::FORMAT_JSON;

		return ConstructorCategories::getConstructorArray();
	}

	public function actionIndex() {
		return $this->render('index');
	}

}
