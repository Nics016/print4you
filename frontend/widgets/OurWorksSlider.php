<?php 

namespace frontend\widgets;
use yii\base\Widget;

class OurWorksSlider extends Widget 
{	

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		return $this->render('our-works-slider');
	}
}