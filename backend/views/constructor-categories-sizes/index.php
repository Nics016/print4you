<?php 
$this->title = 'Редактор категорий и размеров';

$css_file_name = Yii::getAlias('@backend') . '/web/css/constructor-categories-sizes.css';
$this->registerCssFile('/css/constructor-categories-sizes.css?v='. @filemtime($css_file_name));

$js_file_name = Yii::getAlias('@backend') . '/web/js/constructor-categories-sizes.js';
$this->registerJsFile('/js/constructor-categories-sizes.js?v=' . @filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
]);

?>

<div class="categories-container">
	<h3>Категории:</h3>
	
	<ul id="sortable">
		
		<?php for ($i = 0; $i < count($categories); $i++): ?>

		<li class="ui-state-default">
			<div class="form-group clearfix">
			  	<span class="glyphicon glyphicon-resize-vertical icon-container sortable-icon"></span>
			  	<span class="glyphicon glyphicon-remove icon-container category-remove"></span>
			  	<div class="input-container">
			  		<input type="text" 
			  			value="<?= $categories[$i]['name'] ?>" 
			  			data-id="<?= $categories[$i]['id'] ?>" 
			  			class="form-control category-input"
			  			disabled
			  		>
			  	</div>
			</div>
		</li>
		
		<?php endfor; ?>

	</ul>

	<br>
	<br>

	<button class="btn btn-success glyphicon glyphicon-ok" id="category-save">
		<span class="btn-span">Сохранить</span>
	</button>

	<br>
	<br>
	<h3>Размеры:</h3>
	
	<div class="sizes-conatiner clearfix">

		<ul id="size-sortable" class="clearfix pull-left">
			
			<?php for ($i = 0; $i < count($sizes); $i++): ?>
				<li class="pull-left">
					<div class="size clearfix">
						<div class="sizes-meta clearfix">
							<span class="move-size glyphicon glyphicon-resize-horizontal pull-left"></span>
							<span class="remove-size glyphicon glyphicon-remove pull-right"></span>
						</div>	

						<input type="text" 
							value="<?= $sizes[$i]['size'] ?>" 
							class="size-input" 
							data-id="<?= $sizes[$i]['id'] ?>"
						>

					</div>
				</li>
			<?php endfor; ?>
		</ul>

		<button id="add-size" class="pull-left glyphicon glyphicon-plus"></button>

	</div>

	<button class="btn btn-success glyphicon glyphicon-ok" id="sizes-save" style="margin-top: 10px;">
		<span class="btn-span">Сохранить</span>
	</button>
	

	<br>
	<br>
	<h3>Материалы:</h3>
	<div class="product-materials">
		<?php 
		for ($i = 0; $i < count($materials); $i++): 
			$id = $materials[$i]['id'];
			$name = $materials[$i]['name'];
		?>
		<div class="product-material" data-id="<?= $id ?>">
			<input type="text" class="material-input form-control" value="<?= $name ?>" placeholder="Название материала">
			<button class="save-material btn btn-success">Сохранить</button>
			<button class="remove-material btn btn-danger">Удалить</button>
		</div>
		<?php endfor; ?>
		<button id="add-material" class="btn btn-primary" style="margin-top: 10px;">Добавить материал</button>

	</div>

	<br>

	<h3>Стороны:</h3>
	<div class="product-sides">
		<?php 
		for ($i = 0; $i < count($sides); $i++): 
			$id = $sides[$i]['id'];
			$name = $sides[$i]['name'];
		?>
		<div class="product-side" data-id="<?= $id ?>">
			<input type="text" class="side-input form-control" value="<?= $name ?>" placeholder="Название материала">
			<button class="save-side btn btn-success">Сохранить</button>
			<button class="remove-side btn btn-danger">Удалить</button>
		</div>
		<?php endfor; ?>
		<button id="add-side" class="btn btn-primary" style="margin-top: 10px;">
			Добавить сторону
		</button>

	</div>

</div>