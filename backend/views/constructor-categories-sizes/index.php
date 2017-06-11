<?php 
$this->title = 'Редактор категорий и размеров';

$this->registerCssFile('/css/constructor-categories-sizes.css');

$this->registerJsFile('/js/constructor-categories-sizes.js?v=' . time(), [
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
			  		>
			  	</div>
			</div>
		</li>
		
		<?php endfor; ?>

	</ul>

	<br>
	<button class="btn btn-primary glyphicon glyphicon-plus" id="add-category">
		<span class="btn-span">Добавить категорию</span>
	</button>
	
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

</div>