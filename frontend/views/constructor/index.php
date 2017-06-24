<?php 

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Конструктор';

// common style.css
$css_file_name = Yii::getAlias('@frontend') . '/web/constructor-assets/css/constructor.css';
$this->registerCssFile('/constructor-assets/css/constructor.css?v='. filemtime($css_file_name));

// fabric.js
$this->registerJsFile('/constructor-assets/js/fabric.min.js', [
	'position' => \yii\web\View::POS_END,
]);

// common constructor.js
$js_file_name = Yii::getAlias('@frontend') . '/web/constructor-assets/js/constructor.js';
$this->registerJsFile('/constructor-assets/js/constructor.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
]);

// avaliable constructor fonts
$constructor_fonts = [
	'Acrobat Bold' => 'akrobatBold',
	'Sports World Regular' => 'sportsWorldRegular',
	'Helvetica Neue Cyr Roman' => 'helveticaNeueCyrRoman',
	'Proxima Nova Bold' => 'proximaNovaBold',
	'proxima Nova Semibold' => 'proximaNovaSemibold',
];

?>

<div class="container">
	

	<input type="hidden" id="print-sizes" value='<?= $print_sizes ?>'>

	<!-- TITLE  -->

	<div class="constructor-title-container">
		<span class="constructor-first-title">Создайте ваш</span>
		<span class="constructor-second-title">Уникальный принт</span>
	</div>
	
	<!-- ENDTITLE -->
	

	<!-- CONSTRUCTOR  -->

	<div class="constrructor-container clearfix">

		<!-- CONSTRUCTOR LEFT -->

		<div class="constructor-left-area">
			<div class="constructor-leftbar-header clearfix">
				<a href="#" class="constructor-leftbar-toogle current-toogle" data-toggle="products-tab">Товары</a>
				<a href="#" class="constructor-leftbar-toogle" data-toggle="text-tab">Текст</a>
				<a href="#" class="constructor-leftbar-toogle" data-toggle="image-tab">Фото</a>
			</div>
			
			<!-- CONSTRUCTOR PRODUCTS TAB -->
			<div id="products-tab" class="constructor-tab">
				<div class="constructor-leftbar-select-container">
					<select id="constructor-leftbar-select">
						<option value="all">Все</option>
					</select>
				</div>

				<div class="constructor-products-list clearfix" id="products-list">

				</div>
				
			</div>

			<!-- END CONSTRUCTOR PRODUCTS TAB -->

			<!-- CONSTRUCTOR TEXT TAB -->
			<div id="text-tab" class="constructor-tab" style="display: none;">
				<span class="add-text-btn" id="add-text">+ Новый текстовой слой</span>
				
				<span class="constructor-product-meta-title">Текст:</span>

				<textarea id="constructor-text" disabled></textarea>

				<span class="constructor-product-meta-title">Выбор шрифта:</span>

				<div class="constructor-text-options">
					<select id="constructor-text-font-family" disabled>
						<?php foreach ($constructor_fonts as $key => $value): ?>
						<option value="<?= $value ?>" style="font-family: <?= $value ?>;"><?= $key ?></option>
						<?php endforeach; ?>
					</select>

					<input type="color" id="text-color" disabled>

				</div>

			</div>
			<!-- END CONSTRUCTOR TEXT TAB -->

			<!-- CONSTRUCTOR IMAGE TAB -->
			<div id="image-tab" class="constructor-tab" style="display: none;">
				<span class="add-image-title">
					Здесь вы можете <button href="#" id="add-image">добавить изображение</button> с компьютера 
					<input type="file" id="fileupload" style="position:absolute; top:-9999999px;">
				</span>

				<ul class="image-rules-list">
					<li>загрузите .png или .jpg</li>
					<li>маленькое фото нельзя напечатать в большом размере</li>
					<li>чтобы нанести фото на всю ширину области печати, нужен размер не менее 1500*1500 пикселей</li>
					<li>максимальный размер файла 8мб</li>
					<li>использование изображения не должно нарушать авторских прав</li>
				</ul>

			</div>
			<!-- END CONSTRUCTOR IMAGE TAB -->


			<div class="constructor-product-color-container">
				
				<span class="constructor-product-meta-title">Цвет:</span>

				<div class="constructor-product-colors clearfix"></div>

				<span class="constructor-product-color-value"></span>

			</div>

			<div class="constructor-product-size-container">
				<span class="constructor-product-meta-title">Размер:</span>

				<div class="constructor-product-sizes clearfix">
				</div>

			</div>
			

		</div>

		<!-- END CONSTRUCTOR LEFT -->

		<div class="constructor-center-area" id="canvas-wrap">
			
			<span id="constructor-error">/</span>
			
			<div class="constructor-canvas-area">
				<div class="canvas-bg-container">
					<img src="" alt="" id="canvas-bg-image">
				</div>
					
				<div class="canvas-main-container">
					<canvas id="constructor-canvas" width="190" height="330"></canvas>
				</div>

				<div class="canvas-controls-contaner">
					<button class="canvas-control" id="delete-layer" disabled>Удалить</button>
					<button class="canvas-control" id="x-align-layer" disabled>Гор. выравнивание</button>
					<button class="canvas-control" id="y-align-layer" disabled>Верт. выравнивание</button>
				</div>
			</div>
		</div>


		<!-- CONSTRUCTOR RIGHT -->

		<div class="constructor-right-area">
			
			<span class="right-main-button" id="to-text">Добавить текст</span>
			<span class="right-main-button" id="to-image">Добавить Фото</span>

			<div class="constructor-product-sides-container clearfix">
				<div class="constructor-product-side" id="front-side">

					<span class="product-side-title">Лицевая <br>сторона</span>

					<div class="product-side-image-container">
						<img src="" alt="" class="product-side-image">
					</div>

				</div>

				<div class="constructor-product-side" id="back-side">
					<span class="product-side-title">Обратная <br>сторона</span>

					<div class="product-side-image-container">
						<img src="" alt="" class="product-side-image">
					</div>

				</div>

			</div>

			<div class="constructor-price-container">
				<span class="constructor-price-title">Цена:</span>
				<span class="constructor-price-value"></span>
			</div>
			
			<span class="right-buy-button" id="add-cart">Заказать</span>

		</div>

		<!-- END CONSTRUCTOR RIGHT -->
		
		<div id="constructor-loader">
			<div class='uil-ripple-css' style='transform:scale(0.99);'><div></div><div></div></div>
			<span id="loader-text"></span>
		</div>
		
	</div>
	
	<!-- END CONSTRUCTOR  -->
	<!-- SUCCESS MODAL -->
	<div id="success-modal">
		<div class="success-modal-container">
			<div class="success-modal-title clearfix">
				<span class="success-modal-title-text">Ваш заказ успешно добавлен в корзину!</span>
				<div class="success-modal-title-icon-container" data-action="close-success-modal">
					<?= Html::img('@web/constructor-assets/img/close-icon.png', [
						'alt' => 'close icon',
						'class' => 'success-modal-title-icon',
					]) ?>
				</div>
			</div>
			<div class="success-modal-body">
				<div class="success-modal-content clearfix">
					<a href="#" data-action="close-success-modal" class="success-modal-link success-modal-left">Продолжить</a>
					<a href="<?= Url::to(['cart/']) ?>" class="success-modal-link success-modal-right">В корзину</a>
				</div>
			</div>
		</div>
	</div>
</div>
