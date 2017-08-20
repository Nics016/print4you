jQuery(document).ready(function ($) {

	var _csrf = $('meta[name="csrf-token"]').attr('content');

	var errors = {
		firstname: true,
		email: true,
		adress: true,
		phone: true,
	};

	var values = {
		firstname: false,
		email: false,
		adress: false,
		phone: false,
	};

	// был ли отправлен аякс запрос
	var hasAjaxSend = {
		email: false,
		phone: false,
	};

	// нужно ли вызвать колбэк
	var needCallBack = {
		email: false,
		phone: false,
	};

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

	$('#email').on('change', function () {
		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var val = $(this).val();
		var elem = this;
		var pattern = /^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/g;

		if (!val.match(pattern)) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(helpBlock).html('Email должен быть типа example@mail.ru!');
			errors.email = true;
			return false;
		}

		$(parent).removeClass('has-error');
		$(helpBlock).html('');
		
		$(elem).prop('disabled', true);

		$.ajax({
			url: '/site/check-user-data/',
			type: 'POST',
			data: {'csrf-token': _csrf, action: 'email', value: val},
			success: function (response) {
				hasAjaxSend.email = true;
				if (response['status'] == 'ok') {
					$(parent).removeClass('has-error');
					$(parent).addClass('has-success');
					errors.email = false;
					values.email = val;
				} else {
					$(parent).removeClass('has-success');
					$(parent).addClass('has-error');
					$(helpBlock).html('Такой email уже используется!');
					errors.email = true;
				}
				if (needCallBack.email) secondStep();
				$(elem).prop('disabled', false);
			},

			error: function (err) {
				hasAjaxSend.email = true;
				errors.email = true;
				$(parent).addClass('has-error');
				$(helpBlock).html('Произошла ошибка проверки, попробуйте позже или сообщите нам!');
			}
		})
	});


	// обработчик события на телефон
	$('#phone').on('change', function() {
		var parent = $(this).closest('.form-group');
		var helpBlock = $(this).siblings('.help-block');
		var elem = this;
		var val = $(this).val();

		// возбмем реальное значение номера
		var phone = val.replace(/[^0-9]/g, '');
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
			url: '/site/check-user-data/',
			type: 'POST',
			data: {'csrf-token': _csrf, action: 'phone', value: phone},
			success: function (response) {
				hasAjaxSend = true;
				if (response['status'] == 'ok') {
					$(parent).removeClass('has-error');
					$(parent).addClass('has-success');
					errors.phone = false;
					values.phone = phone;
				} else {
					$(parent).removeClass('has-success');
					$(parent).addClass('has-error');
					$(helpBlock).html('Такой телефон уже используется!');
					errors.phone = true;
				}
				if (needCallBack.phone) thirdStep();
				$(elem).prop('disabled', false);
			},

			error: function (err) {
				hasAjaxSend = true;
				errors.phone = true;
				$(parent).addClass('has-error');
				$(helpBlock).html('Произошла ошибка проверки, попробуйте позже или сообщите нам!');
			}
		})
	});

	// обрабочик события на ввод адреса
	$('#adress').on('keyup', function () {

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

	$('#form-submit').on('click', function(event) {
		event.preventDefault();
		triggerSubmitButton();
		firstStep();
	});

	// три шага валидации в трех функциях из за аякс запроса
	function firstStep() {
		$('#firstname').keyup();
		if (errors.firstname) {
			triggerSubmitButton(false);
			return false;
		}

		if (hasAjaxSend.email) {

			if (errors.email) {
				triggerSubmitButton(false);
				return false;
			}

		} else {
			needCallBack.email = true;
			$('#email').change();
			return false;
		}

		secondStep();
	}

	function secondStep() {
		needCallBack.email = false;

		if (errors.email) {
			triggerSubmitButton(false);
			return false;
		}

		if (hasAjaxSend.phone) {

			if (errors.phone) {
				triggerSubmitButton(false);
				return false;
			}

		} else {
			needCallBack.phone = true;
			$('#phone').change();
			return false;
		}

		thirdStep();
	}


	function thirdStep() {
		needCallBack.phone = false;

		if (errors.phone) {
			triggerSubmitButton(false);
			return false;
		}

		$('#adress').keyup();
		if (errors.adress) {
			triggerSubmitButton(false);
			return false;
		}

		requestData();
	}

	// отправляем данные на севрвер
	function requestData() {
		var data = {};
		data['csrf-token'] =_csrf;
		data['firstname'] = values.firstname;
		data['email'] = values.email;
		data['phone'] = values.phone;
		data['address'] = values.adress;

		$.ajax({
			url: '/site/change-user-data/',
			type: 'POST',
			data: data,
			success: function (response) {
				if (response['status'] == 'ok') {
					$('#cabinet-modal').modal('hide');
					triggerSubmitButton(false);
					$('#username .firstname').text(values.firstname);
					$('#head-username').text(values.firstname);
					clearUserDataInputs();
					showSuccessModal();
				} else {	
					window.location.reload(true);
				}

				triggerSubmitButton(false);
			},
			error: function (err) {
				triggerSubmitButton(false);
			}
		});

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

	function clearUserDataInputs() {
		$('#cabinet-form .form-group').each(function(index, elem) {
			$(elem).removeClass('has-success');
			$(elem).removeClass('has-error');
			$(elem).find('.help-block').html('');
		});
	}

	function showSuccessModal() {
		$('.modal').modal('hide');
		setTimeout(function(){
			$('#success-modal').modal('show');
		}, 700);
	}


	/* Изменение пароля */

	var passwordValues = {
		old: null,
		new: null,
		repeat: null,
	};

	// обработчик событий на ввод старого пароля
	$('#old-password').on('keyup', function() {
		var parent = $(this).closest('.form-group');
		var help = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length > 0) {
			$(parent).removeClass('has-error');
			$(parent).addClass('has-success');
			$(help).html('');
			passwordValues.old = val;
			return false;
		}

		$(parent).removeClass('has-success');
		$(parent).addClass('has-error');
		$(help).html('Введите старый пароль!');
		passwordValues.old = null;
	});

	$('#new-password').on('keyup', function() {
		var parent = $(this).closest('.form-group');
		var help = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length == 0) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Введите новый пароль!');
			passwordValues.new = null;
			return false;
		}

		if (val.match(/[^0-9a-zA-Z]+/g)) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Пароль должен содержать только латинские символы и/или цифры!');
			passwordValues.new = null;
			return false;
		}

		if (val.length < 8) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Пароль должен быть не менее 8 символов!');
			passwordValues.new = null;
			return false;
		}

		if (val.length > 16) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Пароль должен быть менее 16 символов!');
			passwordValues.new = null;
			return false;
		}

		$(parent).removeClass('has-error');
		$(parent).addClass('has-success');
		$(help).html('');
		passwordValues.new = val;
	});

	// обработчик событий на повторение пароля
	$('#repeat-password').on('keyup', function() {
		var parent = $(this).closest('.form-group');
		var help = $(this).siblings('.help-block');
		var val = $(this).val();

		if (val.length == 0) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Повторите новый пароль!');
			passwordValues.repeat = null;
			return false;
		}

		if (val != $('#new-password').val()) {
			$(parent).removeClass('has-success');
			$(parent).addClass('has-error');
			$(help).html('Пароли не совпадают!');
			passwordValues.repeat = null;
			return false;
		}

		$(parent).removeClass('has-error');
		$(parent).addClass('has-success');
		$(help).html('');
		passwordValues.repeat = val;
	});

	// сабмит формы пароля
	$('#password-form-submit').on('click', function (event){
		event.preventDefault();

		var elem = this;

		$('#old-password').keyup();
		$('#new-password').keyup();
		$('#repeat-password').keyup();

		for (key in passwordValues) {
			if (passwordValues[key] === null)
				return false;
		}

		triggerPasswordInputs(true);

		$.ajax({
			url: '/site/change-user-password/',
			type: 'POST',
			data: {'csrf-token': _csrf, 'old_password': passwordValues.old, 'new_password': passwordValues.new},
			success: function (response) {
				if (response['status'] == 'fail') {

					switch(response['field']) {
						case 'old': 
							$('#old-password').closest('.form-group').removeClass('has-success');
							$('#old-password').closest('.form-group').addClass('has-error');
							$('#old-password').siblings('.help-block').html(response['message']);
							passwordValues.old = null;
							break;

						case 'new': 
							$('#new-password').closest('.form-group').removeClass('has-success');
							$('#new-password').closest('.form-group').addClass('has-error');
							$('#new-password').siblings('.help-block').html(response['message']);
							passwordValues.new = null;
							break;

						default:
							return false;
							// no break
					}

				} else if (response['status'] == 'ok') {
					clearAllPasswordInputs();
					showSuccessModal();
				}

				triggerPasswordInputs(false);
			},
			error: function (err) {
				triggerPasswordInputs(false);
			}
		});

	});

	function clearAllPasswordInputs() {
		$('#old-password').val('');
		$('#old-password').closest('.form-group').removeClass('has-error');
		$('#old-password').closest('.form-group').removeClass('has-success');
		$('#old-password').siblings('.help-block').html('');

		$('#new-password').val('');
		$('#new-password').closest('.form-group').removeClass('has-error');
		$('#new-password').closest('.form-group').removeClass('has-success');
		$('#new-password').siblings('.help-block').html('');

		$('#repeat-password').val('');
		$('#repeat-password').closest('.form-group').removeClass('has-error');
		$('#repeat-password').closest('.form-group').removeClass('has-success');
		$('#repeat-password').siblings('.help-block').html('');
	}

	function triggerPasswordInputs(disable) {
		if (disable) {
			$('#old-password').prop('disabled', true);
			$('#new-password').prop('disabled', true);
			$('#repeat-password').prop('disabled', true);
			$('#password-form-submit').prop('disabled', true);
			$('#password-form-submit').find('.loading').css({display: 'inline-block'});
		} else {
			$('#old-password').prop('disabled', false);
			$('#new-password').prop('disabled', false);
			$('#repeat-password').prop('disabled', false);
			$('#password-form-submit').prop('disabled', false);
			$('#password-form-submit').find('.loading').hide();
		}
	}

})