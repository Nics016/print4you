
<h3>Рзамеры печати:</h3>

<div class="constructor-print-sizes clearfix">

	<?php 
	for ($i = 0; $i < count($print_sizes); $i++): 
		$id = $print_sizes[$i]['id'];
		$name = $print_sizes[$i]['name'];
		$percent = $print_sizes[$i]['percent'];
	?>

	<div class="constructor-print-size" data-id="<?= $id ?>">
		<input type="text" class="print-size-name" placeholder="Имя" value="<?= $name ?>">
		<input type="number" class="print-size-percent"  min="1" max="100" placeholder="%" value="<?= $percent ?>">

		<div class="print-size-buttons clearfix">
			<span class="btn btn-success save-print-size glyphicon glyphicon-ok"></span>
			<span class="btn btn-danger remove-print-size glyphicon glyphicon-remove"></span>
		</div>
		
		<div class="print-size-modal">
			<span class="glyphicon glyphicon-cog print-size-modal-loader spin-animation"></span>
		</div>

	</div>
	<?php endfor; ?>

	<div class="add-size-btn-container">
		<span id="add-print-size" class="glyphicon glyphicon-plus-sign"></span>
	</div>

</div>

