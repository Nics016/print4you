<?php 
namespace console\controllers;

use yii\console\Controller;

use frontend\components\Basket;

class ConsoleController extends Controller 
{
	public function actionClearBasket()
	{
		Basket::clearOldBasket();
	}
}