(function() {

	window.onload = function() {

        var csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var products = false;
        var currentProduct = false, 
            currentProductColorId = false, 
            currentProductSize = false, 
            currentProductPrice = false;


        var constructorTextArea = document.getElementById('constructor-text');
        var constructorTextColor = document.getElementById('text-color');
        var constructorTextFontFamily = document.getElementById('constructor-text-font-family');

        var constructorObjects = {
            front: false,
            back: false,
        };

        var canvas = new fabric.Canvas('constructor-canvas', {
            containerClass: 'canvas-container',
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
        document.getElementById('front-side').onclick = showFrontSide;

        // клик на бэк
        document.getElementById('back-side').onclick = showBackSide;


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

            // найдем текущий цвет
            var colors = currentProduct['colors'];

            for (var i = 0; i < colors.length; i++) {
                if (colors[i]['id'] == currentProductColorId) {
                    
                    document.getElementById('canvas-bg-image').src = colors[i]['front_image'];
                    console.log(colors[i]['front_image']);
                }
            }

            var json = canvas.toJSON();
            constructorObjects.back = json;
            canvas.clear();
            if (constructorObjects.front !== false) 
                canvas.loadFromJSON(constructorObjects.front, canvas.renderAll.bind(canvas));
            
            disableText();
        }


        // показ задней стороны
        function showBackSide() {

            // найдем текущий цвет
            var colors = currentProduct['colors'];

            for (var i = 0; i < colors.length; i++) {
                if (colors[i]['id'] == currentProductColorId)
                    document.getElementById('canvas-bg-image').src = colors[i]['back_image'];
            }


            var json = canvas.toJSON();
            constructorObjects.front = json;
            canvas.clear();
            if (constructorObjects.back !== false)
                canvas.loadFromJSON(constructorObjects.back, canvas.renderAll.bind(canvas));

            disableText();            
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

            var file = event.target.files[0];
            var ext = false;
            var parts = file.name.split('.');
            if (parts.length > 1) ext = parts.pop();

            if (ext != 'jpg' && ext != 'png') {
                addConstructorError('Расширение файла не поддерживается');
                return false;
            }

            if (file.size / 1024 / 1024 > 8) {
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
            
                    canvas.setActiveObject(image);

                    // центрирование
                    image.center();
                    image.setCoords();

                    canvas.setActiveObject(image);

                }

            }

            reader.readAsDataURL(file);
            
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
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/constructor/get-products/', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(csrfParam + '='+ csrfToken);
            xhr.onload = function () {
                if (xhr.status == 200) {
                    products = JSON.parse(xhr.responseText)[0];
                    renderProducts();
                }
            }
        }

        // рендер продуктов, пришедштх с сервера
        function renderProducts() {
            //изменим селект
            var select = document.getElementById('constructor-leftbar-select');
            for (var i = 0; i < products.length; i++) {
                var opt = document.createElement('option');
                opt.value = products[i]['id'];
                opt.innerHTML = products[i]['name'];
                select.appendChild(opt);
            }

            renderCurrentCat('all');

            select.addEventListener('change', function() {
                renderCurrentCat(this.value);
            });

            // вызовим клик на первого ребенка
            var product = document.querySelector('.leftarea-product-image-container');
            if (product) product.dispatchEvent(new Event('click'));

            document.getElementById('constructor-loader').style.display = 'none';
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
            } else {

                // рендер отдельной категории
                for (var i = 0; i < products.length; i++) {
                    if (catId == products[i]['id']) 
                        renderCurrentCatHelp(i, productsContainer);
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

            var colors = currentProduct['colors'];

            for (var i = 0; i < colors.length; i++) {
                var div = document.createElement('div');
                div.className = 'constructor-product-color';
                div.style.backgroundColor = colors[i]['color_value'];
                div.dataset.id = colors[i]['id'];
                colorsContainer.appendChild(div);
            }

            // установим цену
            currentPrice = currentProduct['price'];
            changeProductPrice();

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
            var colors = currentProduct['colors'];
            for (var i = 0; i < colors.length; i++) {
                if (colors[i]['id'] == id) {
                    document.querySelector('.constructor-product-color-value').textContent = colors[i]['name'];
                    document.getElementById('canvas-bg-image').src = colors[i]['front_image'];
                    document.querySelector('#front-side .product-side-image').src = colors[i]['front_image'];
                    document.querySelector('#back-side .product-side-image').src = colors[i]['back_image'];
                    var current = document.querySelector('.constructor-product-color.current-color');
                    removeClass(current, 'current-color');

                    return true;
                }
            }

            return false;
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
            window.open(canvas.toDataURL("image/png"));
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
    }

}).call(this)