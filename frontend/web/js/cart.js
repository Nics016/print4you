jQuery(document).ready(function($){
	
	var _csrf = $('meta[name="csrf-token"]').attr('content');

	$('.push-product').bind('click', function() {
		changeProductCount(this, 'push')
	});

	$('.pop-product').bind('click', function() {
		changeProductCount(this, 'pop')
	});


	$('.product-count').bind('change', function() {
		var val = parseInt($(this).val());
		if (isNaN(val) || val <= 0) {
			$(this).css({'border': '1px solid red'});
		} else {
			$(this).css({'border': '1px solid #555'});
			changeProductCount(this, 'count', val);
		}
		
	});

	// показ информации о цене
	$(document).on('click', '.about-price', function(){
		var action = $(this).attr('data-action');
		var parent = $(this).closest('.product-price-container');

		if (action == 'open') {
			$(parent).find('.product-prices').slideUp(200);
			$(parent).find('.about-price-block').slideDown(200);
			$(this).attr('data-action', 'close');
			$(this).addClass('align');
			$(this).text('Скрыть');
		} else {
			$(parent).find('.about-price-block').slideUp(200);
			$(parent).find('.product-prices').slideDown(200);
			$(this).attr('data-action', 'open');
			$(this).removeClass('align');
			$(this).text('Подробнее о цене');
		}
	});

	// показ редактора печати
	$('.change-print').on('click', function() {
		var parent = $(this).closest('.constructor-product-row');
		var mainInfoContainer = $(parent).find('.constructor-main-info');
		var changePrintContainer = $(parent).find('.change-print-container');
		mainInfoContainer.hide();
		changePrintContainer.slideDown(200);
	});

	// показ основной информации
	$('.change-meta').on('click', function() {
		var parent = $(this).closest('.constructor-product-row');
		var mainInfoContainer = $(parent).find('.constructor-main-info');
		var changePrintContainer = $(parent).find('.change-print-container');
		changePrintContainer.hide();
		mainInfoContainer.slideDown(200);
	});

	// изменение размера продукта
	$('.constructor-product-sizes').bind('change', function() {
		var parent = $(this).closest('.constructor-product-row');
		var id = parseInt($(parent).find('.product-id').val());
		var loadingOverlay = $(parent).find('.loading-product-container');
		var loadingText = $(loadingOverlay).find('.loading-text');
		var sizeId = $(this).val();

		if (isNaN(id)) {
			$(parent).remove();
		} else {
			$(loadingText).text('Меняем размер, подождите...');
			$(loadingOverlay).show();

			$.ajax({
				url: '/cart/change-product-size/',
				data: {'_csrf-frontend': _csrf, id: id, size_id: sizeId},
				type: 'POST',
				success: function (response) {

					if (response['status'] == 'ok') {
						setTimeout(function (){
							$(loadingOverlay).hide();
						}, 500);
					} else {
						$(loadingText).text('Произошла ошибка, обновите страницу!');
					}

					
				},
				error: function (err) {
					$(loadingText).text('Произошла ошибка, обновите страницу!');
				}
			});
		}
	});

	// показываем модалку удаления
	$('.remove-icon-container').bind('click', function() {
		$(this).closest('.constructor-product-row').find('.remove-product-container').show();
	});

	// само удаление
	$('.remove-product').bind('click', function() {

		// берем все данные и все модалки
		var data = $(this).data('action');
		var parent = $(this).closest('.constructor-product-row');
		var id = parseInt($(parent).find('.product-id').val());
		var removeOverlay = $(parent).find('.remove-product-container');
		var loadingOverlay = $(parent).find('.loading-product-container');
		var loadingText = $(loadingOverlay).find('.loading-text');

		if (isNaN(id)) {
			$(parent).remove();
			return false;
		}

		if (data == 'close') {
			$(removeOverlay).hide();
		} else if (data == 'remove') {
			$(removeOverlay).hide();
			$(loadingText).text('Удаляем товар, подождите');
			$(loadingOverlay).show();

			$.ajax({
				url: '/cart/product-remove/',
				data: {'_csrf-frontend': _csrf, id: id},
				type: 'POST',
				success: function (msg) {
					if (msg['status'] == 'ok') {

						setTimeout(function(){
							// сначала проверим, естть ли html
							if (msg['html'] != 'none') {
								// вставим html
								$('#cart-main').html(msg['html']);
							} else {

								// иначе, уменьшим у всех айдищшники
								$('.product-id').each(function(index, elem) {
									var val = $(elem).val();
									if (parseInt(val) > id)
										$(elem).val(val - 1);
								})

								changeFullPrice(msg['basket_price']);

								$(parent).remove();

							}
						}, 500);

					} else {
						$(loadingText).text('Произошла ошибка, обновите страницу!');
					}
				},
				error: function (err) {
					console.log(err);
					$(loadingText).text('Произошла ошибка, обновите страницу!');
				}
			});

			
		}
	});

	// изменение параметра принта
	$(document).on('change', '.change-print-form-select', function() {
		var parent = $(this).closest('.change-print-side');
		var mainContainer = $(parent).closest('.constructor-product-row');
		var id = parseInt($(mainContainer).find('.product-id').val());

		if (isNaN(id)) {
			$(parent).remove();
			return false;
		}

		var action = $(this).data('action');
		var side = $(parent).data('side');
		var value = $(this).val();
		var name = $(this).attr('name');
		var loadingOverlay = $(mainContainer).find('.loading-product-container');
		var loadingText = $(loadingOverlay).find('.loading-text');
		$(loadingText).text('Меняем ' +  action +', подождите...');
		$(loadingOverlay).show();
		

		$.ajax({
			url: '/cart/change-print-option/',
			data: {'_csrf-frontend': _csrf, id: id, side: side, value: value, name: name},
			type: 'POST',
			success: function (response) {
				if (response['status'] == 'ok') {

					// изменяем цену коризны
					changeFullPrice(response['basket_price']);

					// изменим html продукта
					$(mainContainer).find('.product-price-container').html(response['product_price_html']);

					// изменим html настроек принта
					$(parent).html(response['print_html']);

					setTimeout(function(){
						$(loadingOverlay).hide();
					}, 500);

				} else {
					console.log(response);
					$(loadingText).text('Произошла ошибка, обновите страницу');
				}
			},
			error: function (err) {
				console.log(err);
				$(loadingText).text('Произошла ошибка, обновите страницу');
			}
		});
	});

	// изенение количества продукции
	function changeProductCount(elem, action, count = false) {

		var parent = $(elem).closest('.constructor-product-row');
		var id = parseInt($(parent).find('.product-id').val());
		var loadingOverlay = $(parent).find('.loading-product-container');
		var loadingText = $(loadingOverlay).find('.loading-text');

		if (isNaN(id)) {
			$(parent).remove();
		} else {
			$(loadingText).text('Меняем количество, подождите...');
			$(loadingOverlay).show();

			$.ajax({
				url: '/cart/change-product-count/',
				data: {'_csrf-frontend': _csrf, id: id, action: action, count: count},
				type: 'POST',
				success: function (response) {
					//console.log(response);
					if (response['status'] == 'ok') {

						// изменяем цену коризны
						changeFullPrice(response['basket_price']);

						// изменим значение инпута
						$(parent).find('.product-count').val(response['count']);
						
						// изменим html продукта
						$(parent).find('.product-price-container').html(response['product_price_html']);

						// изменим html сторон
						$(parent).find('.change-print-side[data-side="front"]').html(response['front_print_html']);
						$(parent).find('.change-print-side[data-side="back"]').html(response['back_print_html']);

						setTimeout(function (){
							$(loadingOverlay).hide();
						}, 500);
						
					} else {
						$(loadingText).text('Произошла ошибка, обновите страницу');
					}

				},
				error: function (err) {
					console.log(err);
					setTimeout(function (){
						$(loadingText).text('Произошла ошибка, обновите страницу');
					}, 500);
				}
			});
		}
	}


	// изменить цену внизу
	function changeFullPrice(val) {
		$('#full-price').text(val);
	}

});