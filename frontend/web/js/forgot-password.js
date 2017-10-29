jQuery(document).ready(function($){

	$('#sms-code').mask('9 9 9 9');

	var _csrf = $('meta[name="csrf-token"]').attr('content');
	var phone = false;

	// phone validation
	$('#phone').on('change', function(){
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
			$(helpBlock).html('Неверный номер!');
			phone = false;

		} else {

			$(parent).addClass('has-success');
			$(parent).removeClass('has-error');
			$(helpBlock).html('');

		}

	});

	// send sms code
	$('#forgot-password-form').on('submit', function(event){
		event.preventDefault();
		var submitBtn = $('#forgot-password-submit');

		if (phone === false) {
			$('#phone').change();
			return false;
		}

		submitBtn.prop('disabled', true);

		$.ajax({
			url: '/forgot-password/',
			type: 'POST',
			data: {'csrf-token': _csrf, action: 'send', phone: phone},
			success: function(response) {

				if (response['status'] == 'ok') {
					$('#sms-code').prop('disabled', false);
					showTimer(response['seconds']);
				} else if (response['status'] == 'fail'){
					console.log(response);
					// подсветим ошибки в полях
					showErrors(response);
					submitBtn.prop('disabled', false);
				}
				
			},
			error: function(err) {
				console.log(err);
				submitBtn.prop('disabled', false);
			}
		});

	});

	// sms code validation
	$('#sms-code').on('keyup', function() {
		var code = $(this).val().replace(/[^0-9]/g, '');
		var elem = this;
		var submitBtn = $('#forgot-password-submit');

		if (code.length == 4) {
			$(elem).prop('disabled', true);
			$(submitBtn).prop('disabled', true);

			$.ajax({
				url: '/forgot-password/',
				type: 'POST',
				data: {'csrf-token': _csrf, action: 'verify', phone: phone, code: code},
				success: function(response) {

					if (response['status'] == 'ok') {
						
						// вернем верстку к начальному виду
						clearAll();
						showSuccess();

					} else if (response['status'] == 'fail'){
						// подсветим ошибки в полях
						showErrors(response);
						$(elem).prop('disabled', false);
					}
					
				},
				error: function(err) {
					console.log(err);
					$(elem).prop('disabled', false);
					$(submitBtn).prop('disabled', false);
				}
			});
		}

	});


	// подсвечивает ошибки полей при ajax запросе
	function showErrors(response) {

		var input = false;

		switch (response['field']) {
			case 'phone':
				input = $('#phone');
				$('#sms-code').prop('disabled', true);
				break;
			case 'sms-code':
				input = $('#sms-code');
				break;

			case 'timer':
				showTimer(response['seconds']);
				return false;
				// no break

			default:
				return false;
				// no break
		}

		input.closest('.form-group').removeClass('has-success');
		input.closest('.form-group').addClass('has-error');
		input.siblings('.help-block').html(response['message']);
	}

	// показывает интервал во времени
	function showTimer(seconds) {
		if (seconds < 0) return false;
		$('#timer').text(secondsToText(seconds));
		$('.timer-container').show();
		$('#forgot-password-submit').prop('disabled', true);

		var interval = setInterval(function(){
			seconds--;

			if (seconds < 0) {
				$('.timer-container').hide();
				$('#forgot-password-submit').prop('disabled', false);
				clearInterval(interval);
				return false;
			}

			$('#timer').text(secondsToText(seconds));

		}, 1000);
	}

	// переводт секунды в текстовое представление в формате 00:00
	function secondsToText(seconds) {
		var minutes = Math.floor(seconds / 60);
		seconds = seconds - minutes * 60;
		var str = minutes < 10 ? '0' + minutes + ':' : minutes + ':';
		str += seconds < 10 ? '0' + seconds : seconds;
		return str;
	}

	function clearAll() {
		$('.timer-container').hide();
		$('#phone').val('');
		$('#phone').closest('.form-group').removeClass('has-success');
		$('#phone').closest('.form-group').removeClass('has-error');
		$('#phone').siblings('.help-block').html('');

		$('#sms-code').val('');
		$('#sms-code').prop('disabled', true);
		$('#sms-code').closest('.form-group').removeClass('has-success');
		$('#sms-code').closest('.form-group').removeClass('has-error');
		$('#sms-code').siblings('.help-block').html('');

		$('#forgot-password-submit').prop('disabled', false);
	}

	function showSuccess() {
		$('#success-modal').modal('show');
	}

});