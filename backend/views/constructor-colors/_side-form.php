<?php 
use yii\helpers\Html;

$data_id = isset($model) ? $model->id : 'new';
$img = isset($model) ? $model::getSmallImageLink() . '/' . $model->small_image : '@web/images/camera_200.png';
$input_id = time() + rand();

?>

<div class="form clearfix" data-id="<?= $data_id ?>">
	<div class="image-container">
		<?= Html::img($img, [
			'class' => 'image',
			'alt' => 'Сторона',
		]) ?>
	</div>
	<div class="select-container">
		<select class="select">
			<?php for ($i = 0; $i < count($sides); $i++): ?>
				<option value="<?= $sides[$i]['id'] ?>"><?= $sides[$i]['name'] ?></option>
			<?php endfor; ?>
		</select>

		<div class="input-container">
			<label for="<?= $input_id ?>" class="side-label">Добавить изображение</label>
			<input type="file" class="side-input" id="<?= $input_id ?>">
		</div>
		
		<div class="side-buttons-container">
			<button class="save-side btn btn-success">Сохранить</button>
			<button class="remove-side btn btn-danger">Удалить</button>
		</div>

	</div>
	
	<div class="side-error"></div>

</div>