<?php 

use yii\helpers\Html;
$this->title = 'Редактор сторон цвета "' . $color->name . '"';

$css_file_name = Yii::getAlias('@backend') . '/web/css/constructor-color-sides.css';
$this->registerCssFile('/css/constructor-color-sides.css?v=' . filemtime($css_file_name));

// common constructor-color-sides.js
$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-color-sides.js';
$this->registerJsFile('/js/constructor-color-sides.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => [
        'yii\bootstrap\BootstrapAsset',
	],
]);

?>

<input type="hidden" id="color-id" value="<?= $color->id ?>">

<div class="constructor-color-sides">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>
		<?= Html::a('Вернуться к цвету', ['update', 'id' => $color->id], [
			'class' => 'btn btn-info',
		]) ?>
	</p>
	
	<div id="forms">
		<?php
		foreach ($models as $model) 
			echo $this->render('_side-form', ['sides' => $sides, 'model' => $model])
		?>
	</div>
	
	<button id="add-side" class="btn btn-success">Добавить сторону</button>

</div>	
