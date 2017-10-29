jQuery(document).ready(function($) {

	var _csrf = $('meta[name="csrf-token"').attr('content');

	$('.save-product-changes').on('click', function() {

		// нудные элементы верстки на тсранице
		var elem = $(this);
		var parent = elem.closest('.product-side-data');
		var tr = $(parent).closest('tr');
		var error = $(parent).find('.side-error');

		// возьмем данные о стороне и текущем товаре
		var sideName = $(parent).data('side-name');
		var sideId = $(parent).data('side-id') ? $(parent).data('side-id') : false;
		var id = $(tr).data('id');

		// возьмеме данные об итгговой цене и типу печати
		var totalPrice = $(tr).find('.total-price').val();
		var typeSelect = $(parent).find('.type-select');
		var typeId = typeSelect.length > 0 ? $(typeSelect).val() : false;

		var data = {'_csrf-backend': _csrf, id: id, side_name: sideName, total_price: totalPrice};

		if (sideId !== false) data['side_id'] = sideId;
		if (typeId !== false) data['type_id'] = typeId;


		elem.prop('disabled', true);
		
		$.ajax({
			url: '/orders/change-product-data',
			type: 'POST',
			data: data,
			success: function (response) {

				if (response['status'] == 'ok') {

					$(error).text('');
					if (typeId !== false)
						$(parent).find('.current-type').text(response['current_type']);
					$(tr).find('.product-price').html(response['price'] + 'Р');
					$('#order-full-price').html(response['order_price']);

				} else {
					$(error).text(response['message']);
				}

				elem.prop('disabled', false);
			},
			error: function (err) {
				console.log(err);
				alert('Возникла ошибка, обновите страницу!');
			}
		});

	});
});