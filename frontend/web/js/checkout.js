jQuery(document).ready(function ($) {
	

	var _csrf = $('meta[name="csrf-token"]').attr('content');

	var errors = {
		firstname: true,
		phone: true,
		adress: true,
		comment: true,
	};

	var values = {
		firstname: true,
		phone: true,
		adress: true,
		comment: true,
	};

	var isSelfDelivery = false; // самомвывоз ли
	var wasPhoneAjaxRequest = false; // была ли проверка по аяксу
	var needCallBack = false; // нудно ли вызывать функцию после валидации телефона
	var phone = '';

	// обработчки события на имя
	$('#firstname').on('keyup', function () {
		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length < 3) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(helpBlock).html('Длина имени должна быть не менее 3-х символов!');
			errors.firstname = true;
		} else {
			$(parent).removeClass('has-error');
			$(parent).addClass('has-success');
			$(helpBlock).html('');
			errors.firstname = false;
			values.firstname = val;
		}
	});

	// обработчик события на телефон
	$('#phone').on('keyup', function() {
		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var elem = this;
		var val = $(this).val();

		// возбмем реальное значение номера
		phone = val.replace(/[^0-9]/g, '');
		phone = phone.substr(1);

		if (!phone.match(/^9[0-9]{9}$/g)) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(helpBlock).html('Телефон должен быть в формате +7 (999) 999-99-99!');
			errors.phone = true;
			return false;
		} 

		// проверим на существоание номера
		$(parent).removeClass('has-error');
		$(helpBlock).html('');
		
		$(elem).prop('disabled', true);

		$.ajax({
			url: '/cart/check-phone/',
			type: 'POST',
			data: {'csrf-token': _csrf, phone: phone},
			success: function (response) {
				wasPhoneAjaxRequest = true;
				if (response['status'] == 'ok') {
					$(parent).removeClass('has-error');
					$(parent).addClass('has-success');
					errors.phone = false;
					values.phone = phone;
				} else {
					$(parent).removeClass('has-success');
					$(parent).addClass('has-error');
					$(helpBlock).html('Данный телефон уже зарегистрирован, пожалуйста, войдите в личный кабинет.');
					errors.phone = true;
				}
				if (needCallBack) checkPhone();
				$(elem).prop('disabled', false);
			},

			error: function (err) {
				wasPhoneAjaxRequest = true;
				errors.phone = true;
				$(parent).addClass('has-error');
				$(helpBlock).html('Произошла ошибка проверки, попробуйте позже или сообщите нам!');
			}
		})
	});


	// обработчик событий на клик по типу доставки
	$('.delivery-radio').on('change', function () {

		if ($(this).val() == 'delivery') {
			$('#self-delivery-container').hide();
			$('#delivery-container').show();
			isSelfDelivery = false;
		} else if ($(this).val() == 'self') {
			$('#delivery-container').hide();
			$('#self-delivery-container').show();
			isSelfDelivery = true;
		}

	});

	// обработчки событий на дистанцию доставки
	$('#delivery-distance').on('change', function () {
		var parent = $(this).closest('.form-group');
		isSelfDelivery = false;
		if (!$(parent).hasClass('has-success')) $(parent).addClass('has-success');
	});

	// обработчки событий на клик по оффису 
	$('#office').on('change', function () {
		var parent = $(this).closest('.form-group');
		isSelfDelivery = true;
		if (!$(parent).hasClass('has-success')) $(parent).addClass('has-success');
	});

	// обрабочик события на ввод адреса
	$('#delivery-adress').on('keyup', function () {
		isSelfDelivery = false;

		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length < 7) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(helpBlock).html('Введите адрес по точнее!');
			errors.adress = true;
		} else {
			$(parent).removeClass('has-error');
			$(parent).addClass('has-success');
			$(helpBlock).html('');
			errors.adress = false;
			values.adress = val;
		}
	});


	// обрабочик события на комментарий
	$('#comment').on('keyup', function () {
		
		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length > 255) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(helpBlock).html('Комментарий должен быть меньше 255 символов!');
			errors.comment = true;
		} else {
			$(parent).removeClass('has-error');
			$(parent).addClass('has-success');
			$(helpBlock).html('');
			errors.comment = false;
			values.comment = val;
		}
	});


	// оформление заказа

	$('#checkout-form').on('submit', function (event) {
		event.preventDefault();
		triggerSubmitButton();
		// проверим на валидацию форму
		formValidate();
	});

	function formValidate() {
		$('#firstname').keyup();
		if (errors.firstname) {
			triggerSubmitButton(false);
			return false;
		}

		if (!isSelfDelivery) {
			$('#delivery-adress').keyup();
			if (errors.adress) {
				triggerSubmitButton(false);
				return false;
			}
		}

		$('#comment').keyup();
		if (errors.comment) {
			triggerSubmitButton(false);
			return false;
		}

		if (wasPhoneAjaxRequest) {

			if (errors.phone) {
				triggerSubmitButton(false);
				return false;
			}

		} else {
			needCallBack = true;
			$('#phone').keyup();
			return false;
		}

		// прошли валидацию
		requestData();
	}

	// проверка телефона
	function checkPhone() {
		needCallBack = false;
		if (wasPhoneAjaxRequest && !errors.phone) {
			requestData();
		} else {
			triggerSubmitButton(false);
			return false;
		}

	}

	function requestData() {
		var data = {'csrf-frontend': _csrf, firstname: values.firstname, phone: values.phone, comment: values.comment};
		if (!isSelfDelivery) {
			data['delivery_required'] = 1;
			data['address'] = values.adress;
			data['distance'] = $('#delivery-distance').val();
		} else {
			data['delivery_required'] = 0;
			data['office_id'] = $('#office').val();
		}

		$.ajax({
			url: '/checkout/',
			type: 'POST',
			data: data,
			success: function (response) {
				console.log(response);
				if (response['status'] == 'ok') {
					window.location.href = response['url'];
				} else {
					triggerSubmitButton(false);
				}
			},
			error: function (err) {
				console.log(err);
				triggerSubmitButton(false);
			}
		})
	}


	// показывает или скрывает лоадер загрузки на лодер
	function triggerSubmitButton (isLoading = true) {
		var btn = $('#form-submit');
		if (isLoading) {
			$(btn).prop('disabled', true);
			$(btn).find('.loading').css({display: 'inline-block'});	
		} else {
			$(btn).prop('disabled', false);
			$(btn).find('.loading').hide();
		}
	}
})