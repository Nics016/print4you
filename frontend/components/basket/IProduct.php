<?php 

namespace frontend\components\basket;

interface IProduct 
{

	/**
	* Формирует аяксом товар и возвращает его
	*
	* @return mixed 
	**/
	public function addProductFromAjax();

	/**
	* Формирует данные для вывода в корзину и возвращает массив с данными и полной ценой товара
	* 
	* @param array item товар в корзине
	* @param integer i айдишник товара в корзину
	* @return array
	**/
	public static function renderFrontendCart($item, $i);

	/**
	* Возвращает полную цену товара
	*
	* @param array item товар в корзине
	* @return integer
	**/
	public static function getItemPrice($item);

	/**
	* Возвращает процент скидки товара в десятичный дроби или целом числе
	*
	* @param array item товар в корзине
	* @param boolean return_percent вернуть ли целочисленной значение процента
	* @return mixed
	**/
	public static function getDiscountFactor($item, $return_percent = false);

	/**
	* Сохраняет запись в таблице OrdersProduct
	*
	* @param array item товар в корзине
	* @param integer order_id айдишник созданного заказа в таблице Orders
	* @return boolean
	**/
	public static function makeOrder($item, $order_id);

}