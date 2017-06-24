
<script>
	$('.count-number').bind('change', function() {
		$(this).css({'border': '0px'});
	});
	$('.count-number').bind('keyup', function() {
		$(this).css({'border': '0px'});
	});
</script>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Наименование размера</th>
			<th>Количество</th>
		</tr>
	</thead>

	<tbody>

		<?php 
		for ($i = 0; $i < count($avaliable_sizes); $i++): 
			$size = $avaliable_sizes[$i]['size'];
			$size_id = $avaliable_sizes[$i]['id'];
			$count = $avaliable_sizes[$i]['count'] != null ? $avaliable_sizes[$i]['count'] : 0;
		?>
			<tr>
				<td class="text-center" style="vertical-align: center;"><?= $size ?></td>
				<td class="text-center" style="vertical-align: center;">
					<input type="number" 
							min="0" data-size_id="<?= $size_id ?>" 
							value="<?= $count ?>" class="form-control count-number">
				</td>
			</tr>
		<?php endfor; ?>
	</tbody>
</table>
<input type="hidden" id="current-color_id" value="<?= $color_id ?>">