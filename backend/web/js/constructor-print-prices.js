jQuery(document).ready(function($){

    window.hasGrossError = false;

    var confirmRemove = false;
    var grossObjects = [];

    $('#constructor-prices-form').on('beforeSubmit', function (e) {
        grossObjects = [];

        var grossPriceObjetcs = $('.gross-price');

        // если не добавили ни 1 цены
        if (grossPriceObjetcs.length == 0) {
            $('#gross-price-label').addClass('label-error');
            scrollToElem('gross-price-label');
            return false;
        }

        // првоерим данные и занесем
        $('.gross-price').each(function (index, elem){
            var minInput = $(elem).find('.gross-min');
            var maxInput = $(elem).find('.gross-max');
            var valueInput = $(elem).find('.gross-value');

            minInput.change();
            maxInput.change();
            valueInput.change();

            grossObjects.push({
                from: minInput.val(),
                to: maxInput.val(),
                price: valueInput.val(),
            });

        });

        // если была ошибка
        if (window.hasGrossError) {
            scrollToElem('gross-price-label');
            return false;
        } 

        // занесем в инпут все значения
        $('#gross-price').val(JSON.stringify(grossObjects));
        return true;

    });

    $('.gross-min').bind('change', checkGrossNumber);
    $('.gross-max').bind('change', checkGrossNumber);
    $('.gross-value').bind('change', checkGrossNumber);

    $('.remove-gross-price').bind('click', removeGrossPrice);


    // добавление цены
    $('#add-gross-price').on('click', function(event) {
        event.preventDefault();

        // основной контейнер
        var grossPrice = $(document.createElement('div'));
        grossPrice.addClass('gross-price');

        // верзний контейнер (для инпутов от и до)
        var grossTop = $(document.createElement('div'));
        grossTop.addClass('gross-top');

        // инпут от
        var grossMin = $(document.createElement('input'));
        grossMin.addClass('gross-min');
        grossMin.attr('type', 'number');
        grossMin.attr('min', '1');
        grossMin.attr('placeholder', 'От');

        // инпут до
        var grossMax = $(document.createElement('input'));
        grossMax.addClass('gross-max');
        grossMax.attr('type', 'number');
        grossMax.attr('min', '1');
        grossMax.attr('placeholder', 'До');

        grossTop.append(grossMin);
        grossTop.append(grossMax);

        // нижний контейнер, для инпута цены и кнопки удаления
        var grossBottom = $(document.createElement('div'));
        grossBottom.addClass('gross-bottom');

        // инпут цены
        var grossValue = $(document.createElement('input'));
        grossValue.addClass('gross-value');
        grossValue.attr('type', 'number');
        grossValue.attr('min', '1');
        grossValue.attr('placeholder', 'Цена');

        // кнопка удаления
        var removeBtn = $(document.createElement('button'));
        removeBtn.addClass('btn btn-danger remove-gross-price');
        removeBtn.text('Удалить');

        grossBottom.append(grossValue);
        grossBottom.append(removeBtn);

        grossPrice.append(grossTop);
        grossPrice.append(grossBottom);


        // надожим обработчики событий
        $(grossMin).bind('change', checkGrossNumber);
        $(grossMax).bind('change', checkGrossNumber);
        $(grossValue).bind('change', checkGrossNumber);
        $(removeBtn).bind('click', removeGrossPrice);

        $(grossPrice).insertBefore('.add-btn-container');
        $('#gross-price-label').removeClass('label-error');
    });



    // удаление элемента
    function removeGrossPrice(event) {
        event.preventDefault();
        confirmRemove = false;
        if (confirm('Точно удалить цену?')) {
            var parent = $($(this).closest('.gross-price'));
            parent.find('.gross-min').unbind('change');
            parent.find('.gross-max').unbind('change');
            parent.find('.gross-value').unbind('change');
            parent.find('.remove-gross-price').unbind('click');
            parent.remove();
        }
    }

    // проверка значения
    function checkGrossNumber() {
        var elem = $(this);
        var val = parseInt(elem.val());

        if (isNaN(val) || val < 1) {
            elem.addClass('input-err');
            window.hasGrossError = true;
        } else {
            elem.removeClass('input-err');
            window.hasGrossError = false;
        }

    }

    function scrollToElem(id) {
        $('html, body').animate({
            scrollTop: $("#" + id).offset().top
        }, 500);
    }

});