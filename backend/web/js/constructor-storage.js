jQuery(document).ready(function($){

	var _csrf = $('meta[name="csrf-token"').attr('content');

	var modal = $('#myModal');
	var modalTitle = modal.find('#myModalLabel');
	var formsContainer = modal.find('#forms-container');
	var modalLoader = modal.find('#modal-loader');

	// отобразим модалку
	$('.open-modal').on('click', function(event){
		event.preventDefault();

		var parent = $(this).closest('tr');
		var colorId = $(this).closest('td').data('color_id');
		var name = $(parent).find('td[data-attr="name"]').data('val');

		formsContainer.hide();
		modalLoader.show();
		modalTitle.text(name);
		modal.modal('show');

		$.ajax({
			url: '/constructor-sklad/get-modal',
			data: {'_csrf-backend': _csrf, color_id: colorId},
			type: 'POST',
			success: function (msg) {

				if (msg['status'] == 'ok') {
					formsContainer.html(msg['html']);
					modalLoader.hide();
					formsContainer.show();
				} else {
					console.log(msg);
					alert('Произошла ошибка, обновите страницу!');
				}

			},
			error: function (err) {
				console.log(err);
				alert('Произошла ошибка, обновите страницу!');
			}
		})

	});

	var inputErr = false;
	var inputData = [];

	// сохранение изменений
	$('#save-changes').on('click', function(event) {
		event.preventDefault();

		var colorId = modal.find('#current-color_id').val();
		var button = $(this);

		inputErr = false;

		// если ввели некоректные данные, если все ок, заполним массив
		modal.find('.count-number').each(function(index, elem) {
			var val = parseInt($(elem).val());
			if (val < 0 || isNaN(val)) {
				inputErr = true;
				inputData = [];
				$(elem).css({'border': '1px solid red'});
				return false;
			} else {
				inputData.push({
					size_id: $(elem).data('size_id'),
					count: val,
				});
			}
		});

		// если нет ошибки
		if (!inputErr) {

			button.prop('disabled', true);
			formsContainer.hide();
			modalLoader.show();

			$.ajax({
				url: '/constructor-sklad/set-data',
				data: {'_csrf-backend': _csrf, color_id: colorId, data: JSON.stringify(inputData)},
				type: 'POST',
				success: function (msg) {

					console.log(msg);

					if (msg['status'] == 'ok') {
						setTimeout(function () {
							button.prop('disabled', false);
							formsContainer.show();
							modalLoader.hide();
						}, 500);
					} else {
						console.log(msg);
						alert('Произошла ошибка, обновите страницу!');
					}
				},
				error: function (err) {
					console.log(err);
					alert('Произошла ошибка, обновите страницу!');
				}
			});
		}

	});

});