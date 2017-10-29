<?php 

namespace frontend\components\basket;

use Yii;
use common\models\ConstructorProducts;
use common\models\ConstructorColors;
use common\models\ConstructorColorSizes;
use common\models\ConstructorPrintPrices;
use common\models\ConstructorColorsSides;
use common\models\OrdersProduct;
use common\models\CommonUser;


class ConstructorProduct implements IProduct 
{	

	const STORAGE_TEMP_ORDER_DIR = '/constructor/orders/temp';


	// добавление товара аяксом
	public function addProductFromAjax()
	{	

		$product_id = Yii::$app->request->post('product_id');
		$color_id = Yii::$app->request->post('color_id');
		$size_id = Yii::$app->request->post('size_id');
		$front_data = json_decode(Yii::$app->request->post('front_data'), true);
		$back_data = json_decode(Yii::$app->request->post('back_data'), true);
		$additional = json_decode(Yii::$app->request->post('additional'), true);

		$product = ConstructorProducts::findOne(+$product_id);
		$color = ConstructorColors::findOne(['id' => +$color_id, 'product_id' => +$product_id]);
		$color_sizes = ConstructorColorSizes::findOne(['color_id' => +$color_id, 'size_id' => +$size_id]);

		if ($product && $color && $color_sizes) {
			
			$folder_name = $this->generateTempDir();
			
			$front_filename = $this->generateTempFile($folder_name, $front_data['print']);
			$back_filename = $this->generateTempFile($folder_name, $back_data['print']);

			if ($front_filename == false) return false;
			if ($back_filename == false) return false;

			$front_print = $front_data['size'] == false ? [] : ['size_id' => $front_data['size']];
			$back_print = $back_data['size'] == false ? [] : ['size_id' => $back_data['size']];
		
			$front_print_data = $this->uploadPrintData($folder_name, $front_data['data']);
			$back_print_data = $this->uploadPrintData($folder_name, $back_data['data']);

			if ($front_print_data === false || $back_print_data === false) return false;

			$additional_sides = [];

			for ($i = 0; $i < count($additional); $i++) {

				// возьмем все данные
				$current = $additional[$i];
				$print_image = $this->generateTempFile($folder_name, $current['print']);
				$print = $current['size'] == false ? [] : ['size_id' => $current['size']];
				$print_data = $this->uploadPrintData($folder_name, $current['data']);

				// проверим наличие стороны у товрвара
				$side = ConstructorColorsSides::find()->where([
							'color_id' => $color->id, 
							'side_id' => $current['side_id']]
							)->with('side')->one();

				if ($side == null) return false;
				$additional_sides[] = [
					'print_image' => $print_image,
					'print' => $print,
					'print_data' => $print_data,
					'side_id' => $current['side_id'],
					'side_name' => $side->side->name,
				];
			} 

			$item = [
				'folder_name' => $folder_name,
				'product_id' => $product_id,
				'product_type' => Basket::PRODUCT_CONSTRUCTOR_TYPE,
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
				'front_print_data' => $front_print_data,
				'back_print' => $back_print,
				'back_print_data' => $back_print_data,
				'additional_sides' => $additional_sides,
			];

			$item = $this->calcPrintData($item);

			return $item;
			
		}

		return false;
	}

	// вывод во фронт коризны
	public static function renderFrontendCart($item, $id)
	{
		$print_temp_adress = self::getImageLink($item);
		$color = ConstructorColors::find()->select('name')
					->where(['id' => $item['color_id']])->one();

		$avaliable_sizes = ConstructorColorSizes::find()->asArray()
						->where(['color_id' => $item['color_id']])->all();

		// оптовая цена или нет
		$front_print_price = self::getPrintPrice($item, 'front');
		$back_print_price = self::getPrintPrice($item, 'back');
		$additional_price = 0;

		$additional_sides = [];
		for ($i = 0; $i < count($item['additional_sides']); $i++) {
			$current = $item['additional_sides'][$i];
			$print_price = self::getPrintPrice($item, 'additional', $current);
			$additional_price += $print_price;
			$additional_sides[] = [
				'print_image' => $print_temp_adress . '/' . $current['print_image'],
				'side_name' => $current['side_name'],
				'side_id' => $current['side_id'],
				'print_price' => $print_price,
				'print' => $current['print'],
				'avaliable_prices' => $current['avaliable_prices'],
			];
		}

		$product_price = self::getItemPrice($item);

		$price = $product_price + $additional_price + $front_print_price + $back_print_price; 

		// цена со скидкой
		$discount_price = $price * self::getDiscountFactor($item);

		$data = [
			'id' => $id,
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
			'front_print' => $item['front_print'],
			'front_print_avaliable_prices' => $item['front_print_avaliable_prices'],
			'back_print' => $item['back_print'],
			'back_print_avaliable_prices' => $item['back_print_avaliable_prices'],
			'front_print_price' => $front_print_price,
			'back_print_price' => $back_print_price,
			'product_price' => $product_price,
			'additional_sides' => $additional_sides,
		];

		return [
			'data' => $data,
			'price' => $discount_price * $item['count'],
		];
	}

