<?php 
use yii\helpers\Html;

?>

<main id="guests">
	
	<h1 class="guests-main-title">Наши гости</h1>
	
	<div class="guests-container clearfix">
		<?php 
		for($i = 1; $i <= 24; $i++) { 
			$img_link = "@web/assets/images/guest_$i.jpg";
			$img = Html::img($img_link);
			$a = Html::a($img, $img_link, ['class' => 'guest']);
			echo $a;
		}
		?>
	</div>

</main>