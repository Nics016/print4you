<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

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

		return [
			[
				[
					'name' => 'Футболки мужские',
					'id' => 1,
					'products' => [
						[
							'name' => 'Футболка хлопок 100%',
							'id' => 1,
							'description' => 'Описание футболки 100%',
							'image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/front.png',
							'price' => 750,
							'colors' => [
								[
									'name' => 'Белый',
									'id' => 1,
									'color_value' => '#fffff',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/back.png',
									'sizes' => [
										[
											'id' => 1,
											'size' => 'X'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 4,
											'size' => 'S'
										],

									],
								],

								[
									'name' => 'Черный',
									'id' => 2,
									'color_value' => '#000000',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_2/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_2/back.png',
									'sizes' => [
										[
											'id' => 4,
											'size' => 'XL'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 5,
											'size' => 'XXl'
										],
									],
								],
							],
						],
						[
							'name' => 'Футболка хлопок 10%',
							'id' => 2,
							'image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/front.png',
							'description' => 'Супер описание футболки 10%',
							'price' => 650,
							'colors' => [
								[
									'name' => 'Белый',
									'id' => 3,
									'color_value' => '#fffff',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/back.png',
									'sizes' => [
										[
											'id' => 4,
											'size' => 'XL'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 5,
											'size' => 'XXl'
										],
									],
								],

								[
									'name' => 'Серый',
									'id' => 4,
									'color_value' => '#cccccc',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_3/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_3/back.png',
									'sizes' => [
										[
											'id' => 1,
											'size' => 'X'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 3,
											'size' => 'S'
										],
									],
								],
							],
						],
					],
				],

				[
					'name' => 'Футболки не мужские',
					'id' => 2,
					'products' => [
						[
							'name' => 'Футболка не хлопок 100%',
							'id' => 3,
							'description' => 'Описание не футболки 100%',
							'image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/front.png',
							'price' => 700,
							'colors' => [
								[
									'name' => 'Белый',
									'id' => 5,
									'color_value' => '#fffff',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_1/back.png',
									'sizes' => [
										[
											'id' => 1,
											'size' => 'X'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 3,
											'size' => 'S'
										],
									],
								],

								[
									'name' => 'Черный',
									'id' => 6,
									'color_value' => '#000000',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_2/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_1/color_2/back.png',
									'sizes' => [
										[
											'id' => 4,
											'size' => 'XL'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 5,
											'size' => 'XXl'
										],
									],
								],
							],
						],
						[
							'name' => 'Футболка не хлопок 10%',
							'id' => 4,
							'price' => 600,
							'description' => 'Супер не описание футболки 10%',
							'image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/front.png',
							'colors' => [
								[
									'name' => 'Белый',
									'id' => 7,
									'color_value' => '#fffff',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_4/back.png',
									'sizes' => [
										[
											'id' => 4,
											'size' => 'XL'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 5,
											'size' => 'XXl'
										],
									],
								],

								[
									'name' => 'Серый',
									'id' => 8,
									'color_value' => '#cccccc',
									'front_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_3/front.png',
									'back_image' => 'http://print4you.tk/upload/constructor/products/product_2/color_3/back.png',
									'sizes' => [
										[
											'id' => 4,
											'size' => 'XL'
										],

										[
											'id' => 2,
											'size' => 'M'
										],

										[
											'id' => 5,
											'size' => 'XXl'
										],
									],
								],
							],
						],
					],
				],
			],
		];
	}

	public function actionIndex() {
		return $this->render('index');
	}

}
