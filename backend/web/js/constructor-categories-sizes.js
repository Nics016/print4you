jQuery(document).ready(function($){

	var categoriesValues = [];
	var sizesValues = [];
	var csrf = $('meta[name="csrf-token"]').attr('content');

	/*Редактор категорий*/

	$( "#sortable" ).sortable({
		cursor: "move",
		handle: '.sortable-icon' 
	});

    $( "#sortable" ).disableSelection();

    // добавление верстки категори
    $('#add-category').on('click', function() {

    	var li = $(document.createElement('li'));
    	li.addClass('ui-state-default');

    	var formContainer = $(document.createElement('div'))
    	formContainer.addClass('form-group clearfix')

    	var moveSpan = $(document.createElement('span'))
    	moveSpan.addClass('glyphicon glyphicon-resize-vertical icon-container sortable-icon');

    	var removeSpan = $(document.createElement('span'))
    	removeSpan.addClass('glyphicon glyphicon-remove icon-container category-remove');
    	$(removeSpan).bind('click', removeCategory);

    	var inputContainer = $(document.createElement('div'))
    	inputContainer.addClass('input-container');

    	var input = $(document.createElement('input'));
    	input.attr('type', 'text')
    	input.val('Название категории');
    	input.addClass('form-control category-input');
    	input.attr('data-id', 'new');
    	$(input).bind('keyup', removeErrorClass);

    	inputContainer.append(input);
    	formContainer.append(moveSpan);
    	formContainer.append(removeSpan);
    	formContainer.append(inputContainer);
    	li.append(formContainer);

    	$('#sortable').append(li);
    });


    // посылает аякс запрос на измененеие категорий
    $('#category-save').bind('click', changeCategories);

    var confirmRemove = false;

    // удаление категории
    $('.category-remove').bind('click', removeCategory);

    // удаление класса ошибки при изменение категории
    $('.category-input').bind('keyup', removeErrorClass);


    /* Редактор рзмеров */
    $( "#size-sortable" ).sortable({
		cursor: "move",
		handle: '.move-size' 
	});

    $( "#size-sortable" ).disableSelection();


    // верстка размеров
    $('#add-size').on('click', function() {
    	var li = $(document.createElement('li'));
    	li.addClass('pull-left');

    	var sizeContainer = $(document.createElement('div'));
    	sizeContainer.addClass('size clearfix');

    	var sizeMetaContainer = $(document.createElement('div'));
    	sizeMetaContainer.addClass('sizes-meta clearfix');

    	var moveSize = $(document.createElement('span'));
    	moveSize.addClass('move-size glyphicon glyphicon-resize-horizontal pull-left');

    	var removeSize = $(document.createElement('span'));
    	removeSize.addClass('remove-size glyphicon glyphicon-remove pull-right');

        removeSize.bind('click', removeSizeHandle);

    	var input = $(document.createElement('input'));
    	input.attr('type', 'text');
    	input.attr('data-id', 'new');
    	input.val('X');
    	input.addClass('size-input');

    	sizeMetaContainer.append(moveSize);
    	sizeMetaContainer.append(removeSize);

    	sizeContainer.append(sizeMetaContainer);
    	sizeContainer.append(input);

    	li.append(sizeContainer);

    	$('#size-sortable').append(li);

    });	

    // посылает аякс запрос на измененеие размеров
    $('#sizes-save').bind('click', changeSizes);

    // удаление категории
    $('.remove-size').bind('click', removeSizeHandle);

    // удаление класса ошибки при изменение категории
    $('.size-input').bind('keyup', function() {
    	$(this).removeClass('error');
    });


    // удаление размера
   function removeSizeHandle() {
    	confirmRemove = confirm('Точно удалить размер?');
 		var elem = $(this);

    	if (confirmRemove) {
    		var sizeId = $(this).closest('.sizes-meta').siblings('.size-input').data('id');

    		if (sizeId == 'new') {
    			elem.closest('li').remove();
    		} else {
    			// заюлокируем кнопку сохрания категорий
    			var button = $('#category-save');
    			button.prop('disabled', true);

    			// посылаем аякс запрос на удаление
    			$.ajax({
		    		url: '/categories-sizes/remove-size',
		    		data: {'_csrf-backend': csrf, id: sizeId},
		    		type: 'POST',
		    		success: function (msg) {
		    			var response = msg;

		    			if (response.response) {
		    				elem.closest('li').remove();
		    				button.prop('disabled', false);
		    			} else {
		    				alert('Ошибка сервера!');
		    				window.location.reload(true);
		    			}
		    		},

		    		error: function (err) {
		    			console.log(err);
		    		}
		    	});

    		}
    	}
    }

    // посылает аякс запрос на измененеие размеров
    function changeSizes () {

    	var button = $(this);

    	button.prop('disabled', true);

    	var forms = $('.size-input');

    	sizesValues = [];


    	for (var i  = 0; i < forms.length; i++) {

    		var form = $(forms[i]);
    		var val = form.val();
    		var id = form.attr('data-id');

    		// если не заполнили категорию
    		if (val.length == 0) {
    			form.addClass('error');
    			$(this).prop('disabled', false);
    			return false;
    		}

    		sizesValues.push({
    			id: id,
    			size: val,
    		})
    	}

    	$.ajax({
    		url: '/categories-sizes/change-sizes',
    		data: {'_csrf-backend': csrf, data: sizesValues},
    		type: 'POST',
    		success: function (msg) {
    			var response = msg;

    			if (response.response) {

    				var changed = $('.size-input[data-id="new"]');
    				var newValue = response.new;

    				for (var i = 0; i < newValue.length; i++) {
    					$(changed[i]).attr('data-id', newValue[i]);
    				}

    				button.prop('disabled', false);

    			} else {
    				alert('Ошибка сервера!');
    				window.location.reload(true);
    			}

    		},

    		error: function (err) {
    			console.log(err);
    		}
    	});

    	button.prop('disabled', false);

    };

    // посылает аякс запрос на измененеие категорий
    function changeCategories () {

    	var button = $(this);

    	button.prop('disabled', true);

    	var forms = $('.category-input');

    	categoriesValues = [];


    	for (var i  = 0; i < forms.length; i++) {

    		var form = $(forms[i]);
    		var val = form.val();
    		var id = form.attr('data-id');

    		// если не заполнили категорию
    		if (val.length == 0) {
    			form.closest('.input-container').addClass('has-error');
    			$(this).prop('disabled', false);
    			return false;
    		}

    		categoriesValues.push({
    			id: id,
    			value: val,
    		})
    	}

    	$.ajax({
    		url: '/categories-sizes/change-categories',
    		data: {'_csrf-backend': csrf, data: categoriesValues},
    		type: 'POST',
    		success: function (msg) {
    			var response = msg;

    			if (response.response) {

    				var changed = $('.category-input[data-id="new"]');
    				var newValue = response.new;

    				for (var i = 0; i < newValue.length; i++) {
    					$(changed[i]).attr('data-id', newValue[i]);
    				}

    				button.prop('disabled', false);

    			} else {
    				alert('Ошибка сервера!');
    				window.location.reload(true);
    			}

    		},

    		error: function (err) {
    			console.log(err);
    		}
    	});

    	button.prop('disabled', false);

    };

    function removeCategory() {
    	confirmRemove = confirm('Точно удалить категорию?');
 		var elem = $(this);

    	if (confirmRemove) {
    		var categoryId = $(this).siblings('.input-container').children('.category-input').data('id');

    		if (categoryId == 'new') {
    			elem.closest('li').remove();
    		} else {
    			// заюлокируем кнопку сохрания категорий
    			var button = $('#category-save');
    			button.prop('disabled', true);

    			// посылаем аякс запрос на удаление
    			$.ajax({
		    		url: '/categories-sizes/remove-category',
		    		data: {'_csrf-backend': csrf, id: categoryId},
		    		type: 'POST',
		    		success: function (msg) {
		    			var response = msg;

		    			if (response.response) {
		    				elem.closest('li').remove();
		    				button.prop('disabled', false);
		    			} else {
		    				alert('Ошибка сервера!');
		    				window.location.reload(true);
		    			}
		    		},

		    		error: function (err) {
		    			console.log(err);
		    		}
		    	});

    		}
    	}
    }

    function removeErrorClass() {
    	var inputContainer = $(this).closest('.input-container'); 
    	$(inputContainer).removeClass('has-error');
    }



});