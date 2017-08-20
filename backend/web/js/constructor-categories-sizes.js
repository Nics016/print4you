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


    // посылает аякс запрос на измененеие категорий
    $('#category-save').bind('click', changeCategories);

    var confirmRemove = false;

    // удаление категории
    $('.category-remove').bind('click', removeCategory);


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


    // доабвление материала
    $('#add-material').on('click', function(event) {
        event.preventDefault();
        var materialContainer = $(document.createElement('div'));
        materialContainer.addClass('product-material');
        materialContainer.attr('data-id', 'new');

        var materialInput = $(document.createElement('input'));
        materialInput.addClass('material-input form-control');
        materialInput.attr('type', 'text');
        materialInput.attr('placeholder', 'Название материала');

        var saveBtn = $(document.createElement('button'));
        saveBtn.addClass('save-material btn btn-success');
        saveBtn.text('Сохранить');

        var removeBtn = $(document.createElement('button'));
        removeBtn.addClass('remove-material btn btn-danger');
        removeBtn.text('Удалить');

        materialContainer.append(materialInput);
        materialContainer.append(saveBtn);
        materialContainer.append(removeBtn);

        materialInput.bind('keyup', clearErrorMaterialClass);
        saveBtn.bind('click', saveMaterial);
        removeBtn.bind('click', removeMaterial);

        materialContainer.insertBefore(this);
    });

    // доабвление стороеы
    $('#add-side').on('click', function(event) {
        event.preventDefault();
        var sideContainer = $(document.createElement('div'));
        sideContainer.addClass('product-side');
        sideContainer.attr('data-id', 'new');

        var sideInput = $(document.createElement('input'));
        sideInput.addClass('side-input form-control');
        sideInput.attr('type', 'text');
        sideInput.attr('placeholder', 'Название стороны');

        var saveBtn = $(document.createElement('button'));
        saveBtn.addClass('save-side btn btn-success');
        saveBtn.text('Сохранить');

        var removeBtn = $(document.createElement('button'));
        removeBtn.addClass('remove-side btn btn-danger');
        removeBtn.text('Удалить');

        sideContainer.append(sideInput);
        sideContainer.append(saveBtn);
        sideContainer.append(removeBtn);

        sideInput.bind('keyup', clearErrorSideClass);
        saveBtn.bind('click', saveSide);
        removeBtn.bind('click', removeSide);

        sideContainer.insertBefore(this);
    });

    // сохранение и удаление материала
    $('.save-material').bind('click', saveMaterial);
    $('.remove-material').bind('click', removeMaterial);

    // очистка класса ошибки материала
    $('.material-input').bind('keyup', clearErrorMaterialClass);

    // охранение и удлаение стороеы
    $('.save-side').bind('click', saveSide);
    $('.remove-side').bind('click', removeSide);

    // очистка класса ошибки стороны
    $('.material-input').bind('keyup', clearErrorSideClass);

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
		    		url: '/constructor-categories-sizes/remove-size',
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
    		url: '/constructor-categories-sizes/change-sizes',
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
    		})
    	}

    	$.ajax({
    		url: '/constructor-categories-sizes/change-categories',
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
		    		url: '/constructor-categories-sizes/remove-category',
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


    // сохранение материала
    function saveMaterial() {
        var parent = $(this).closest('.product-material');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.material-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-material');


        if ($(input).val().length == 0) {
            $(input).addClass('error-material');
            return false;
        }

        $(input).prop('disabled', true);
        $(saveBtn).prop('disabled', true);
        $(removeBtn).prop('disabled', true);

        $.ajax({
            url: '/constructor-categories-sizes/save-material',
            data: {'_csrf-backend': csrf, id: id, name: $(input).val()},
            type: 'POST',
            success: function (response) {
                if (response['status'] == 'ok') {
                    $(parent).attr('data-id', response['id']);
                    setTimeout(function(){
                        $(input).prop('disabled', false);
                        $(saveBtn).prop('disabled', false);
                        $(removeBtn).prop('disabled', false);
                    }, 500);
                } else {
                    console.log(err);
                    alert('Произошла ошибка, обновите страницу');
                }
            },
            error: function (err) {
                console.log(err);
                alert('Произошла ошибка, обновите страницу');
            }
        });

    }

    // удаление материала
    function removeMaterial() {
        var parent = $(this).closest('.product-material');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.material-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-material');

        if (confirm('Точно удалить размер?')) {
            if (id == 'new') {
                $(parent).remove();
                return false;
            }

            $(input).prop('disabled', true);
            $(saveBtn).prop('disabled', true);
            $(removeBtn).prop('disabled', true);

            $.ajax({
               url: '/constructor-categories-sizes/remove-material',
                data: {'_csrf-backend': csrf, id: id},
                type: 'POST',
                success: function (response) {
                    if (response['status'] == 'ok') {
                        setTimeout(function(){
                            $(parent).remove();
                        }, 500);
                    } else {
                        console.log(err);
                        alert('Произошла ошибка, обновите страницу');
                    }
                },
                error: function (err) {
                    console.log(err);
                    alert('Произошла ошибка, обновите страницу');
                } 
            });
        }

        
    }   

    // стирание класса ошибки у материала
    function clearErrorMaterialClass() {
        $(this).removeClass('error-material');
    }


    // сохранение стороны
    function saveSide() {
        var parent = $(this).closest('.product-side');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.side-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-side');


        if ($(input).val().length == 0) {
            $(input).addClass('error-side');
            return false;
        }

        $(input).prop('disabled', true);
        $(saveBtn).prop('disabled', true);
        $(removeBtn).prop('disabled', true);

        $.ajax({
            url: '/constructor-categories-sizes/save-side',
            data: {'_csrf-backend': csrf, id: id, name: $(input).val()},
            type: 'POST',
            success: function (response) {
                if (response['status'] == 'ok') {
                    $(parent).attr('data-id', response['id']);
                    setTimeout(function(){
                        $(input).prop('disabled', false);
                        $(saveBtn).prop('disabled', false);
                        $(removeBtn).prop('disabled', false);
                    }, 500);
                } else {
                    console.log(err);
                    alert('Произошла ошибка, обновите страницу');
                }
            },
            error: function (err) {
                console.log(err);
                alert('Произошла ошибка, обновите страницу');
            }
        });

    }

    // удаление материала
    function removeSide() {
        var parent = $(this).closest('.product-side');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.side-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-side');

        if (confirm('Точно удалить размер?')) {
            if (id == 'new') {
                $(parent).remove();
                return false;
            }

            $(input).prop('disabled', true);
            $(saveBtn).prop('disabled', true);
            $(removeBtn).prop('disabled', true);

            $.ajax({
               url: '/constructor-categories-sizes/remove-side',
                data: {'_csrf-backend': csrf, id: id},
                type: 'POST',
                success: function (response) {
                    if (response['status'] == 'ok') {
                        setTimeout(function(){
                            $(parent).remove();
                        }, 500);
                    } else {
                        console.log(err);
                        alert('Произошла ошибка, обновите страницу');
                    }
                },
                error: function (err) {
                    console.log(err);
                    alert('Произошла ошибка, обновите страницу');
                } 
            });
        }

        
    }   

    // стирание класса ошибки у стооеы
    function clearErrorSideClass() {
        $(this).removeClass('error-side');
    }

});