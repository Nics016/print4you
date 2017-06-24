<br>
<br>
<h3>Типы печати:</h3>
<div class="print-types">
	<?php 
	for ($i = 0; $i < count($print_types); $i++): 
		$id = $print_types[$i]['id'];
		$name = $print_types[$i]['name'];
	?>
	<div class="print-type" data-id="<?= $id ?>">
		<input type="text" class="type-input form-control" value="<?= $name ?>" placeholder="Название типа">
		<button class="save-type btn btn-success">Сохранить</button>
		<button class="remove-type btn btn-danger">Удалить</button>
	</div>
	<?php endfor; ?>
	<button id="add-type" class="btn btn-primary" style="margin-top: 10px;">Добавить тип печати</button>

</div>