	/* РАБОТА С ЦЕНОЙ ТОВАРА */

	// полная цена
	public static function getItemPrice($item)
	{
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

	// возвращает полную цену товара
	public static function getFullPrice($item) 
	{
		// формирование полной цеын за товар
		$product_price = self::getItemPrice($item);
		$front_print_price = self::getPrintPrice($item, 'front');
		$back_print_price = self::getPrintPrice($item, 'back');

		$additional_price = 0;
		for ($i = 0; $i < count($item['additional_sides']); $i++) 
			$additional_price += self::getPrintPrice($item, 'additional', $item['additional_sides'][$i]);

		$price = $product_price + $additional_price + $front_print_price + $back_print_price; 

		$discount_price = $price * self::getDiscountFactor($item);
		return $discount_price * $item['count'];
	}

	// возвращает все данные о цене товара
	public static function getPriceData($item)
	{	
		$front_print_price = self::getPrintPrice($item, 'front');
		$back_print_price = self::getPrintPrice($item, 'back');

		$additional_price = 0;
		$additional_sides = [];
		for ($i = 0; $i < count($item['additional_sides']); $i++) {
			$current = $item['additional_sides'][$i];
			$print_price = self::getPrintPrice($item, 'additional', $current);
			$additional_price += $print_price;
			$additional_sides[] = [
				'side_name' => $current['side_name'],
				'print_price' => $print_price,
			];
		}

		$product_price = self::getItemPrice($item);
		$price = $product_price + $additional_price + $front_print_price + $back_print_price; 
		$discount_price = $price * self::getDiscountFactor($item);
		$count = $item['count'];

		return [
			'front_print_price' => $front_print_price,
			'back_print_price' => $back_print_price,
			'product_price' => $product_price,
			'price' => $price,
			'discount_price' => $discount_price,
			'additional_sides' => $additional_sides,
			'count' => $count,
		];
	}

	// процент скидки товара
	public static function getDiscountFactor($item, $return_percent = false)
	{
		$discount = CommonUser::getDiscount($item['count']);
		if ($return_percent) 
			return $discount;
		else
			return ((100 - $discount) / 100);
	}

	/* РАБОТА С ФАЙЛАМИ И ДИРЕКТОРИЯМИ */

	// генерация временного файла принта
	private function generateTempFile($folder_name, $base64) {
		$data = explode(',', $base64);
		$filename = time() . '_' . $this->generateRandomString() . '.png';
		$file_path = Yii::getAlias('@storage') . $folder_name . '/' . $filename;
		$file = fopen($file_path, "wb");

		if (fwrite($file, base64_decode($data[1])) == false) return false;
		fclose($file);

		if (filesize($file_path) / 1024 / 1024 > 4) {
			unlink($file);
			return false;
		}
		return $filename;
	}

	// генкрация папки для временных принтов
	private function generateTempDir() {

		// проверим есть ли временная папка, если нет, то создадим
		$alias = Yii::getAlias('@storage');
		$folder_name = self::STORAGE_TEMP_ORDER_DIR . '/' . time();
		$dir_path = $alias . $folder_name;
		if (!file_exists($dir_path) && !is_dir($dir_path)) 
            	if (!mkdir($dir_path, 0755, true)) return false;

        return $folder_name;
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

	/* Изначальная инициализация товара с констурктора в корзину */

	// загрузка данных о принте (картинки и текста)
	private function uploadPrintData($folder_name, $data)
	{	
		$array = [];
		for ($i = 0; $i < count($data); $i++) {
			if ($data[$i]['type'] == 'text') {
				$array[] = $data[$i];
			} elseif ($data[$i]['type'] == 'image') {
				$filename = $this->generateTempFile($folder_name, $data[$i]['src']);
				if ($filename == false) return false;
				$array[] = [
					'type' => 'image',
					'filename' => $filename,
				];
			}
		}

		return $array;
	}


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
			$front_print = ConstructorPrintPrices::getPriceData($material_id, $front_size, $count);
			if ($front_print == false) {
				$front_print = [];
				$avaliable_front_prices = [];
			} else {
				$front_print['attendance'] = null; 
				$type_id = $front_print['type_id'];
				$front_color = $front_print['color'];
				$avaliable_front_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $front_size, $count, $type_id, $front_color);
			}
		
		}
		
