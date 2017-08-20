(function() {

	window.onload = function() {

        var setProduct = document.getElementById('set_product').value;
        var setCat = document.getElementById('set_cat').value;

        var printSizes = JSON.parse(document.getElementById('print-sizes').value);
        var currentPrintSize = false; // сюда будем записывать размер принта стороны

        var csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var products = false;
        var currentProduct = false, 
            currentProductColorId = false, 
            currentProductSize = false, 
            currentProductPrice = false,
            currentProductSide = false;
            currentImagesCount = 0;

        // элементы конструктора
        var constructorTextArea = document.getElementById('constructor-text');
        var constructorTextColor = document.getElementById('text-color');
        var constructorTextFontFamily = document.getElementById('constructor-text-font-family');

        var constructorFrontSideBtn = document.getElementById('front-side');
        var constructorBackSideBtn = document.getElementById('back-side');

        var constructorProductColorValueElem = document.querySelector('.constructor-product-color-value');
        var productFontSideImageElem = document.querySelector('#front-side .product-side-image');
        var productBackSideImageElem = document.querySelector('#back-side .product-side-image');
        var colorSizesContainer = document.querySelector('.constructor-product-sizes');
        var additionalSidesContainer = document.querySelector('.additional-sides');


        var canvasBgImage = document.getElementById('canvas-bg-image'); 

        // хранилище объектов для основных стороны
        var constructorObjects = {
            front: false,
            back: false,
        };

        // хранилище объектов для дополнительных сторон
        var constructorAdditionalObjects = {};

        // для отправки данных на сервер
        var frontData = {};
        var backData = {};
        var additionalData = [];

        var canvas = new fabric.Canvas('constructor-canvas', {
            containerClass: 'canvas-container',
        });

        var canvasContainer = document.querySelector('.canvas-container');

        // для добавления в корзину
        var cloneCanvas = document.createElement('canvas');
        cloneCanvas = new fabric.Canvas(cloneCanvas, {
            width: canvas.getWidth(), 
            height:canvas.getHeight()
        });


        var tabsBtns = document.getElementsByClassName('constructor-leftbar-toogle');

        for(var i = 0; i < tabsBtns.length; i++) {
            var tabBtn = tabsBtns[i];
            tabBtn.onclick = chageTab;
        }

        document.getElementById('canvas-wrap').onclick = disableText;
        canvas.on('object:selected', disableText);

        // добавление текста из правой кнопки
        document.getElementById("to-text").onclick = toText;

        // добавление нового текстового слоя
        document.getElementById("add-text").onclick = addText;

        // измененине текста
        constructorTextArea.onkeyup = changeConstructorTextValue;

        // изменение цвета текста 
        constructorTextColor.onchange = changeConstructorTextColor;

        //изменение шрифта текста
        constructorTextFontFamily.onchange = changeConstructorFontFamily;

        //удаление слоя
        document.getElementById('delete-layer').onclick = deleteLayer;

        // выравнивание слоя
        document.getElementById('x-align-layer').onclick = horisontalAlignLayer;
        document.getElementById('y-align-layer').onclick = verticalAlignLayer;

        // клик до фото
        document.getElementById('add-image').onclick = openFileInput;
        document.getElementById('to-image').onclick = toImageTab;

        // загрузка фото
        document.getElementById('fileupload').onchange = uploadFile;

        //добавление в корзину 
        document.getElementById('add-cart').onclick = addToCart;
        
        // клик на фронт
        constructorFrontSideBtn.onclick = showFrontSide;

        // клик на бэк
        constructorBackSideBtn.onclick = showBackSide;

        // закрыть модалку успешного заказа
        var closeSuccessModalBtns = document.querySelectorAll('[data-action="close-success-modal"]');

        for (var i = 0; i < closeSuccessModalBtns.length; i++) 
            closeSuccessModalBtns[i].onclick = hideSuccessModal;


        constructorInit();

        // дизейблит инпуты
        function disableText() {

            var layerControls = document.getElementsByClassName('canvas-control');
            var activeObj = canvas.getActiveObject();

            if (activeObj == null) {
                constructorTextArea.disabled = true;
                constructorTextColor.disabled = true; 
                constructorTextFontFamily.disabled = true; 
                for (var i = 0; i < layerControls.length; i++)
                    layerControls[i].disabled = true;

            } else if (activeObj.get('type') == 'text') {
                constructorTextArea.disabled = false;
                constructorTextColor.disabled = false;
                constructorTextFontFamily.disabled = false;
                for (var i = 0; i < layerControls.length; i++)
                    layerControls[i].disabled = false;
                
            } else {
                constructorTextArea.disabled = true;
                constructorTextColor.disabled = true; 
                constructorTextFontFamily.disabled = true; 
                for (var i = 0; i < layerControls.length; i++)
                    layerControls[i].disabled = false;
            }
            

        }

        // показ передней стороны
        function showFrontSide() {
            if (currentProductSide != 'front' || currentProductSide === false) {

                // найдем текущий цвет
                var colors = currentProduct['constructorColors'];

                for (var i = 0; i < colors.length; i++) {
                    if (colors[i]['id'] == currentProductColorId) {
                        canvasBgImage.src = colors[i]['full_front_image'];
                        break;
                    }
                }

                var json = canvas.toJSON();

                if (currentProductSide == 'back')
                    constructorObjects[currentProductSide] = json;
                else if (currentProductSide !== false)
                    constructorAdditionalObjects[currentProductSide] = json;

                canvas.clear();

                currentImagesCount = 0;
                
                if (constructorObjects.front !== false) {
                    canvas.loadFromJSON(constructorObjects.front, canvas.renderAll.bind(canvas));
                    
                    for (var i = 0; i < constructorObjects.front.objects.length; i++) {
                        var obj = constructorObjects.front.objects[i];
                        if (obj['type'] == 'image') currentImagesCount++;
                    }
                }
                
                removeClass(document.querySelector('.constructor-product-side.current'), 'current');

                currentProductSide = 'front';
                this.className += ' current';

                disableText();
            }
            
        }


        // показ задней стороны
        function showBackSide() {

            if (currentProductSide != 'back' || currentProductSide === false) {
                // найдем текущий цвет
                var colors = currentProduct['constructorColors'];

                for (var i = 0; i < colors.length; i++) {
                    if (colors[i]['id'] == currentProductColorId) {
                        canvasBgImage.src = colors[i]['full_back_image'];
                        break;
                    }
                }

                var json = canvas.toJSON();

                if (currentProductSide == 'front') 
                    constructorObjects[currentProductSide] = json;
                else if (currentProductSide !== false)
                    constructorAdditionalObjects[currentProductSide] = json;

                canvas.clear();

                currentImagesCount = 0;

                if (constructorObjects.back !== false) {
                    canvas.loadFromJSON(constructorObjects.back, canvas.renderAll.bind(canvas));
                    for (var i = 0; i < constructorObjects.back.objects.length; i++) {
                        var obj = constructorObjects.back.objects[i];
                        if (obj['type'] == 'image') currentImagesCount++;
                    }
                } 
                
                removeClass(document.querySelector('.constructor-product-side.current'), 'current');

                currentProductSide = 'back';
                this.className += ' current';

                disableText();    
            }        
        }

        // показывает дополнительные стороны
        function showAdditionalSide() {
            var sideId = this.getAttribute('data-side-id');
            if (currentProductSide != sideId || currentProductSide === false) {

                var fullImg = this.getAttribute('data-full-img');
                canvasBgImage.src = fullImg;

                var json = canvas.toJSON();

                // если основные стороны, то сохраним их
                if (currentProductSide == 'front' || currentProductSide == 'back') 
                    constructorObjects[currentProductSide] = json;
                else if (currentProductSide !== false)
                    constructorAdditionalObjects[currentProductSide] = json;

                canvas.clear();

                currentImagesCount = 0;

                if (typeof constructorAdditionalObjects[sideId] !== 'undefined') {
                    canvas.loadFromJSON(constructorAdditionalObjects[sideId], canvas.renderAll.bind(canvas));
                    
                    var objects = constructorAdditionalObjects[sideId].objects;

                    for (var i = 0; i < objects.length; i++) {
                        if (objects[i]['type'] == 'image') currentImagesCount++;
                    }
                }


                removeClass(document.querySelector('.constructor-product-side.current'), 'current');
                currentProductSide = sideId;
                this.className += ' current';

            }
        }

        // ошибки констурктор
        function addConstructorError(err) {
            var elem = document.getElementById('constructor-error');
            elem.textContent = err;
            elem.style.visibility = 'visible';

            setTimeout(function () {
                elem.style.visibility = 'hidden';
            }, 5000);
        }

        // загрузка фалйа
        function uploadFile(event) {

            if (currentImagesCount >= 3) {
                addConstructorError('Лимит фото превышен!');
                this.value = '';
                return false;
            }

            var file = event.target.files[0];
            var ext = false;
            var parts = file.name.split('.');
            if (parts.length > 1) ext = parts.pop();

            if (ext != 'jpg' && ext != 'png') {
                addConstructorError('Расширение файла не поддерживается');
                return false;
            }

            if (file.size / 1024 / 1024 > 2) {
                addConstructorError('Большой размер файла');
                return false;
            }
        
            var reader = new FileReader();


            reader.onload = function (event){
                var imgObj = new Image();
                imgObj.src = event.target.result;

                imgObj.onload = function () {
                    var image = new fabric.Image(imgObj);
                    var proportions = imgObj.naturalWidth / imgObj.naturalHeight;

                    image.set({
                        angle: 0,
                        padding: 10,
                        cornersize: 10,
                        height: 110 / proportions,
                        width: 110,
                    });

                    canvas.add(image);
                    currentImagesCount++;

                    // центрирование
                    image.center();
                    image.setCoords();

                    canvas.setActiveObject(image);

                }

            }

            reader.readAsDataURL(file);

            this.value = '';
        }

        // открытие input file
        function openFileInput() {
            
            var elem = document.getElementById('fileupload');

            if(elem && document.createEvent) {
                var evt = document.createEvent("MouseEvents");
                evt.initEvent("click", true, false);
                elem.dispatchEvent(evt);
            }

        }

        // клик до фото 
        function toImageTab() {
            var elem = document.querySelector('.constructor-leftbar-toogle[data-toggle="image-tab"]');
            var event = new Event('click');
            elem.dispatchEvent(event);
            document.getElementById('add-image').dispatchEvent(event);
        }

        // выравнивание слоя
        function horisontalAlignLayer() {
            var current = canvas.getActiveObject();
            if (typeof current != 'undefined') {
                current.centerH();
                current.setCoords();
            }
        }

        function verticalAlignLayer() {
            var current = canvas.getActiveObject();
            if (typeof current != 'undefined') {
                current.centerV();
                current.setCoords();
            }
        }

        // удаление слоя 
        function deleteLayer() {
            var current = canvas.getActiveObject();
            if (typeof current != 'undefined') {

                if (current['type'] == 'image')
                    currentImagesCount--;

                current.remove();
                disableText();
            }
        }

        // изменение шрифта текста
        function changeConstructorFontFamily() {
            var current = canvas.getActiveObject();

            if (current.get('type') == 'text' && typeof current != 'undefined') {
                current.fontFamily = constructorTextFontFamily.value;
                canvas.renderAll();
            }
        }

        // изменение цвета текста
        function changeConstructorTextColor() {
            var current = canvas.getActiveObject();

            if (current.get('type') == 'text' && typeof current != 'undefined') {
                current.setColor(constructorTextColor.value);
                canvas.renderAll();
            }
        }

        // изменение текста
        function changeConstructorTextValue() {
            var current = canvas.getActiveObject();

            if (current.get('type') == 'text' && typeof current != 'undefined') {
                current.setText(constructorTextArea.value);
                canvas.renderAll();
            }
        }

         // добавление текста
        function addText() {

            // дефолтные настройки
            constructorTextArea.value = 'Текст';
            constructorTextColor.value = "#000000";

            var text = new fabric.Text(constructorTextArea.value, {
                fill: constructorTextColor.value,
                fontSize: 18,
                fontFamily: constructorTextFontFamily.value,
            });

            canvas.add(text);
            
            canvas.setActiveObject(text);

            // центрирование
            text.center();
            text.setCoords();

        }

        // добавление текста из правой кнопки
        function toText() {
            var elem = document.querySelector('.constructor-leftbar-toogle[data-toggle="text-tab"]');
            var event = new Event('click');
            elem.dispatchEvent(event);
        }

        // инициализация констурктора
        function constructorInit () {
            showLoader('Загружаем товары...');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/constructor/get-products/', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(csrfParam + '='+ csrfToken);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    products = JSON.parse(xhr.responseText);
                    renderProducts();
                }
            }
        }

        // рендер продуктов, пришедштх с сервера
        function renderProducts() {

            var hasCat = false;

            //изменим селект
            var select = document.getElementById('constructor-leftbar-select');
            for (var i = 0; i < products.length; i++) {
                var opt = document.createElement('option');

                // если есть категория, которую получили из get парметров
                if (!hasCat && products[i]['id'] == setCat)
                    hasCat = true;

                opt.value = products[i]['id'];
                opt.innerHTML = products[i]['name'];
                select.appendChild(opt);
            }

            renderCurrentCat('all');

            select.addEventListener('change', function() {
                renderCurrentCat(this.value);
            });

            if (hasCat) {
                // если есть категория из гет параметров, то кликнем на нее 
                select.value = setCat;
                select.dispatchEvent(new Event('change')); 

            } 

           // вызовим клик на первого ребенка или на которого нам задвали параметром
            var product = 
                document.querySelector('.leftarea-product-image-container[data-id="' + setProduct +'"]');

            if (!product)
                product = document.querySelector('.leftarea-product-image-container');

            if (product) product.dispatchEvent(new Event('click')); 
           
            

            setTimeout(hideLoader, 500)
        }

        // рендер продуктов категории
        function renderCurrentCat(catId) {

            // очистим все дочерние элементы
            var productsContainer = document.getElementById('products-list');
            while (productsContainer.firstChild) {
                productsContainer.removeChild(productsContainer.firstChild);
            }

            if (catId == 'all') {

                // ренедер всех категорий
                for (var i = 0; i < products.length; i++) 
                    renderCurrentCatHelp(i, productsContainer);

                document.getElementById('category-title').innerHTML = '';
                document.getElementById('category-info').innerHTML = '';

            } else {

                // рендер отдельной категории
                for (var i = 0; i < products.length; i++) {
                    if (catId == products[i]['id']) {
                        renderCurrentCatHelp(i, productsContainer);
                        document.getElementById('category-title').innerHTML = products[i]['name'];
                        document.getElementById('category-info').innerHTML = products[i]['description'];
                        break;
                    }
                }
            }

            // удалим обработчик событий на клик и поставим заново
            var productImageContainers = document.querySelectorAll('.leftarea-product-image-container');

            for (var i = 0; i < productImageContainers.length; i++) {
                productImageContainers[i].removeEventListener('click', productClickHandler);
                productImageContainers[i].addEventListener('click', productClickHandler);
            }

        }

        // чтобы не дублировать код
        function renderCurrentCatHelp(i, productsContainer) {

            // добавим все из массива продуктов
            var productList = products[i]['products'];
            for (var x = 0; x < productList.length; x++) {
                var div = document.createElement('div');
                var span = document.createElement('span');
                var image = new Image();

                div.className = 'leftarea-product-image-container';
                div.dataset.id = productList[x]['id'];
                div.dataset.catId = products[i]['id'];

                image.src = productList[x]['image'];
                image.alt = productList[x]['img_alt'] == null ? '' : productList[x]['img_alt'];
                image.className = 'leftarea-product-image';

                span.className = 'left-area-product-name';
                span.textContent = productList[x]['name']

                div.appendChild(image);
                div.appendChild(span);

                productsContainer.appendChild(div);
                
            }
        }

        // обработчик на клик продукта
        function productClickHandler() {
            var id = this.dataset.id;
            var catId = this.dataset.catId;
            if (currentProduct !== false) {
                // зарендерим продукт и присвоим класс
                if (typeof id !== 'undefined' && typeof catId !== 'undefined'
                    && currentProduct['id'] != id && renderProductData(id, catId)
                    ) 
                    this.className += ' current';
            } else {
                if (typeof id !== 'undefined' && typeof catId !== 'undefined' 
                    && renderProductData(id, catId)
                    ) 
                    this.className += ' current';
            }
            
        }


        // рендер продукта в конструктор
        function renderProductData(id, catId) {

            for (var i = 0; i < products.length; i++) {

                if (products[i]['id'] == catId) {
                    var productsList = products[i]['products'];
                    for (var x = 0; x < productsList.length; x++) {
                        if (id == productsList[x]['id']) {

                            currentProduct = productsList[x];

                            // отобразим все данные
                            renderProductMeta();
                            // изменим размер канваса
                            changeCanvasSize();

                            // удалим класс текущего элемента с другого продукта
                            var current = document.querySelector('.leftarea-product-image-container.current');
                            removeClass(current, 'current');

                            return true;
                        }
                    }
                }

            }

            return false;

        }

        // рендерит цену продукта, и все цвета
        function renderProductMeta() {
            var colorsContainer = document.querySelector('.constructor-product-colors');

            // удалим старые цвета
            while (colorsContainer.firstChild) {
                colorsContainer.removeChild(colorsContainer.firstChild);
            }

            var colors = currentProduct['constructorColors'];

            for (var i = 0; i < colors.length; i++) {
                var div = document.createElement('div');
                div.className = 'constructor-product-color';
                div.style.backgroundColor = colors[i]['color_value'];
                div.dataset.id = colors[i]['id'];
                colorsContainer.appendChild(div);
            }


            // удалим и добавим обработчик событий на клик по цвету
            var colorsList = document.querySelectorAll('.constructor-product-color');
            for (var i = 0; i < colorsList.length; i++) {
                colorsList[i].removeEventListener('click', colorClickHandler);
                colorsList[i].addEventListener('click', colorClickHandler);
            }

            // кликнем по первому цвету
            colorsList[0].dispatchEvent(new Event('click'));

        }

        // меняем цвет продукта (логика)
        function colorClickHandler() {
            var id = this.dataset.id;
            if (currentProductColorId !== false) {
                if (typeof id !== 'undefined' && id !== currentProductColorId && changeProductColor(id)) 
                    this.className += ' current-color';
            } else {
                if (typeof id !== 'undefined' && changeProductColor(id)) 
                    this.className += ' current-color';

            }
        }

        // непосредственно меняем цвет продукта
        function changeProductColor(id) {
            var colors = currentProduct['constructorColors'];
            for (var i = 0; i < colors.length; i++) {
                if (colors[i]['id'] == id) {

                    var color = colors[i];

                    // установим цену
                    currentPrice = color['price'];
                    changeProductPrice();

                    constructorProductColorValueElem.textContent = color['name'];

                    // изменим стороны цвета
                    changeColorSides(color);

                    var current = document.querySelector('.constructor-product-color.current-color');
                    removeClass(current, 'current-color');

                    currentProductColorId = id;

                    // очистим все размеры
                    while (colorSizesContainer.firstChild) {
                        colorSizesContainer.removeChild(colorSizesContainer.firstChild);
                    }

                    // добавим все размеры
                    for (var i = 0; i < color['constructorSizes'].length; i++) {
                        var span = document.createElement('span');
                        span.className = 'constructor-product-size';
                        span.textContent = color['constructorSizes'][i]['size'];
                        span.dataset.id = color['constructorSizes'][i]['id'];
                        colorSizesContainer.appendChild(span);
                    }

                    // удалим и добавим обработчик событий на клик по размеру
                    var sizesList = document.querySelectorAll('.constructor-product-size');
                    for (var i = 0; i < sizesList.length; i++) {
                        sizesList[i].removeEventListener('click', sizeClickHandler);
                        sizesList[i].addEventListener('click', sizeClickHandler);
                    }
                    

                    sizesList[0].dispatchEvent(new Event('click'));

                    // если неизвестная текущая сторона, то кликнем по фронту
                    if (currentProductSide === false) {
                        constructorFrontSideBtn.dispatchEvent(new Event('click'));
                        currentProductSide = 'front';
                        return true;
                    }


                    // если текущая сторона фронт или бэк, то просто изменим фоновую картинку в канвасе
                    if (currentProductSide == 'front') {
                        canvasBgImage.src = color['full_front_image'];
                        return true;
                    }
                    if (currentProductSide == 'back') {
                        canvasBgImage.src = color['full_back_image'];
                        return true;
                    }

                    /* 
                        если текущая сторона одна из дополнительных, то проерми ее наличие в текущем цвете
                        и изменим фоновую картинку и добавим класс текущей стороны
                        если ее нет, то клинем по фронту
                    */
                    if (currentProductSide != 'front' && currentProductSide != 'back') {
                        var selector = '.constructor-product-side[data-side-id="' + currentProductSide +'"]';
                        var sideElem = document.querySelector(selector);

                        if (sideElem) {
                            canvasBgImage.src = sideElem.getAttribute('data-full-img');
                            sideElem.className += ' current';
                        } else {
                            currentProductSide = false;
                            constructorFrontSideBtn.dispatchEvent(new Event('click'));
                        }

                        return true;
                    }
                    
                }
            }

            return false;
        }


        // изменяет стороны текущего цвета товара
        function changeColorSides(color) {

            // изменим фронт и бэк стороны
            productFontSideImageElem.src = color['small_front_image'];
            productBackSideImageElem.src = color['small_back_image'];

            // сделаем дополнительные стороны

            // удалим старые обработчкик событий
            var oldSides = additionalSidesContainer.getElementsByClassName('constructor-product-side');

            for (var i = 0; i < oldSides.length; i++)
                oldSides[i].removeEventListener('click', showAdditionalSide);


            additionalSidesContainer.innerHTML = '';
            var tmpCanvasObjects = {};

            for (var i = 0; i < color['constructorSides'].length; i++) {
                var side = color['constructorSides'][i];

                // проверим, есть ли подходящие объекты в канвасе

                if (typeof constructorAdditionalObjects[side['side_id']] != 'undefined') {
                    tmpCanvasObjects[side['side_id']] = constructorAdditionalObjects[side['side_id']];
                }
                

                var mainContainer = document.createElement('div');
                mainContainer.className = 'constructor-product-side';
                mainContainer.setAttribute('data-full-img', side['full_image']);
                mainContainer.setAttribute('data-side-id', side['side_id']);

                var title = document.createElement('span');
                title.className = 'product-side-title';
                title.textContent = side['side']['name'];

                var imageContainer = document.createElement('div');
                imageContainer.className = 'product-side-image-container';

                var img = document.createElement('img');
                img.className = 'product-side-image';
                img.src = side['small_image'];

                mainContainer.appendChild(title);
                imageContainer.appendChild(img);
                mainContainer.appendChild(imageContainer);

                mainContainer.addEventListener('click', showAdditionalSide);

                additionalSidesContainer.appendChild(mainContainer);
            }

            // перезапишем объекты в канвасе
            constructorAdditionalObjects = tmpCanvasObjects;
        }

        // изменяет размер канваса
        function changeCanvasSize()
        {
            canvasBgImage.onload = function () {
                var imgWidth = this.width;
                var imgHeight = this.height;

                var canvasOffsetY = imgHeight / 100 * currentProduct['print_offset_y'];
                var canvasHeight = imgHeight / 100 * currentProduct['print_height'];
                var canvasOffsetX = imgWidth / 100 * currentProduct['print_offset_x'];  
                var canvasWidth = imgWidth / 100 * currentProduct['print_width'];

                // изменим отступы у канваса
                canvasContainer.style.top = canvasOffsetY + 'px';
                canvasContainer.style.left = canvasOffsetX + 'px';

                canvas.setHeight(canvasHeight);
                canvas.setWidth(canvasWidth);
            }
        }

        // обработчки событый на клик по размеру
        function sizeClickHandler() {
            var id = this.dataset.id;

            if (typeof id !== 'undefined' && !isNaN(parseInt(id)) && currentProductSize !== +id) {
                var colors = currentProduct['constructorColors'];



                // пройдемся по массиву цветов и найдем текущий цвет
                for (var i = 0; i < colors.length; i++) {
                    if (currentProductColorId == colors[i]['id']) {

                        var sizes = colors[i]['constructorSizes'];

                        for (var x = 0; x < sizes.length; x++) {
                            if (sizes[x]['id'] == id) {

                                // удалим класс текущего размера и присвоем другому элементу
                                var currentSizeElem = document.querySelector('.constructor-product-size.current-size');
                                removeClass(currentSizeElem, 'current-size');
                                this.className += ' current-size';

                                // изменим текущий цвет
                                currentProductSize = id;

                                return true;
                            }

                        }
                        
                    }
                }
            }
        }

        // изменение цены продукта
        function changeProductPrice() {
            document.querySelector('.constructor-price-value').textContent = currentPrice + 'Р';
        }

        function removeClass(obj, cls) {
            if (obj) {

                var classes = obj.className.split(' ');

                for (var i = 0; i < classes.length; i++) {
                    if (classes[i] == cls) {
                      classes.splice(i, 1);
                      i--;
                    }
                }

                obj.className = classes.join(' '); 
            }
    
        }

        // добавление в корзину
        function addToCart() {
            showLoader('Формируем заказ');
            // перезапишем текущий момент, потому что другая сторона уже записана
            var json = canvas.toJSON();

            if (currentProductSide == 'front' || currentProductSide === false)
                constructorObjects.front = json;
            else if (currentProductSide == 'back')
                constructorObjects.back = json;
            else 
                constructorAdditionalObjects[currentProductSide] = json;

            // сформируем фронт и бэк
            calculateMainData();   

        }

        // формирует данные о фронте и бэке для посылки на сервер
        function calculateMainData() {
            // возьмем ссылки на картинки цветов
            var colors = currentProduct['constructorColors'];
            var frontColorImage, backColorImage, color;
            for (var i = 0; i < colors.length; i++) {
                if (currentProductColorId == colors[i]['id']) {
                    color = colors[i];
                    frontColorImage = colors[i]['full_front_image'];
                    backColorImage = colors[i]['full_back_image'];
                }
            }

            // загрузим перднее изображение и размер принта
            getOrderImage(constructorObjects.front, frontColorImage, function(image, data) {
                frontData = {
                    print: encodeURIComponent(image),
                    size: currentPrintSize,
                    data: data,
                };

                // загрузим заднее изображение и размер принта
                getOrderImage(constructorObjects.back, backColorImage, function(image, data) {
                    backData = {
                        print: encodeURIComponent(image),
                        size: currentPrintSize,
                        data: data,
                    };

                    calculateAdditionalData(color['constructorSides'], 0);
                });
            });
        }

        // формирует данные для сервера с дополнительных сторон
        function calculateAdditionalData(sides, i) { 

            
            if (typeof sides[i] != 'undefined') {
                
                var sideId = sides[i]['side_id'];
                var bgImage = sides[i]['full_image'];

                var objects = false;

                if (typeof constructorAdditionalObjects[sideId] != 'undefined') 
                    objects = constructorAdditionalObjects[sideId];


                getOrderImage(objects, bgImage, function (image, data) {

                    additionalData.push({
                        side_id: sideId,
                        print: encodeURIComponent(image),
                        size: currentPrintSize,
                        data: data,
                    });

                    return calculateAdditionalData(sides, ++i);
                });

                
            } else {
                makeServerQuery();
                return true;
            }
            
        }

        // посылает запрос со всеми данными на сервер
        function makeServerQuery() {

            // формируем данные
            var data = csrfParam + '=' + csrfToken + '&front_data=' + JSON.stringify(frontData)
                        + '&back_data=' +  JSON.stringify(backData) + '&product_id=' + currentProduct['id']
                        + '&color_id=' + currentProductColorId + '&size_id=' + currentProductSize
                        + '&additional=' + JSON.stringify(additionalData); 
                        
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/constructor/add-to-cart/', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(data);

            xhr.onload = function () {
                console.log(xhr.responseText);
                hideLoader();
                if (xhr.status == 200) {
                    hideLoader();
                    var response = JSON.parse(xhr.responseText);
                    if (response['status']) {
                        showSuccessModal();
                        var countElem = document.getElementById('basket-count');
                        countElem.textContent = response['status'] > 0 ? ' ( ' + response['count'] + ' ) ' : ''; 
                    } else {
                        addConstructorError('Извините, произошла ошибка, попробуйте позже!');
                    }
                } else {
                    addConstructorError('Извините, произошла ошибка, попробуйте позже!');
                }
            }

            xhr.onerror = function() {
                console.log(xhr.responseText);
                hideLoader();
                addConstructorError('Извините, произошла ошибка, попробуйте позже!');
            }
            
            frontData = backData = {};
            additionalData = [];
        }

        // формирует картинку заказа
        function getOrderImage(objects, canvasBgImage, callback) {

            // изанчально все приведем к  дефолтному занчению
            cloneCanvas.clear();
            cloneCanvas.setWidth(canvas.getWidth());
            cloneCanvas.setHeight(canvas.getHeight());

            var bgImage = new Image();

            bgImage.onload = function() {

                // загрузили картинку товара
                var bgImageHeight = this.height;
                var bgImageWidth = this.width;


                var afterLoad = function() {

                    // увеличим сам принт и получим с него данные
                    var data = zoomCanvasObjects(bgImageWidth, bgImageHeight);

                    // получаем сам принт, установим его как картинк
                    var contentImage = new Image();
                    contentImage.src = cloneCanvas.toDataURL('image/png', 1.0);
                    cloneCanvas.clear();
               
                    contentImage.onload = function () {
                        // ставим картинку контента и картинку товара
                        cloneCanvas.setWidth(bgImageWidth);
                        cloneCanvas.setHeight(bgImageHeight);
                        cloneCanvas.setBackgroundImage(new fabric.Image(bgImage), function() {
                            var contentObj = new fabric.Image(contentImage);

                            contentObj.set({
                                top: bgImageHeight / 100 * currentProduct['print_offset_y'],
                                left: bgImageWidth / 100 * currentProduct['print_offset_x'],
                                height: bgImageHeight / 100 * currentProduct['print_height'],
                                width: bgImageWidth / 100 * currentProduct['print_width'],
                            });

                            cloneCanvas.add(contentObj);
                            contentObj.setCoords();
                            cloneCanvas.renderAll();
                            callback(cloneCanvas.toDataURL('image/png', 1.0), data);
                        });
                        
                    }      
                }

                // загрузим все объекты принта
                if (objects !== false)
                    cloneCanvas.loadFromJSON(objects, afterLoad);
                else
                    afterLoad();
            }

            bgImage.crossOrigin = "Anonymous";
            bgImage.src = canvasBgImage;
        }

        // увеличивает все объекты и оступы принта
        function zoomCanvasObjects(newCanvasWidth, newCanvasHeight) {
            var data = [];
            var objects = cloneCanvas.getObjects();

            var offsets = {left: false, top: false, right: false, bottom: false};

            // рабочая область, которая редактируется в админке
            var canvasWorkWidth = newCanvasWidth / 100 * currentProduct['print_width'];
            var canvasWorkHeight = newCanvasHeight / 100 * currentProduct['print_height'];

            cloneCanvas.setWidth(canvasWorkWidth);
            cloneCanvas.setHeight(canvasWorkHeight);

            var xFactor = canvasWorkWidth / canvas.getWidth();
            var yFactor = canvasWorkHeight / canvas.getHeight();

            for (var i in objects) {

                var object = objects[i];

                var scaleX = object.scaleX;
                var scaleY = object.scaleY;

                var left = object.left;
                var top = object.top;
                if (object.type == 'text') {
                    data.push({
                        type: 'text',
                        text: object.getText(),
                        font_family: object.getFontFamily(),
                        color: object.fill,
                    });
                } 
                if (object.type == 'image') {
                    data.push({
                        type: 'image',
                        src: encodeURIComponent(object.src),
                    });
                }
                
                getObjectOffsets(object, offsets);

            
                object.scaleX = scaleX * xFactor;
                object.scaleY = scaleY * yFactor;
                object.left = left * xFactor;
                object.top = top * yFactor;


                object.setCoords();
            }

            // рассчитаем размер принта
            calcPrintSize(offsets);

            cloneCanvas.renderAll();

            return data;
        }

        // высчитывает координаты, нужные для расчета размера принта
        function getObjectOffsets(object, values) {

            var height = canvas.getHeight();
            var width = canvas.getWidth();
            var coords = object.oCoords;

            // запишем координаты углов
            var yCoords = [coords.tl.y, coords.tr.y, coords.bl.y, coords.br.y];
            var xCoords = [coords.tl.x, coords.tr.x, coords.bl.x, coords.br.x];

            // найдем минимальные и максимальные отсутпы углов данного объект
            var minYOffset = coords.tl.y;
            var maxYOffset = coords.tr.y;
            var minXOffset = coords.tl.x;
            var maxXOffset = coords.tr.x;
            
            // минимальный отступ - верх, максимальный - низ для Y
            // минимальный отступ - левый, максимальный - правый для X
            for (i = 0; i < yCoords.length; i++) {
                minYOffset = yCoords[i] < minYOffset ? yCoords[i] : minYOffset;
                maxYOffset = yCoords[i] > maxYOffset ? yCoords[i] : maxYOffset;

                minXOffset = xCoords[i] < minXOffset ? xCoords[i] : minXOffset;
                maxXOffset = xCoords[i] > maxXOffset ? xCoords[i] : maxXOffset;
            }

            // проверим координаты, может они зашли за область канваса
            minYOffset = minYOffset < 0 ? 0 : minYOffset;
            maxYOffset = maxYOffset > height ? 0 : height - maxYOffset;

            minXOffset = minXOffset < 0 ? 0 : minXOffset;
            maxXOffset = maxXOffset > width ? 0 : width - maxXOffset;

            // теперь проверим, записывали ли мы ранее координаты
            if (values.top === false) {
                // если нет, то запишем эти
                values.top = minYOffset;
                values.bottom = maxYOffset;
                values.left = minXOffset;
                values.right = maxXOffset;
            } else {
                // иначе сравним с минимальными значениями отступов
                values.top = values.top < minYOffset ? values.top : minYOffset;
                values.bottom = values.bottom < maxYOffset ? values.bottom : maxYOffset;
                values.left = values.left < minXOffset ? values.left : minXOffset;
                values.right = values.right < maxXOffset ? values.right : maxXOffset;
            }
        }

        // рассчитать размер принта
        function calcPrintSize(values) {

            if (values.left === false) {
                currentPrintSize = 0;
                return;
            }

            var parentWidth = canvas.getWidth();
            var parentHeight = canvas.getHeight();
            var printWidth = parentWidth - values.left - values.right;
            var printHeight = parentHeight - values.top - values.bottom;

            var parentSquare = parentHeight * parentWidth;
            var printSquare = printWidth * printHeight;

            
            var printPercent = Math.ceil(printSquare / parentSquare * 100);
            var possibleSizes = []; // сюда будем записывать возиожные размеры принта

            // переберм все размеры
            for (var i = 0; i < printSizes.length; i++) {
                var current = printSizes[i];

                // если нашли прям ровный процент, о значит это
                if (current['percent'] == printPercent) {
                    currentPrintSize = current['id'];
                    return;
                }

                // это возиожные размеры принта
                if (current['percent'] > printPercent) {
                    possibleSizes.push(current);
                }
            }

            var minSize = possibleSizes[0];

            // найдем самый подзодящий размер
            for (i = 0; i < possibleSizes.length; i++) {
                minSize = possibleSizes[i]['percent'] < minSize['percent'] ? possibleSizes[i] : minSize;
            }

            currentPrintSize = minSize['id'];
        }

        // сменить таб 
        function chageTab (event) {

            event.preventDefault();

            if (!this.classList.contains('current-toogle')) {
                var toggle = this.dataset.toggle;
                var tabsBtns = document.getElementsByClassName('constructor-leftbar-toogle');
                for (var i = 0; i < tabsBtns.length; i++) {
                    tabsBtns[i].classList.remove('current-toogle');
                }

                this.classList.add('current-toogle');

                var tabs = document.getElementsByClassName('constructor-tab');

                for (i = 0; i < tabs.length; i++) {
                    tabs[i].style.display = 'none';
                }

                document.getElementById(toggle).style.display = 'block';

            }

        }


        // показать лоадер
        function showLoader(text) {
            document.getElementById('loader-text').textContent = text;
            document.getElementById('constructor-loader').style.display = 'block';
        }


        // скрыть лоадер
        function hideLoader() {
            document.getElementById('constructor-loader').style.display = 'none';
        }

        // скрыть модалку успешного заказа
        function hideSuccessModal(event) {
            event.preventDefault();
            document.getElementById('success-modal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        // показать модалку умпешного заказа
        function showSuccessModal() {
            var modal = document.getElementById('success-modal');
            modal.style.top = window.pageYOffset + 'px';
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
    }

}).call(this)