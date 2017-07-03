<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

use frontend\components\Basket;
use frontend\components\Sms;

use common\models\ConstructorSizes;
use common\models\Orders;
use common\models\Office;

class CartController extends Controller {

	public function behaviors()
	{
	    return [
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'get-products'  => ['post'],
	                'add-to-cart'  => ['post'],
	                'change-print-option'  => ['post'],
	            ],
	        ],
	    ];
	}


	public function actionIndex() {

		$basket_obj = Basket::init()->getFrontendCart();
		$basket = $basket_obj['basket'];
		$basket_price = $basket_obj['basket_price'];
		/*$lol = Basket::init()->getBasket();
		dd($lol[0]);*/
		return $this->render('index', [
			'basket' => $basket,
			'basket_price' => $basket_price,
			'constructor_sizes' => ConstructorSizes::find()->asArray()->all(),
		]);

	}

	public function actionChangeProductCount()
	{	
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = (int)Yii::$app->request->post('id');
		$action = Yii::$app->request->post('action');
		$count = (int)Yii::$app->request->post('count');
		$basket = Basket::init();

		switch ($action) {
			case 'push':
				$result = $basket->push($id);
				break;
			
			case 'pop':
				$result = $basket->pop($id);
				break; 

			case 'count':
				$result = $basket->changeCount($id, $count);
				break;

			default:
				$result = false;
				break;
		}
		

		if ($result !== false) {

			if ($basket->isConstructorProduct($id)) {
				$rebuild = $basket->rebuildOptions($id);
				if ($rebuild == false) return ['status' => 'fail'];
				$basket_price = $basket->basketCountPrice();
				$product_price = $basket->getConstructorProductPrice($id);
				return [
					'status' => 'ok',
					'basket_price' => $basket_price['price'],
					'count' => $result,
					'product_price_html' => $this->renderAjax('constructor_product_price', $product_price),
					'front_print_html' => $this->renderAjax('constructor_change_print', $rebuild['front']),
					'back_print_html' => $this->renderAjax('constructor_change_print', $rebuild['back']),
				];
			}
				
		}

		return ['status' => 'fail'];
	}

	public function actionChangeProductSize()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = Yii::$app->request->post('id');
		$size_id = Yii::$app->request->post('size_id');

		return ['status' => Basket::init()->changeProductSize($id, $size_id) ? 'ok' : 'fail'];
	}

	public function actionProductRemove()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = Yii::$app->request->post('id');
		$basket = Basket::init();
		$count = $basket->removeItem($id);
		
		if ($count === false) return ['status' => 'fail'];

		if ($count > 0) {

			$basket_price = $basket->basketCountPrice();
			
			return [
				'status' => 'ok', 
				'html' => 'none',
				'basket_price' => $basket_price['price'],
			];
		} 

		return ['status' => 'ok', 'html' => $this->renderAjax('empty_cart')];

	}


	// настройки принта
	public function actionChangePrintOption() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = (int)Yii::$app->request->post('id');
		$side = Yii::$app->request->post('side');
		$value = Yii::$app->request->post('value');
		$name = Yii::$app->request->post('name');

		$basket = Basket::init();
		$result = $basket->changePrintOption($id, $side, $name, $value);

		if ($result !== false) {
			$basket_price = $basket->basketCountPrice();
			$product_price = $basket->getConstructorProductPrice($id);
			return [
				'status' => 'ok',
				'basket_price' => $basket_price['price'],
				'product_price_html' => $this->renderAjax('constructor_product_price', $product_price),
				'print_html' => $this->renderAjax('constructor_change_print', $result),
			];
		} 

		return ['status' => 'fail'];
	}

	/**
     * Рендерится при нажатии "Оформить" в корзине
     */
    public function actionCheckout()
    {
        $model = new Orders();
        $basket = Basket::init();
        $basket_data = $basket->basketCountPrice(); // количество товаров в корзине

        /*
            $basket_data['count'] => количество товаров в корзине
            $basket_data['price'] => общая стоимость корзины
        */

        if ($basket_data['count'] == 0) return $this->render('empty_order.php');

        if ($model->load(Yii::$app->request->post())) {
            $discountVal = Yii::$app->user->identity->getDiscount($basket_data['count']);
            $totalSum = $basket_data['price'];
            $model->discount_percent = $discountVal;
            $model->price = $totalSum;

            // gross
            if ($basket_data['count'] >= Orders::GROSS_PRICE_PRODUCT_COUNT){
            	$model->is_gross = true;
            } else {
            	$model->is_gross = false;
            }

            if (!Yii::$app->user->isGuest){
            	$model->client_id = Yii::$app->user->identity->id;
            }

            // delivery
            if ($model->delivery_required){
            	$model->delivery_office_id = null;
            	$model->delivery_price = Orders::DELIVERY_REQUIRED_PRICE;
            }
            // no delivery
            else {
            	$model->delivery_price = Orders::DELIVERY_NOT_REQUIRED_PRICE;
            	$office = Office::findOne(["id" => $model->delivery_office_id]);
            	$model->address = $office->address;
            }

            if ($model->save() && $basket->makeOrder($model->getPrimaryKey())) { 
            	Sms::message($model->phone, 'Спасибо, Ваш заказ #' . $model->id . ' принят в обработку!');
                return $this->redirect(['order-created']);
            }

        }   
      
        $model->delivery_required = true;
        $records = Office::Find()->all();
        if (!Yii::$app->user->isGuest) {
            $model->client_name = Yii::$app->user->identity->firstname;
            $model->phone = Yii::$app->user->identity->phone;
        }
       
        $offices = [];
        foreach ($records as $record){
            $offices[$record['id']] = $record['address'];
        }
        return $this->render('checkout', [
            'model' => $model,
            'offices' => $offices,
        ]);
        
    }

    /**
     * Открывается после успешного создания заказа,
     * т.е. перенаправляется сюда из Checkout
     */
    public function actionOrderCreated()
    {
        return $this->render('order-created');
    }

}