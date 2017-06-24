<br>
<br>
<h3>Дополнительные услуги печати:</h3>
<div class="print-attendances">
	
	<?php 
	for ($i = 0; $i < count($attendances); $i++): 
		$id = $attendances[$i]['id'];
		$name = $attendances[$i]['name'];
		$percent = $attendances[$i]['percent'];
	?>
	<div class="print-attendance" data-id="<?= $id ?>">
		<input type="text" 
			   class="form-control attendance-name attendance-input" 
			   value="<?= $name ?>" placeholder="Название услуги" 
		/>
		<input type="number" 
			   class="form-control attendance-percent attendance-input" 
			   min="1" max="100" 
			   value="<?= $percent ?>" placeholder="%" 
		/>
		<button class="save-attendance btn btn-success">Сохранить</button>
		<button class="remove-attendance btn btn-danger">Удалить</button>
	</div>
	<?php endfor; ?>

	<button id="add-attendance" class="btn btn-primary">Добавить услугу</button>

</div>