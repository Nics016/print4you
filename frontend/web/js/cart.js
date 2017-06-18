jQuery(document).ready(function($){
	
	var _csrf = $('meta[name="csrf-token"]').attr('content');

	$('.push-product').bind('click', function() {
		changeProductCount(this, 'push')
	});

	$('.pop-product').bind('click', function() {
		changeProductCount(this, 'pop')
	});

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

								changeCheckoutHtml(msg['checkout_html']);

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

	// изенение количества продукции
	function changeProductCount(elem, action) {

		var parent = $(elem).closest('.constructor-product-row');
		var id = parseInt($(parent).find('.product-id').val());
		var price = parseInt($(parent).find('.product-price').val());
		var loadingOverlay = $(parent).find('.loading-product-container');
		var loadingText = $(loadingOverlay).find('.loading-text');

		if (isNaN(id)) {
			$(parent).remove();
		} else {
			$(loadingText).text('Меняем количество, подождите...');
			$(loadingOverlay).show();

			$.ajax({
				url: '/cart/change-product-count/',
				data: {'_csrf-frontend': _csrf, id: id, action: action},
				type: 'POST',
				success: function (response) {

					if (response['status'] == 'ok') {
						changeCheckoutHtml(response['checkout_html']);
						$(parent).find('.product-count').text(response['count']);
						$(parent).find('.product-price-count').text(response['count']);
						$(parent).find('.product-price-sum-value').text(response['count'] * price);
					}

					setTimeout(function (){
						$(loadingOverlay).hide();
					}, 500);
				},
				error: function (err) {
					console.log(err);
					setTimeout(function (){
						$(loadingOverlay).hide();
					}, 500);
				}
			});
		}
	}


	// изменить цену внизу
	function changeCheckoutHtml(html) {
		$('#checkout-container').html(html);
	}

});