jQuery(document).ready(function($){

	var _csrf = $('meat[name="csrf-token"]').attr('content');


	// добавление размера принта
	$('#add-print-size').on('click', addPrintSize);

	// сохранение и удаление размера принта
	$('.save-print-size').on('click', savePrintSize);
	$('.remove-print-size').on('click', removePrintSize);

	// очистка ошибок инпутов
	$('.print-size-name').on('keyup', clearSizeInputsError);
	$('.print-size-percent').on('keyup', clearSizeInputsError);


	// доабвление типа
    $('#add-type').on('click', function(event) {
        event.preventDefault();
        var typeContainer = $(document.createElement('div'));
        typeContainer.addClass('print-type');
        typeContainer.attr('data-id', 'new');

        var typeInput = $(document.createElement('input'));
        typeInput.addClass('type-input form-control');
        typeInput.attr('type', 'text');
        typeInput.attr('placeholder', 'Название материала');

        var saveBtn = $(document.createElement('button'));
        saveBtn.addClass('save-type btn btn-success');
        saveBtn.text('Сохранить');

        var removeBtn = $(document.createElement('button'));
        removeBtn.addClass('remove-type btn btn-danger');
        removeBtn.text('Удалить');

        typeContainer.append(typeInput);
        typeContainer.append(saveBtn);
        typeContainer.append(removeBtn);

        typeInput.bind('keyup', clearErrorTypeClass);
        saveBtn.bind('click', saveType);
        removeBtn.bind('click', removeType);

        typeContainer.insertBefore(this);
    });


    // сохранение и удаление типа печати
    $('.save-type').bind('click', saveType);
    $('.remove-type').bind('click', removeType);

    // очистка класса ошибки типа печати
    $('.type-input').bind('keyup', clearErrorTypeClass);

    // добавление услуги
    $('#add-attendance').on('click', function(event) {
    	event.preventDefault();

    	var container = $(document.createElement('div'));
    	container.addClass('print-attendance');
    	container.attr('data-id', 'new');

    	// инпуты
    	var nameInput = $(document.createElement('input'));
    	nameInput.attr('type', 'text');
    	nameInput.attr('placeholder', 'Название услуги');
    	nameInput.addClass('form-control attendance-name attendance-input');

    	var percentInput = $(document.createElement('input'));
    	percentInput.attr('type', 'number');
    	percentInput.attr('min', '1');
    	percentInput.attr('max', '100');
    	percentInput.attr('placeholder', '%');
    	percentInput.addClass('form-control attendance-percent attendance-input');

    	// кнопки
    	var saveBtn = $(document.createElement('button'));
    	saveBtn.addClass('save-attendance btn btn-success');
    	saveBtn.text('Сохранить');

    	var removeBtn = $(document.createElement('button'));
    	removeBtn.addClass('remove-attendance btn btn-danger');
    	removeBtn.text('Удалить');

    	container.append(nameInput);
    	container.append(percentInput);
    	container.append(saveBtn);
    	container.append(removeBtn);

    	// добавим обработчики событий
    	nameInput.bind('keyup', clearErrorTypeClass);
    	percentInput.bind('keyup', clearErrorTypeClass);
    	saveBtn.bind('click', saveAttendance);
    	removeBtn.bind('click', removeAttendance);

    	container.insertBefore(this);
    });

    // сохранение и удаление типа печати
    $('.save-attendance').bind('click', saveAttendance);
    $('.remove-attendance').bind('click', removeAttendance);

    // очистка класса ошибки типа печати
    $('.attendance-input').bind('keyup', clearErrorTypeClass);

	// добавление размера принта
	function addPrintSize(event) {
		event.preventDefault();

		// основной контейнер
		var constructorPrintSize = $(document.createElement('div'));
		constructorPrintSize.addClass('constructor-print-size');
		constructorPrintSize.attr('data-id', 'new');

		// инпуты
		var printSizeName = $(document.createElement('input'));
		printSizeName.addClass('print-size-name');
		printSizeName.attr('placeholder', 'Имя');
		printSizeName.attr('type', 'text');

		var printSizePercent = $(document.createElement('input'));
		printSizePercent.addClass('print-size-percent');
		printSizePercent.attr('placeholder', '%');
		printSizePercent.attr('type', 'number');
		printSizePercent.attr('min', 1);
		printSizePercent.attr('max', 100);

		// контейнер кнопок
		var printSizeButtons = $(document.createElement('div'));
		printSizeButtons.addClass('print-size-buttons clearfix');

		// сами кнопки
		var savePrintBtn = $(document.createElement('span'));
		savePrintBtn.addClass('btn btn-success save-print-size glyphicon glyphicon-ok');
		var removePrintBtn = $(document.createElement('span'));
		removePrintBtn.addClass('btn btn-danger remove-print-size glyphicon glyphicon-remove');

		// добавим кнопки в их контейнер
		printSizeButtons.append(savePrintBtn);
		printSizeButtons.append(removePrintBtn);

		// модалка 
		var printModal = $(document.createElement('div'));
		printModal.addClass('print-size-modal');
		var printModalLoader = $(document.createElement('span'));
		printModalLoader.addClass('glyphicon glyphicon-cog print-size-modal-loader spin-animation');
		printModal.append(printModalLoader);

		// добавим все в контейнер
		constructorPrintSize.append(printSizeName);
		constructorPrintSize.append(printSizePercent);
		constructorPrintSize.append(printSizeButtons);
		constructorPrintSize.append(printModal);

		// добавим обработчкик событий на кнопки
		savePrintBtn.bind('click', savePrintSize);
		removePrintBtn.bind('click', removePrintSize);

		// и на очиску инпутов
		$(printSizeName).on('keyup', clearSizeInputsError);
		$(printSizePercent).on('keyup', clearSizeInputsError);

		// добавлим контейнер перед кнопкой
		$(constructorPrintSize).insertBefore('.add-size-btn-container');
	}


	// сохранение принта
	function savePrintSize() {

		var parent = $(this).closest('.constructor-print-size');

		if (validatePrintSizeInputs(parent)) {

			var modal = $(parent).find('.print-size-modal');
			$(modal).show();

			var name = $(parent).find('.print-size-name').val();
			var percent = $(parent).find('.print-size-percent').val();
			var id = $(parent).attr('data-id');

			$.ajax({
				url: 'constructor-print/save-size',
				type: 'POST',
				data: {'_csrf-backend': _csrf, 'id': id, 'name': name, 'percent': percent},
				success: function (response) {

					if (response['status'] == 'ok') {

						$(parent).attr('data-id', response['id']);
						setTimeout(function() {	
							$(modal).hide();
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
			})

		}
	}

	// удаление принта
	function removePrintSize() {
		if (confirm('Точно удалить?')) {
			var parent = $(this).closest('.constructor-print-size');
			var id = $(parent).attr('data-id');

			if (id == 'new') {
				$(parent).remove();
				return false;
			}

			$(parent).find('.print-size-modal').show();

			$.ajax({
				url: 'constructor-print/remove-size',
				type: 'POST',
				data: {'_csrf-backend': _csrf, 'id': id},
				success: function (response) {

					if (response['status'] == 'ok') {
						setTimeout(function() {
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
			})
		}
	}

	// валидация инпутов
	function validatePrintSizeInputs(parent) {
		var nameInput = $(parent).find('.print-size-name');
		var percentInput = $(parent).find('.print-size-percent');
		var notError = true;

		var nameVal = $(nameInput).val();
		var percentVal = $(percentInput).val()

		if (nameVal.length == 0 || nameVal.length > 10) {
			$(nameInput).addClass('size-input-err');
			notError = false;
		}

		if (isNaN(percentVal) || percentVal < 1 || percentVal > 100) {
			$(percentInput).addClass('size-input-err');
			notError = false;
		}

		return notError;

	}

	// очитска инпутов размера от ошибок
	function clearSizeInputsError() {
		$(this).removeClass('size-input-err');
	}


	// сохранение материала
    function saveType() {
        var parent = $(this).closest('.print-type');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.type-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-type');


        if ($(input).val().length == 0) {
            $(input).addClass('error-type');
            return false;
        }

        $(input).prop('disabled', true);
        $(saveBtn).prop('disabled', true);
        $(removeBtn).prop('disabled', true);

        $.ajax({
            url: '/constructor-print/save-type',
            data: {'_csrf-backend': _csrf, id: id, name: $(input).val()},
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
                    console.log(response);
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
    function removeType() {
        var parent = $(this).closest('.print-type');
        var id = $(parent).attr('data-id');
        var input = $(parent).find('.type-input');
        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-type');

        if (confirm('Точно удалить размер?')) {
            if (id == 'new') {
                $(parent).remove();
                return false;
            }

            $(input).prop('disabled', true);
            $(saveBtn).prop('disabled', true);
            $(removeBtn).prop('disabled', true);

            $.ajax({
               url: '/constructor-print/remove-type',
                data: {'_csrf-backend': _csrf, id: id},
                type: 'POST',
                success: function (response) {
                    if (response['status'] == 'ok') {
                        setTimeout(function(){
                            $(parent).remove();
                        }, 500);
                    } else {
                        console.log(response);
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
    function clearErrorTypeClass() {
        $(this).removeClass('error-type');
    }

    // сохранение услуги
    function saveAttendance () {
    	var parent = $(this).closest('.print-attendance');
        var id = $(parent).attr('data-id');

        var nameInput = $(parent).find('.attendance-name');
        var percentInput = $(parent).find('.attendance-percent');

        var nameInputVal = $(nameInput).val();
        var percentInputVal = parseInt($(percentInput).val());

        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-attendance');


        if (nameInputVal.length == 0) {
            $(nameInput).addClass('error-type');
            return false;
        } else if (isNaN(percentInputVal) || percentInputVal < 1 || percentInputVal > 100) {
        	$(percentInput).addClass('error-type');
            return false;
        }

        $(nameInput).prop('disabled', true);
        $(percentInput).prop('disabled', true);
        $(saveBtn).prop('disabled', true);
        $(removeBtn).prop('disabled', true);

        $.ajax({
            url: '/constructor-print/save-attendance',
            data: {'_csrf-backend': _csrf, id: id, name: nameInputVal, percent: percentInputVal},
            type: 'POST',
            success: function (response) {
                if (response['status'] == 'ok') {
                    $(parent).attr('data-id', response['id']);
                    setTimeout(function(){
                        $(nameInput).prop('disabled', false);
                        $(percentInput).prop('disabled', false);
                        $(saveBtn).prop('disabled', false);
                        $(removeBtn).prop('disabled', false);
                    }, 500);
                } else {
                    console.log(response);
                    alert('Произошла ошибка, обновите страницу');
                }
            },
            error: function (err) {
                console.log(err);
                alert('Произошла ошибка, обновите страницу');
            }
        });
    }


    // сохранение услуги
    function removeAttendance () {
    	var parent = $(this).closest('.print-attendance');
        var id = $(parent).attr('data-id');

        var nameInput = $(parent).find('.attendance-name');
        var percentInput = $(parent).find('.attendance-percent');

        var saveBtn = this;
        var removeBtn = $(parent).find('.remove-type');

        if (confirm('Точно удалить размер?')) {
            if (id == 'new') {
                $(parent).remove();
                return false;
            }

            $(nameInput).prop('disabled', true);
            $(percentInput).prop('disabled', true);
            $(saveBtn).prop('disabled', true);
            $(removeBtn).prop('disabled', true);

            $.ajax({
               url: '/constructor-print/remove-attendance',
                data: {'_csrf-backend': _csrf, id: id},
                type: 'POST',
                success: function (response) {
                    if (response['status'] == 'ok') {
                        setTimeout(function(){
                            $(parent).remove();
                        }, 500);
                    } else {
                        console.log(response);
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


});