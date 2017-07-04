<?php 

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use common\models\Reviews;

class DeliveryInfoWidget extends Widget 
{	

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		return $this->render('delivery-info-widget');
	}
}