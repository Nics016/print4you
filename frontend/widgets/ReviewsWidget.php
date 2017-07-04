<?php 

namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use common\models\Reviews;

class ReviewsWidget extends Widget 
{	
	public $reviews = [];

	public function init()
	{
		parent::init();
		$this->reviews = Reviews::find()->limit(5)->with('user')
					->asArray()->orderBy('id DESC')->all();
	}

	public function run()
	{
		return $this->render('reviews-widget', ['reviews' => $this->reviews]);
	}
}