jQuery(document).ready(function($) {

	var _csrf = $('meta[name="csrf-token"]').attr('content');
	var colorId = $('#color-id').val();

	refreshBinds();

	// добавление формы через ajax
	$('#add-side').on('click', function () {
		var elem = $(this);
		elem.prop('disabled', true);

		$.ajax({
			url: '/constructor-colors/side-form',
			type: 'POST',
			data: {'_csrf-backend': _csrf},
			success: function (response) {
				if (typeof response['html'] != 'undefined') {
					$('#forms').append(response['html']);
					refreshBinds();
					elem.prop('disabled', false);
				} else {
					console.log(response);
					alert('Возникла ошибка, перезагрузите страницу!');
				}
			},
			error: function (error) {
				console.log(error);
				alert('Возникла ошибка, перезагрузите страницу!');
			}
		});
	});

	// сохранение формы
	function saveHandle() {
		var saveBtn = this;
		var parent = $(saveBtn).closest('.form');
		var error = $(parent).find('.side-error');
		var removeBtn = $(parent).find('.remove-side');
		var input = $(parent).find('.side-input');
		var select = $(parent).find('.select');
		var image = $(parent).find('.image');

		if (!inputChangeHandle(input)) return false;
		if (!selectValidation(select)) return false;

		$(saveBtn).prop('disabled', true);
		$(removeBtn).prop('disabled', true);

		var data = new FormData();

		data.append('_csrf-backend', _csrf);
		data.append('action', 'save');
		data.append('id', $(parent).attr('data-id'));
        data.append('image', input[0].files[0]);
        data.append('color_id', colorId);
        data.append('side_id', $(select).val());

		$.ajax({
			url: '/constructor-colors/edit-side',
			type: 'POST',
			contentType: false,
			processData: false,
			data: data,
			success: function (response) {
				if (response['status'] == 'ok') {
					$(parent).attr('data-id', response['id']);
					$(image).attr('src', response['image']);
					$(input).val('');
					error.hide();
				} else {
					error.text(response['msg']);
					error.show();
				}

				$(saveBtn).prop('disabled', false);
				$(removeBtn).prop('disabled', false);

			},
			error: function (error) {
				console.log(error);
				alert('Произошла ошибка, обновите страницу!');
			}
		});
	}

	// удаление записи
	function removeHandle() {

		if (!confirm('Точно удалить?'))
			return false;

		var saveBtn = this;
		var parent = $(saveBtn).closest('.form');
		var error = $(parent).find('.side-error');
		var removeBtn = $(parent).find('.remove-side');
		var id = $(parent).attr('data-id');

		if (id == 'new') {
			$(parent).remove();
			return false;
		}

		$(saveBtn).prop('disabled', true);
		$(removeBtn).prop('disabled', true);

		$.ajax({
			url: '/constructor-colors/edit-side',
			type: 'POST',
			data: {'_csrf-backend': _csrf, 'action': 'remove', id: id},
			success: function (response) {
				if (response['status'] == 'ok') {
					$(parent).remove();
				} else {
					error.text('Произошла обшибка в удалении!');
					error.show();
					$(saveBtn).prop('disabled', false);
					$(removeBtn).prop('disabled', false);
				}
			},
			error: function (error) {
				console.log(error);
				alert('Произошла ошибка, обновите страницу!');
			}
		})
	}

	// изменение инпута
	function inputChangeHandle(elem) {
		var id = $(elem).closest('.form').data('id');
		var error = $(elem).closest('.form').find('.side-error');
		var file = $(elem)[0].files[0];

		if (!file) {
			if (id == 'new') {
				error.text('Загрузите файл');
				error.show();
				return false;
			}
			return true;
		}

		var	parts = file.name.split('.'),
			ext = parts.length > 1 ? parts.pop() : false,
			size = file.size;

		if ((ext === false) || (ext != 'jpg' && ext != 'jpeg' && ext != 'png')) {
			error.text('Допустимые расширения файла: .jpg, .jpeg, .png');
			error.show();
			return false;
		}

		if (size / 1024 / 1024 > 2) {
			error.text('Допустимый размер файла 2Мб');
			error.show();
			return false;
		}

		error.hide();
		return true;
	}

	// валидация селекта
	function selectValidation(elem) {
		var error = $(elem).closest('.form').find('.side-error');
		var val = $(elem).val();

		if (typeof val == 'undefined' || isNaN(parseInt(val))) {
			error.text('Недопустимое значение стороны принта');
			error.show();
			return false;
		}

		error.hide();
		return true;
	}

	// рефрешит все обработчики событий
	function refreshBinds() {

		// загрузка картинки
		$('.side-input').unbind('change');
		$('.side-input').bind('change', function() {
			inputChangeHandle(this);
		});

		// сохрание формы
		$('.save-side').unbind('click', saveHandle);
		$('.save-side').bind('click', saveHandle);

		// удаление формы
		$('.remove-side').unbind('click', removeHandle);
		$('.remove-side').bind('click', removeHandle);
	}
})