		if (empty($item['back_print'])) {
			$back_print = [];
			$avaliable_back_prices = [];
		} else {
			$back_size = $item['back_print']['size_id'];
			$back_print = ConstructorPrintPrices::getPriceData($material_id, $back_size, $count);
			if ($back_print == false) {
				$back_print = [];
				$avaliable_back_prices = [];
			} else {
				$back_print['attendance'] = null; 
				$type_id = $back_print['type_id'];
				$back_color = $back_print['color'];
				$avaliable_back_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $back_size, $count, $type_id, $back_color);
			}
		}
		
		for ($i = 0; $i < count($item['additional_sides']); $i++) {
			$current = $item['additional_sides'][$i];
			if (empty($current['print'])) {
				$item['additional_sides'][$i]['avaliable_prices'] = [];
				continue;
			}

			$size_id = $current['print']['size_id'];
			$print = ConstructorPrintPrices::getPriceData($material_id, $size_id, $count); 
			if ($print == false) {
				$item['additional_sides'][$i]['avaliable_prices'] = [];
				continue;
			}

			$print['attendance'] = null; 
			$type_id = $print['type_id'];
			$color = $print['color'];
			$avaliable_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $size_id, $count, $type_id, $color);

			$item['additional_sides'][$i]['print'] = $print;
			$item['additional_sides'][$i]['avaliable_prices'] = $avaliable_prices;
		}

		$item['front_print'] = $front_print;
		$item['front_print_avaliable_prices'] = $avaliable_front_prices;

		$item['back_print'] = $back_print;
		$item['back_print_avaliable_prices'] = $avaliable_back_prices;
		
		return $item;
	}

	// Сохраняет запись в таблице OrdersProduct
	public static function makeOrder($item, $order_id)
	{
		$model = new OrdersProduct();
		$model->order_id = $order_id;
		$model->product_id = $item['product_id'];
		$model->price = self::getItemPrice($item);
		$model->count = $item['count'];
		$model->name = $item['name'];

		$folder_name = OrdersProduct::generateProductionDir();

		$front_print = self::getImageDir($item) . '/' . $item['front_image'];
		$back_print = self::getImageDir($item) . '/' . $item['back_image'];

		$model->is_constructor = true;
		$model->size_id = $item['size_id'];
		$model->color_id = $item['color_id'];
		$model->folder_name = $folder_name;
		$model->front_image = OrdersProduct::moveFile($folder_name, $front_print);
		$model->back_image = OrdersProduct::moveFile($folder_name, $back_print);
		$model->discount_percent = self::getDiscountFactor($item, true);

		if (!empty($item['front_print'])) {
			$model->front_print_data = json_encode([
				'type_id' => $item['front_print']['type_id'],
				'size_id' => $item['front_print']['size_id'],
				'color' => $item['front_print']['color'],
				'attendance' => $item['front_print']['attendance'],
				'price' => self::getPrintPrice($item, 'front'),
				'data' => self::makePrintData($item, $folder_name, $item['front_print_data']),
			]);
		}

		if (!empty($item['back_print'])) {
			$model->back_print_data = json_encode([
				'type_id' => $item['back_print']['type_id'],
				'size_id' => $item['back_print']['size_id'],
				'color' => $item['back_print']['color'],
				'attendance' => $item['back_print']['attendance'],
				'price' => self::getPrintPrice($item, 'back'),
				'data' => self::makePrintData($item, $folder_name, $item['back_print_data']),
			]);
		}
		
		$additional_data = [];

		for ($i = 0; $i < count($item['additional_sides']); $i++) {
			$current = $item['additional_sides'][$i];
			$print_path = self::getImageDir($item) . '/' . $current['print_image'];
			$additional_data[] = [
				'image' => OrdersProduct::moveFile($folder_name, $print_path),
				'type_id' => $current['print']['type_id'] ?? null,
				'size_id' => $current['print']['size_id'] ?? null,
				'side_id' => $current['side_id'] ?? null,
				'color' => $current['print']['color'] ?? null,
				'attendance' => $current['print']['attendance'] ?? null,
				'price' => self::getPrintPrice($item, 'additional', $current),
				'data' => self::makePrintData($item, $folder_name, $current['print_data']),
			];
		}

		if (!empty($additional_data)) 
			$model->additional_print_data = json_encode($additional_data);

		self::removeDirectory(self::getImageDir($item));

		return $model->save();
	}

	// формирует данные о принте для записи в таблицу OrdersProduct
	private static function makePrintData($item, $folder_name, $data)
	{
		$array = [];
		for ($i = 0; $i < count($data); $i++) {
			if ($data[$i]['type'] == 'text') {
				$array[] = $data[$i];
			}

			if ($data[$i]['type'] == 'image') {
				$data_file_path = self::getImageDir($item) . '/' . $data[$i]['filename'];
				$filename = OrdersProduct::moveFile($folder_name, $data_file_path);
				$array[] = [
					'type' => 'image',
					'filename' => $filename,
				];
			}
		}

		return $array;
	}

	/* Работа с данными принта */

	// получение цены принта
	public static function getPrintPrice($item, $side_name, $side = false)
	{
		$price = 0;
		$gross_price = [];
		$count = $item['count'];
		$attendance = null;
		$print_price = false;

		switch ($side_name) {
			case 'front':
				if (empty($item['front_print'])) return null;
				$price = $item['front_print']['price'];
				$gross_price = json_decode($item['front_print']['gross_price'], true);
				$attendance = $item['front_print']['attendance'];
				break;
			
			case 'back':
				if (empty($item['back_print'])) return null;
				$price = $item['back_print']['price'];
				$gross_price = json_decode($item['back_print']['gross_price'], true);
				$attendance = $item['back_print']['attendance'];
				break;

			case 'additional':
				if (empty($side['print'])) return null;
				$price = $side['print']['price'];
				$gross_price = json_decode($side['print']['gross_price'], true);
				$attendance = $side['print']['attendance'];
				break;

			default:
				return null;
				break;
		}

		$max_value = 0;
		$max_gross_price = 0;

		for ($i = 0; $i < count($gross_price); $i++) {
			// если нашли подходящую оптовую цену
			if ($count >= $gross_price[$i]['from'] && $count <= $gross_price[$i]['to']){
				$print_price = $gross_price[$i]['price'];
				break;
			}

			// иначе запишем максимальные данные
			if ($gross_price[$i]['to'] > $max_value) {
				$max_value = $gross_price[$i]['to'];
				$max_gross_price = $gross_price[$i]['price'];
			}
		}
		
		// если так и не нашли оптовую цену
		if ($print_price == false)
			$print_price = $count > $max_value ? $max_gross_price : $price;
		// найдем услуги
		if ($attendance != null) 
			$print_price += $print_price * $attendance['percent'] / 100;
		
		return $print_price;
	}


	// изменение опции принта
	public static function changePrintOption($item, $side_name, $side_id = null, $option_name, $option_value)
	{
		$print = [];
		switch ($side_name) {
			case 'front':
				$print = &$item['front_print'];
				$print_avaliable_prices = $item['front_print_avaliable_prices'];
				break;
			
			case 'back':
				$print = &$item['back_print'];
				$print_avaliable_prices = &$item['back_print_avaliable_prices'];
				break;

			case 'additional':
				if ($side_id === null) return false;
				for ($i = 0; $i < count($item['additional_sides']); $i++) {
					if ($item['additional_sides'][$i]['side_id'] == $side_id) {
						$print = &$item['additional_sides'][$i]['print'];
						$back_print_avaliable_prices = &$item['additional_sides'][$i]['back_print_avaliable_prices'];
					} 
				}
				break;

			default:
				return false;
		}
		if (empty($print)) return false;



		$material_id = $item['material_id'];
		$size_id = $print['size_id'];
		$count = $item['count'];

		$type_id = $print['type_id'];
		$color = $print['color'];

		switch ($option_name) {
			case 'type':
				$price_data = self::changePrintType($material_id, $size_id, $count, (int)$option_value);
				if ($price_data == false) return false;
				$print = $price_data;
				break;

			case 'color':
				$price_data = self::changePrintColor($material_id, $size_id, $count, $type_id, (int)$option_value);
				if ($price_data == false) return false;
				$print = $price_data;
				break;

			case 'attendance':
				if ($option_value != false) {
					$attendance = self::changePrintAttendance($material_id, $size_id, $count, $type_id, $color, (int)$option_value);

					if ($attendance == null) return false;
					$print['attendance'] = $attendance;	


				} else {
					$print['attendance'] = null;
				}
				break;
			default:
				return false;
				break;
		}

		$color = $print['color'];
		$type_id = $print['type_id'];
		$print_avaliable_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $size_id, $count, $type_id, $color);

		return [
			'item' => $item,
			'print' => $print,
			'print_avaliable_prices' => $print_avaliable_prices,
		];
	}

	// изменение параметров принта внутри ссылка на товар
	public static function rebuildType($item, $side_name, $side = false) 
	{
		$material_id = $item['material_id'];
		$count = $item['count'];

		$print = [];
		$print_avaliable_prices = [];


		if (!empty($item[$side_name]) || ($side_name == 'additional' && $side !== false)) {

			if ($side_name == 'additional' &&
				 (!isset($side['print']['size_id']) || !isset($side['print']['type_id']))) {
				return [
					'print' => $print,
					'print_avaliable_prices' => $print_avaliable_prices,
				];
			}

			$size_id = $side_name == 'additional' ? $side['print']['size_id'] : $item[$side_name]['size_id'];
			$type_id = $side_name == 'additional' ? $side['print']['type_id'] : $item[$side_name]['type_id'];
	

			// узнаем доступные методы печати для текущего количества
			$avaliable_types = ConstructorPrintPrices::getAvaliableTypes($material_id, $size_id, $count);

			$need_change = true;
			for ($i = 0; $i < count($avaliable_types); $i++) {
				if ($avaliable_types[$i]['id'] == $type_id) {
					$need_change = false;
					break;
				}
			}

			// если надо изменить, то именим тип на самый первый в списке
			if ($need_change) 
				$print = self::changePrintType($material_id, $size_id, $count, $avaliable_types[0]['id']);
			else 
				$print = $side_name == 'additional' ? $side['print'] : $item[$side_name];
			
			$type_id = $print['type_id'];
			$color = $print['color'];
			$print_avaliable_prices = ConstructorPrintPrices::getAvaliablePrices($material_id, $size_id, $count, $type_id, $color);
		}

		return [
			'print' => $print,
			'print_avaliable_prices' => $print_avaliable_prices,
		];
	}

	private static function changePrintType($material_id, $size_id, $count, $type_id)
	{
		$model = ConstructorPrintPrices::getPrintType($material_id, $size_id, $count, $type_id);
		if ($model != null) 
			$model['attendance'] = null;
		
		return $model;
	}

	private static function changePrintColor($material_id, $size_id, $count, $type_id, $color_id)
	{
		$model = ConstructorPrintPrices::getPrintColor($material_id, $size_id, $count, $type_id, $color_id);
		if ($model != null) 
			$model['attendance'] = null;
		return $model;
	}

	private static function changePrintAttendance($material_id, $size_id, $count, $type_id, $color, $attendance_id)
	{
		return ConstructorPrintPrices::getPrintAttendance($material_id, $size_id, $count, $type_id, $color, $attendance_id);
		
	}

	// получение ссылки до файла
	public static function getImageLink($item)
	{
		return Yii::getAlias('@storage_link') . $item['folder_name'];
	}

	// получение папки файлов
	public static function getImageDir($item)
	{
		return Yii::getAlias('@storage') . $item['folder_name'];
	}

	// удаление не пустой директории
	public static function removeDirectory($dir)
	{
		if (is_dir($dir)) {

	     	$objects = scandir($dir);
		    foreach ($objects as $object) {
		        if ($object != "." && $object != "..") {

					if (filetype($dir."/".$object) == "dir") 
						$this->removeDirectory($dir."/".$object); 
					else 
						unlink($dir."/".$object);

		        }
		    }

		    reset($objects);
		    rmdir($dir);
	    } elseif (file_exists($dir)) {
	    	unlink($dir);
	    }
	}

}