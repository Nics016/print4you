<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\NotFoundHttpException;

use frontend\components\basket\Basket;
use frontend\components\Sms;

use common\models\ConstructorSizes;
use common\models\Orders;
use common\models\CommonUser;
use common\models\Office;
use common\models\PagesSeo;

use yii\helpers\Url;

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
		$this->registerSeo(11, 'index');
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

				$additonal_html = [];
				for ($i = 0; $i < count($rebuild['additional_sides']); $i++) {
					$current = $rebuild['additional_sides'][$i];
					$additonal_html[] = [
						'side_id' => $current['side_id'],
						'html' => $this->renderAjax('constructor_change_print', [
							'print' =>  $current['print'],
							'print_avaliable_prices' => $current['avaliable_prices'],
						]),
					];
				}

				return [
					'status' => 'ok',
					'basket_price' => $basket_price['price'],
					'count' => $result,
					'product_price_html' => $this->renderAjax('constructor_product_price', $product_price),
					'front_print_html' => $this->renderAjax('constructor_change_print', $rebuild['front']),
					'back_print_html' => $this->renderAjax('constructor_change_print', $rebuild['back']),
					'additonal_html' => $additonal_html,
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
				'count' => $basket->getPositionsCount(),
			];
		} 

		return [
			'status' => 'ok', 
			'html' => $this->renderAjax('empty_cart'), 
			'count' => $basket->getPositionsCount(),
		];

	}


	// настройки принта
	public function actionChangePrintOption() 
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$id = (int)Yii::$app->request->post('id');
		$side = Yii::$app->request->post('side');
		$side_id = Yii::$app->request->post('side_id');
		$value = Yii::$app->request->post('value');
		$name = Yii::$app->request->post('name');

		$basket = Basket::init();
		$result = $basket->changePrintOption($id, $side, $side_id, $name, $value);

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
	 * Проверка телефона  в заказк
	 */

	public function actionCheckPhone()
	{
		if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

			Yii::$app->response->format = Response::FORMAT_JSON;
			$phone = Yii::$app->request->post('phone');

			return [
				'status' => Orders::checkPhone($phone) ? 'ok' : 'fail',
			];
		}

		throw new NotFoundHttpException();
	}

	/**
     * Рендерится при нажатии "Оформить" в корзине
     */
    public function actionCheckout()
    {	
        $model = new Orders();
        $basket = Basket::init();
        $basket_data = $basket->basketCountPrice(); 

        if ($basket_data['count'] == 0) return $this->render('empty_cart');

        if (Yii::$app->request->isAjax && Yii::$app->request->isPost) {

        	Yii::$app->response->format = Response::FORMAT_JSON;

        	if (!$model->loadFromAjax()) return ['status' => 'fail'];
            $discountVal = CommonUser::getDiscount($basket_data['count']);
            $totalSum = $basket_data['price'];
            $model->discount_percent = $discountVal;
            $model->price = $totalSum;

            // gross
            if ($basket_data['count'] >= Orders::GROSS_PRICE_PRODUCT_COUNT){
            	$model->is_gross = true;
            } else {
            	$model->is_gross = false;
            }

            // свалидируем, чтобы понять, что номер уникальный и больше не валидиировать
            // потому что, возможно придется регать пользователя
            if (!$model->validate()) return ['status' => 'fail'];

            // если пользователь не зашел, попробуем его зарегать

            if (!Yii::$app->user->isGuest){
            	$model->client_id = Yii::$app->user->identity->id;
            } else {
            	$user = new CommonUser();
            	$user->scenario = CommonUser::CREATE_FROM_ORDER;
            	$user->firstname = $model->client_name;
            	$user->phone = $model->phone;
            	$password = Yii::$app->getSecurity()->generateRandomString(8);
	            $user->generatePasswordHash($password);
	            $user->generateAuthKey();

	            if ($user->save()) {
	                $user->successSms($password);
	            } else {
	            	return ['status' => 'fail'];
	            }

	            $model->client_id = $user->getPrimaryKey();
            }

            if ($model->save(false) && $basket->makeOrder($model->getPrimaryKey())) { 
            	$model->orderCreatedSms();
                return [
                	'status' => 'ok',
                	'url' => Url::to(['/order-pay/', 'id' => $model->getPrimaryKey()], true),
                ];
          	}

          	return ['status' => 'fail'];

        }   
      
        $model->delivery_required = true;
        $records = Office::find()->all();
        if (!Yii::$app->user->isGuest) {
            $model->client_name = Yii::$app->user->identity->firstname;
            $model->phone = Yii::$app->user->identity->phone;
        }
       
        $offices = [];
        foreach ($records as $record){
            $offices[$record['id']] = $record['address'];
        }

        $this->registerSeo(17, 'checkout');

        return $this->render('checkout', [
            'model' => $model,
            'offices' => $offices,
            'delivery_distances' => Orders::DELIVERY_DISTANCES,
        ]);
        
    }


    /**
     * Страница оплаты
     */
    public function actionOrderPay($id = null) {

    	if ($id === null) throw new NotFoundHttpException();
    	$model = Orders::findOne(['id' => (int)$id]);
    	if ($model === null || $model->order_status != $model::STATUS_NOT_PAID) 
    			throw new NotFoundHttpException();

    	$order_price = $model->price;
    	$delivery_price = $model->delivery_price;
        $clean_full_price = $order_price + $delivery_price;
        $comission_full_price = $clean_full_price + ceil($clean_full_price / 100 * 7);
    	return $this->render('order-pay', [
    		'order_id' => $model->id,
    		'sum' => $comission_full_price,
    		'delivery_price' => $delivery_price,
    		'order_price' => $order_price,
    		'success_url' => Url::to(['/'], true),
            'clean_order_price' => $clean_full_price,
    	]);
    }


    /**
     * Обработка HHTP уведомлений для Yandex.Money
     */

    public function actionCheckYandexPay()
    {	


    	$notification_secret = 'eY8ZqVodGpGe9AxxcLMVe+zp';

    	$notification_type = Yii::$app->request->post('notification_type');
    	$operation_id = Yii::$app->request->post('operation_id');
    	$amount = Yii::$app->request->post('amount');
    	$currency = Yii::$app->request->post('currency');
    	$datetime = Yii::$app->request->post('datetime');
    	$sender = Yii::$app->request->post('sender');
    	$codepro = Yii::$app->request->post('codepro');
    	$label = Yii::$app->request->post('label');

    	$sha1_hash = Yii::$app->request->post('sha1_hash');
    	$unaccepted = Yii::$app->request->post('unaccepted');

        

    	$hash = sha1(
    		$notification_type . '&' .
    		$operation_id . '&' .
    		$amount . '&' .
    		$currency . '&' .
    		$datetime . '&' .
    		$sender . '&' .
    		$codepro . '&' .
    		$notification_secret . '&' .
    		$label . '&'
    	);

        @file_put_contents(__DIR__ . '/history.txt', "Время: $datetime, бабки $amount hash - $hash, sha_1 - $sha1_hash" . PHP_EOL, FILE_APPEND);

    	if ($hash !== $sha1_hash || $codepro === true || $unaccepted === true)
    		throw new NotFoundHttpException();
   		
        

   		$model = Orders::findOne(['id' => (int)$label]);
   		if ($model !== null) {
   			$total_sum = $model->price + $model->delivery_price;
   			if ($total_sum + ceil($total_sum / 100 * 7) != $amount) throw new NotFoundHttpException();
   			$model->status = $model::STATUS_NEW;
   			$model->save();
   			$model->sucessSms();
   		}

   		throw new NotFoundHttpException();
    }

    /**
     * Открывается после успешного создания заказа,
     * т.е. перенаправляется сюда из Checkout
     */
    /*public function actionOrderCreated()
    {
        return $this->render('order-created');
    }*/

    // регистрирует сео пола
    private function registerSeo($page_id, $view_name = null) 
    {
        $seo = PagesSeo::findOne(['page_id' => $page_id]);

        Yii::$app->view->title = $seo->title ?? '';

        Yii::$app->view->registerMetaTag([
            'name' => 'title',
            'content' => $seo->title ?? '',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $seo->description ?? '',
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => $seo->keywords ?? '',
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
    }
}