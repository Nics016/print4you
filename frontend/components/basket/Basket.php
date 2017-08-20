<?php 

namespace frontend\components\basket;

use Yii;
use common\models\ConstructorProducts;
use common\models\ConstructorColors;
use common\models\ConstructorColorSizes;
use common\models\ConstructorPrintPrices;
use common\models\UserCart;
use common\models\CommonUser;
use common\models\OrdersProduct;
use common\models\Orders;


class Basket {

	private $basket = null;
	private $model = null;
	private $product = null;

	const STORAGE_CONSTRUCTOR_TEMP_ORDER_DIR = '/constructor/orders/temp';
	const PRODUCT_CONSTRUCTOR_TYPE = 'constructor';

	const PRODUCT_MIN_COUNT = 1;
	const PRODUCT_MAX_COUNT = 2999;

	public function __construct (IProduct $product = null)
	{	

		Yii::$app->session->open();

		$this->product = $product;

		if (Yii::$app->user->isGuest) {
			// если пользователь не зареган, то берем коризну из сессии
			$this->basket = $_SESSION['basket'] ?? [];
		} else {
			// если зареган - из бд
			$user_id = Yii::$app->user->identity->id;
			$model = UserCart::find()->where(['user_id' => $user_id])->limit(1)->one();
			$this->initFromModel($model);
		}
	}

	private function initFromModel($model)
	{
		// если модели нет, то создадим
		if ($model == null) {
			$this->basket = [];
			$this->model = new UserCart();
			$this->model->user_id = $user_id;
		} else {
			$this->model = $model;
			$this->basket = json_decode($this->model->cart_data, true);
		}
	}

	public static function init(IProduct $product = null) {
		return new self($product);
	}

	public function getPositionsCount()
	{
		return count($this->basket);
	}

	// доабвление в коризну принта
	public function addAjaxProduct() 
	{	
		$item = $this->product->addProductFromAjax();
		if ($item === false) 
			return false;
		else 
			$this->basket[] = $item;

		return $this->save();
	}

	

	/*Работа с печатью товара*/

	public function changePrintOption($id, $side_name, $side_id = null, $option_name, $option_value)
	{
		if (!isset($this->basket[$id])) return false;
		if ($side_name == 'additional' && $side_id === null) return false;
		$item = &$this->basket[$id];
		if (!$this->isConstructorProduct($id)) return false;
		$result = ConstructorProduct::changePrintOption($item, $side_name, $side_id, $option_name, $option_value);
		$item = $result['item'];
		if ($this->save())
			return [
				'print' => $result['print'],
				'print_avaliable_prices' => $result['print_avaliable_prices'],
			];
		else
			return false;  


	}

	// изменение параметров принта
	public function rebuildOptions($id) {
		if (!isset($this->basket[$id])) return false;
		$item = &$this->basket[$id];
		if ($item['product_type'] != self::PRODUCT_CONSTRUCTOR_TYPE) return false;

		$front_data = ConstructorProduct::rebuildType($item, 'front_print');
		$item['front_print'] = $front_data['print'];
		$item['front_print_avaliable_prices'] = $front_data['print_avaliable_prices'];
		
		$back_data = ConstructorProduct::rebuildType($item, 'back_print');
		$item['back_print'] = $back_data['print'];
		$item['back_print_avaliable_prices'] = $back_data['print_avaliable_prices'];

		for ($i = 0; $i < count($item['additional_sides']); $i++) {
			$data = ConstructorProduct::rebuildType($item, 'additional', $item['additional_sides'][$i]);
			$item['additional_sides'][$i]['print'] = $data['print'];
			$item['additional_sides'][$i]['avaliable_prices'] = $data['print_avaliable_prices'];
		}

		if (!$this->save()) return false;

		return [
			'front' => [
				'print' => $item['front_print'],
				'print_avaliable_prices' => $item['front_print_avaliable_prices'],
			],
			'back' => [
				'print' => $item['back_print'],
				'print_avaliable_prices' => $item['back_print_avaliable_prices'],
			],
			'additional_sides' => $item['additional_sides'],
		];
	}


	// получение коризны
	public function getBasket()
	{
		return $this->basket;
	}

