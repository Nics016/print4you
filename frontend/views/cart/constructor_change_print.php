
<div class="change-print-form-row">
	<span class="change-print-form-label">Метод печати</span>
	<?php if (!empty($print)): ?>
		<select name="type" class="change-print-form-select" data-action="метод печати">
			
			<?php
			// выведем все типы печати
			$current_type = $print['type_id'];
			$types = $print_avaliable_prices['types'];
			for ($i = 0; $i < count($types); $i++):
				$id = $types[$i]['id'];
				$name = $types[$i]['name'];
				$selected = $id == $current_type ? 'selected' : '';
			?>
				<option value="<?= $id ?>" <?= $selected ?>><?= $name ?></option>
			<?php endfor; ?>
		</select>
	<?php else: ?>
		<span class="not-avaliable-select">Выбор недоступен</span>
	<?php endif; ?>
</div>
		
	

<div class="change-print-form-row">
	<span class="change-print-form-label">Цветность</span>
	<?php
	$colors = $print_avaliable_prices['colors'] ?? [];
	if(!empty($colors)):
		$current_color = $print['color'];
	?>
		<select name="color" class="change-print-form-select" data-action="цветность печати">
			<?php
			for ($i = 0; $i < count($colors); $i++):
					$color = $colors[$i];
					$selected = $color == $current_color ? 'selected' : '';
			?>
			
				<option value="<?= $color ?>" <?= $selected ?>><?= $color ?></option>
			<?php endfor; ?>
		</select>
	<?php else: ?>
		<span class="not-avaliable-select">Выбор недоступен</span>
	<?php endif; ?>
</div>




<div class="change-print-form-row">
	<span class="change-print-form-label">Доп. услуги</span>
	<?php
	$attendances = $print_avaliable_prices['attendances'] ?? [];
	if(!empty($attendances)):
		$current_attendance = $print['attendance']['id'];
	?>

		<select name="attendance" class="change-print-form-select" data-action="доп. услугу">
			<option value="">Выберите услугу</option>
			<?php
			for ($i = 0; $i < count($attendances); $i++):
				$id = $attendances[$i]['id'];
				$name = $attendances[$i]['name'];
				$percent = $attendances[$i]['percent'];
				$selected = $id === $current_attendance ? 'selected' : '';
			?>
			
				<option value="<?= $id ?>" <?= $selected ?>><?= $name ?> (+<?= $percent ?>%)</option>
			<?php endfor; ?>
		</select>

	<?php else: ?>
		<span class="not-avaliable-select">Выбор недоступен</span>
	<?php endif; ?>
	
</div>





	
	