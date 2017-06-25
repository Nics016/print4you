<?php 

namespace frontend\components;

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

	private $basket = false;
	private $model = false;

	const STORAGE_CONSTRUCTOR_TEMP_ORDER_DIR = '/constructor/orders/temp';
	const PRODUCT_CONSTRUCTOR_TYPE = 'constructor';

	const PRODUCT_MIN_COUNT = 1;
	const PRODUCT_MAX_COUNT = 2999;

	public function __construct()
	{	
		Yii::$app->session->open();
		if (Yii::$app->user->isGuest) {
			// если пользователь не зареган, то берем коризну из сессии
			$this->basket = $_SESSION['basket'] ?? [];
		} else {
			// если зареган - из бд
			$user_id = Yii::$app->user->identity->id;
			$model = UserCart::find()->where(['user_id' => $user_id])->limit(1)->one();

			// если модели нет, то создадим
			if ($model == false) {
				$this->basket = [];
				$this->model = new UserCart();
				$this->model->user_id = $user_id;
			} else {
				$this->model = $model;
				$this->basket = json_decode($this->model->cart_data, true);
			}

		}
	}

	public function init() {
		return new self();
	}

	// доабвление в коризну принта
	public function addConstructorProduct($product_id, $color_id, $size_id, $front_base64, $back_base64, $front_size, $back_size) 
	{	
		$product = ConstructorProducts::findOne(+$product_id);
		$color = ConstructorColors::findOne(['id' => +$color_id, 'product_id' => +$product_id]);
		$color_sizes = ConstructorColorSizes::findOne(['color_id' => +$color_id, 'size_id' => +$size_id]);

		if ($product && $color && $color_sizes) {

			
			$dir_path = $this->generateTempDir();
			
			$front_filename = $this->generateTempFile($dir_path, $front_base64);
			$back_filename = $this->generateTempFile($dir_path, $back_base64);

			if ($front_filename == false) return false;
			if ($back_filename == false) return false;


			if ($front_size == false)
				$front_print = [];
			else
				$front_print = [
					'size_id' => $front_size,
					'color' => null,
				];

			if ($back_size == false) 
				$back_print = [];
			else 
				$back_print = [
					'size_id' => $back_size,
					'color' => null,
				];

			$item = [
				'product_id' => $product_id,
				'product_type' => self::PRODUCT_CONSTRUCTOR_TYPE,
				'color_id' => $color_id,
				'name' => $product->name,
				'price' => $color->price,
				'material_id' => $product->material_id,
				'gross_price' => json_decode($color->gross_price, true),
				'size_id' => $size_id,
				'front_image' => $front_filename,
				'back_image' => $back_filename,
				'count' => 1,
				'front_print' => $front_print,
				'back_print' => $back_print,
			];

			$item = $this->calcPrintData($item);
			$this->basket[] = $item;

			return $this->save();
			
		}

		return false;
	}

	/*Работа с печатью товара*/

	// формирует данные печати
	private function calcPrintData($item) 
	{	

		$count = $item['count'];
		$material_id = $item['material_id'];
		
		if (empty($item['front_print'])) {
			$front_print = [];
			$avaliable_front_prices = [];
		} else {
			$front_size = $item['front_print']['size_id'];
			$front_color = $item['front_print']['color'];
			$front_print = ConstructorPrintPrices::getPriceData($material_id, $front_size, $count, $front_color);
			$type_id = $front_print['type_id'];
			$avaliable_front_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $front_size, $type_id, $count, $front_color);
		}
		
		if (empty($item['back_print'])) {
			$back_print = [];
			$avaliable_back_prices = [];
		} else {
			$back_size = $item['back_print']['size_id'];
			$back_color = $item['back_print']['color'];
			$back_print = ConstructorPrintPrices::getPriceData($material_id, $back_size, $count, $back_color);
			$type_id = $back_print['type_id'];
			$avaliable_back_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $back_size, $type_id, $count, $back_color);
		}
		

		$item['front_print'] = $front_print;
		$item['front_print']['avaliable_prices'] = $avaliable_front_prices;

		$item['back_print'] = $back_print;
		$item['back_print']['avaliable_prices'] = $avaliable_back_prices;
		return $item;
	}

	// генерация временного файла принта
	private function generateTempFile($dir_path, $base64) {
		$data = explode(',', $base64);
		$filename = time() . '_' . $this->generateRandomString() . '.png';
		$file = fopen($dir_path . '/' . $filename, "wb");
		if (fwrite($file, base64_decode($data[1])) == false) return false;
		fclose($file);
		return $filename;
	}

	// генкрация папки для временных принтов
	private function generateTempDir() {

		// проверим есть ли временная папка, если нет, то создадим
		$alias = Yii::getAlias('@storage');
		$dir_path = $alias . self::STORAGE_CONSTRUCTOR_TEMP_ORDER_DIR;

		if (!file_exists($dir_path) && !is_dir($dir_path)) 
            	if (!mkdir($dir_path, 0755, true)) return false;

        return $dir_path;
	}

	// генерация случайно строки
	private function generateRandomString($length = 32)
	{
		$chars = "abcdefghijklmnopqrstuvwxyz1234567890_";
		$chars_length = strlen($chars);
		$output = '';

		for ($i = 0; $i < $length; $i++)
			$output .= $chars[rand(0, $chars_length - 1)]; 

		return $output;
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
			$price = $this->getItemPrice($item);
			$dicount_price = $price * $this->getDiscountFactor($item);
			if ($this->save()) return [
				'count' => $item['count'], 
				'price' => $price,
				'discount_price' => $dicount_price,
			];
			
			return false;

		} 
			
		return false;
	}

	// добавление продукта
	public function push($id)
	{
		if (isset($this->basket[$id]) && $this->basket[$id]['count'] < self::PRODUCT_MAX_COUNT) {
			$item = &$this->basket[$id];
			$item['count']++;
			$price = $this->getItemPrice($item);
			$dicount_price = $price * $this->getDiscountFactor($item);
			if ($this->save()) return [
				'count' => $item['count'], 
				'price' => $price,
				'discount_price' => $dicount_price,
			];
			
			return false;

		} 
			
		return false;
		
	}

	// уменьшение продукта
	public function pop($id)
	{
		if (isset($this->basket[$id]) && $this->basket[$id]['count'] > self::PRODUCT_MIN_COUNT) {
			$item = &$this->basket[$id];
			$item['count']--;
			$price = $this->getItemPrice($item);
			$dicount_price = $price * $this->getDiscountFactor($item);
			if ($this->save()) return [
				'count' => $item['count'], 
				'price' => $price,
				'discount_price' => $dicount_price,
			];
			
			return false;

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

	// удаление элемента
	public function removeItem($id) 
	{
		if (isset($this->basket[$id])) {

			// если конструктор, тогда удалим все картинки
			if ($this->basket[$id]['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) {
				@unlink($this->getImageDir() . '/' . $this->basket[$id]['front_image']);
				@unlink($this->getImageDir() . '/' . $this->basket[$id]['back_image']);
			}

			array_splice($this->basket, +$id, 1);

			if (!$this->save()) return false;

			return count($this->basket);
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
			$price = $this->getItemPrice($item);
			$discount_price = $price * $this->getDiscountFactor($item);
			$basket_price += $discount_price * $item['count'];
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
		$print_temp_adress = self::getImageLink();
		$result_basket = [];
		$basket = $this->basket;
		$basket_price = 0;
		$products_count = 0;

		for ($i = 0; $i < count($basket); $i++) {

			if ($basket[$i]['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) {

				$item = $basket[$i];

				$color = ConstructorColors::find()->select('name')
							->where(['id' => $basket[$i]['color_id']])->one();

				$avaliable_sizes = ConstructorColorSizes::find()->asArray()
							->where(['color_id' => $basket[$i]['color_id']])
							->all();

				// оптовая цена или нет
				$price = $this->getItemPrice($item); 

				// цена со скидкой
				$discount_price = $price * $this->getDiscountFactor($item);

				$result_basket[] = [
					'id' => $i,
					'product_type' => $item['product_type'],
					'name' => $item['name'],  
					'color' => $color->name, 
					'price' => $price,
					'discount_price' => $discount_price,
					'front_image' => $print_temp_adress . '/' . $item['front_image'], 
					'back_image' => $print_temp_adress . '/' . $item['back_image'], 
					'avaliable_sizes' => $avaliable_sizes,
					'current_size' => $item['size_id'],
					'count' => $item['count'],
				];

				$basket_price += $discount_price * $basket[$i]['count'];
			}
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
			$model = new OrdersProduct();
			$model->order_id = $order_id;
			$model->product_id = $item['product_id'];
			$model->price = $this->getItemPrice($item);
			$model->count = $item['count'];
			$model->name = $item['name'];
			if ($item['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) {
				$model->is_constructor = true;
				$model->size_id = $item['size_id'];
				$model->color_id = $item['color_id'];
				$front_image_path = self::getImageDir() . '/' .$item['front_image'];
				$back_image_path = self::getImageDir() . '/' .$item['back_image'];
				$model->front_image = OrdersProduct::moveFileToProduction($front_image_path);
				$model->back_image = OrdersProduct::moveFileToProduction($back_image_path);
			}

			if (!$model->save()) return false;
		}
		$this->basket = [];

		return $this->save();
	}

	/* Работа с ценой товара */

	// возврашает цену товара взависиммости от опта или нет
	private function getItemPrice($item) 
	{	

		if ($item['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) {
			$count = $item['count'];
			$gross_price = $item['gross_price'];

			// запишем максимальное число количества товара оптовой цены и его значение
			$max_value = 0;
			$max_gross_price = 0;

			for ($i = 0; $i < count($gross_price); $i++) {
				// если нашли подходящую оптовую цену
				if ($count >= $gross_price[$i]['from'] && $count <= $gross_price[$i]['to'])
					return $gross_price[$i]['price'];

				// иначе запишем максимальные данные
				if ($gross_price[$i]['to'] > $max_value) {
					$max_value = $gross_price[$i]['to'];
					$max_gross_price = $gross_price[$i]['price'];
				}
			}

			/* 
				если не нашли подходящую оптову цену, проверим,
				может быть количество вышло за рамки максимального оптового число
				если нет, то это розничная цена 
			*/
		
			return $count > $max_value ? $max_gross_price : $item['price'];
		}
	}

	// получение скидочного множителя товара
	private function getDiscountFactor($item)
	{
		if ($item['product_type'] == self::PRODUCT_CONSTRUCTOR_TYPE) {
			$discount = CommonUser::getDiscount($item['count']);
			return ((100 - $discount) / 100);
		}
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


	// получение ссылки до файла
	public function getImageLink() 
	{
		return Yii::getAlias('@storage_link') . self::STORAGE_CONSTRUCTOR_TEMP_ORDER_DIR;
	}

	// получение папки файлов
	public function getImageDir()
	{
		return Yii::getAlias('@storage') . self::STORAGE_CONSTRUCTOR_TEMP_ORDER_DIR;
	}
}