	// проверяет, продукт ли констурктора или нет
	public function isConstructorProduct($id) 
	{
		if (isset($this->basket[$id])) {
			return $this->basket[$id]['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE;
		}

		return null;
	}

	public function changeCount($id, $count) {

		if (isset($this->basket[$id]) && $count <= self::PRODUCT_MAX_COUNT && $count >= self::PRODUCT_MIN_COUNT) {
			$item = &$this->basket[$id];
			$item['count'] = $count;
			return $this->save() ? $item['count'] : false;
		} 
			
		return false;
	}

	// добавление продукта
	public function push($id)
	{
		if (isset($this->basket[$id]) && $this->basket[$id]['count'] < self::PRODUCT_MAX_COUNT) {
			$item = &$this->basket[$id];
			$item['count']++;
			return $this->save() ? $item['count'] : false;
		} 
			
		return false;
		
	}

	// уменьшение продукта
	public function pop($id)
	{
		if (isset($this->basket[$id]) && $this->basket[$id]['count'] > self::PRODUCT_MIN_COUNT) {
			$item = &$this->basket[$id];
			$item['count']--;
			return $this->save() ? $item['count'] : false;
		} 
			
		return false;
		
	}

	// изменяет размер продукта
	public function changeProductSize($id, $size_id)
	{
		if (isset($this->basket[$id])) {
			$model = ConstructorColorSizes::findOne([
				'color_id' => $this->basket[$id]['color_id'], 
				'size_id' => $size_id
			]);

			if ($model != null) {
				$this->basket[$id]['size_id'] = $size_id;
				return $this->save();
			}

			return false;
		}

		return false;
	}


	// возвращается цена корзины
	public function basketCountPrice()
	{	
		$basket_price = 0;
		$basket_count = 0;
		for ($i = 0; $i < count($this->basket); $i++) {
			$item = $this->basket[$i];

			if ($this->isConstructorProduct($i)) 
				$basket_price += ConstructorProduct::getFullPrice($item);
			
			$basket_count += $item['count'];
		}

		return [
			'price' => $basket_price,
			'count' => $basket_count,
		];
	}

	// возвращает количество товаров
	public function count() 
	{
		return count($this->basket);
	}


	// вывод корзины во фронтенд
	public function getFrontendCart()
	{
		$result_basket = [];
		$basket = $this->basket;
		$basket_price = 0;
		$products_count = 0;

		for ($i = 0; $i < count($basket); $i++) {

			if ($basket[$i]['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) 
				$data = ConstructorProduct::renderFrontendCart($basket[$i], $i);
				
			$result_basket[] = $data['data'];
			$basket_price += $data['price'];
		}

		return [
			'basket' =>  $result_basket,
			'basket_price' =>  $basket_price,
		];
	}

	// создает заказ в таблице orders_products
	public function makeOrder($order_id) 
	{
		for ($i = 0; $i < count($this->basket); $i++) {
			$item = $this->basket[$i];
			
			if ($item['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) 
				if (!ConstructorProduct::makeOrder($item, $order_id)) return false;
		}

		$this->basket = [];

		return $this->save();
	}


	public function getConstructorProductPrice($id)
	{	
		if (!isset($this->basket[$id])) return false;
		$item = $this->basket[$id];
		if ($item['product_type'] != self::PRODUCT_CONSTRUCTOR_TYPE) return false;
		return ConstructorProduct::getPriceData($item);
	}


	// сохранение корзины
	private function save() 
	{	

		if (Yii::$app->user->isGuest) {
			$_SESSION['basket'] = $this->basket;
			return true;
		} else {
			date_default_timezone_set('Europe/Moscow');
			$this->model->cart_data = json_encode($this->basket);
			$this->model->expire_in = date('Y/m/d H:i:s', time() + 3600 * 24 * 3);
			return $this->model->save();
		}

	}

	/* Удаление и очитска корзины */

	// удаление элемента
	public function removeItem($id) 
	{	
		//dd($id);
		if (isset($this->basket[$id])) {

			// если конструктор, тогда удалим все картинки
			if ($this->basket[$id]['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) 
				ConstructorProduct::removeDirectory(ConstructorProduct::getImageDir($this->basket[$id]));
			

			array_splice($this->basket, (int)$id, 1);

			if (!$this->save()) return false;

			return count($this->basket);
		}

		return false;
	}

	// очистка старой коризны
	public static function clearOldBasket()
	{
		date_default_timezone_set('Europe/Moscow');
	}

	/*Работа с переносом коризны*/

	// сохранение корзины из сесси в бд
	public static function saveToDb($user_id) {
		Yii::$app->session->open();
		$basket = $_SESSION['basket'] ?? [];
		$model = UserCart::find()->where(['user_id' => $user_id])->limit(1)->one();
		if ($model == null) {
			$model = new UserCart();
			$model->user_id = $user_id;
		}

		unset($_SESSION['basket']);

		date_default_timezone_set('Europe/Moscow');
		$model->cart_data = json_encode($basket);
		$model->expire_in = date('Y/m/d H:i:s', time() + 3600 * 24 * 3);
		return $model->save();
	}

	// сохранение из бд в сессию
	public static function saveToSession($user_id) {
		Yii::$app->session->open();
		$model = UserCart::find()->where(['user_id' => $user_id])->limit(1)->one();

		if ($model != null) {
			$data = json_decode($model->cart_data, true);
			$model->cart_data = json_encode([]);
			$_SESSION['basket'] = $data;
			return $model->save();
		}

		return false;
	}
	
}