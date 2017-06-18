<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

use frontend\components\Basket;

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
	            ],
	        ],
	    ];
	}


	public function actionIndex() {

		$basket_obj = Basket::init()->getFrontendCart();
		$basket = $basket_obj['basket'];
		$basket_price = $basket_obj['basket_price'];
		$discount = $basket_obj['discount'];

		return $this->render('index', [
			'basket' => $basket,
			'basket_price' => $basket_price,
			'discount' => $discount,
			'constructor_sizes' => ConstructorSizes::find()->asArray()->all(),
		]);

	}

	public function actionChangeProductCount()
	{	
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = Yii::$app->request->post('id');
		$action = Yii::$app->request->post('action');
		$basket = Basket::init();

		switch ($action) {
			case 'push':
				$result = $basket->push($id);
				break;
			
			case 'pop':
				$result = $basket->pop($id);
				break; 

			default:
				$result = false;
				break;
		}
		

		if ($result !== false) {
			$full_price = $basket->getBasketFullPrice();
			return [
				'status' => 'ok',
				'count' => $result,
				'checkout_html' => $this->renderAjax('checkout_price', [
					'basket_price' => $full_price['basket_price'],
					'discount' => $full_price['discount'],
				]),
			];
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

			$full_price = $basket->getBasketFullPrice();
			
			return [
				'status' => 'ok', 
				'html' => 'none',
				'checkout_html' => $this->renderAjax('checkout_price', [
					'basket_price' => $full_price['basket_price'],
					'discount' => $full_price['discount'],
				]),
			];
		} 

		return ['status' => 'ok', 'html' => $this->renderAjax('empty_cart')];

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
            
            if ($model->save() && $basket->makeOrder($model->getPrimaryKey())) 
                return $this->redirect(['order-created']);

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