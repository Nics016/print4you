var linksVideo = [
    'ld9A6WVatL0',
    'ld9A6WVatL0'
];
jQuery(document).ready(function($) {
	///////////////
	// Variables //
	///////////////
	var iNumElements = 0; // total elements size
	var iCurElement = 1; // currently selected item
	var portraits = [];
	var sections = [];
	var activeImgSrc = "/img/line3-circle-active.png";
	var passiveImgSrc = "/img/line3-circle.png";
	var mainSliderMax1 = -1;
	var mainSliderMax2 = -1;
	var mainSliderCur1 = -1;
	var mainSliderCur2 = -1;
	var mainSliderIntervalId = -1;
	var _csrf = $('meta[name="csrf-token"]').attr('content');

	// маска на телефон
	$('.masked-phone').mask("+7 ?(999) 999-99-99");

	//////////
	// Init //
	//////////

	initLine3Slider();

	initMainLine2Slider(2, 2, 4000);
	
	activateAssortyTabs();

	// lightgallery
	$(".footer-right-photos").lightGallery();

	// activate bxslider
	$('.line5-slider').bxSlider({
		minSlides: 4,
 		maxSlides: 4,
 		slideWidth: 360,
  		slideMargin: 10
	});

	initNewOrderCheckbox();


	// логин
	$('#login-form').on('beforeSubmit', function() {
		var form = $(this);
		var btn = form.find('button[type="submit"]');
		var error = form.find('.login-error');

		if (form.find('.has-error').length) return false;

		btn.prop('disabled', true);
		error.hide();

		var phone = form.find('.login-phone').val();
		var password = form.find('.login-password').val();
		var checkbox = form.find('.login-remember');
		var rememberMe = $(checkbox).is(':checked') ? 1 : 0;

		$.ajax({
			url: '/site/login/',
			data: {'csrf-token': _csrf, phone: phone, password: password, rememberMe: rememberMe},
			type: 'POST',
			success: function (response) {
				if (response['status'] == 'ok') {
					window.location.reload(true);
				} else {
					btn.prop('disabled', false);
					error.show();
				}
			},
			error: function (err) {

			}
		});


		return false;
	})

	/**
	 * Бинд функции при нажатии на чекбокс "Доставка"
	 * на странице new-order
	 */
	function initNewOrderCheckbox()
	{
		// hide delivery_office_id initially
		$(".field-orders-delivery_office_id").hide();
		$("#orders-delivery_required").bind("click", function(){
			if (document.getElementById("orders-delivery_required").checked){
				$(".field-orders-delivery_office_id").slideUp();
				$(".field-orders-address").slideDown();
			} else {
				$(".field-orders-address").slideUp();
				$(".field-orders-delivery_office_id").slideDown();
			}
		});
	}

	/**
	 * Табы Розница / Оптом на странице uslugi/assorty
	 */
	function activateAssortyTabs()
	{
		$(".assorty-category a").each(function(index){
			$(this).bind("click", function(e){
				e.preventDefault();
				clickAssortyTab(index);
			});
		});

		clickAssortyTab(0);
	}

	var assortyLimit = $('#assorty-limit').val();
	var assortyOffset = $('#assorty-offset').val();


	// кнопка load-more-assorty на странице ассортимента
	$('#load-more-assorty').bind('click', function (event){
		event.preventDefault();
		assortyOffset += assortyLimit;
		var elem = $(this);
		var data = {'_csrf-frontend': _csrf, offset: assortyOffset, limit: assortyLimit};
		elem.prop('disabled', true);
		$.ajax({
			url: '/uslugi/load-more-assorty/',
			data: data,
			type: 'POST',
			success: function (msg) {
				console.log(msg);
				if (msg['status'] == 'ok') {
					$('#simple_price').append(msg['simple_html']);
					$('#gross_price').append(msg['gross_html']);
					elem.prop('disabled', false);
					return false;
				} 

				elem.remove();
			},
			error: function (err) {
				console.log(err);
				elem.remove();
			}
		});
	});

	function clickAssortyTab(i)
	{
		// change .active class
		$(".assorty-category a").removeClass("active");
		$(".assorty-category a").eq(i).addClass("active");

		// show tab
		$(".line2-tabs-tab").fadeOut().eq(i).fadeIn();
	}

	////////////////////////////////////////////////////
	// The main's line 2 slider functionality script. //
	////////////////////////////////////////////////////
	/**
	 * Инициализация mainLine2Slider
	 */
	function initMainLine2Slider(maxSlides1, maxSlides2, timeToChangeSlide)
	{
		mainSliderMax1 = maxSlides1;
		mainSliderMax2 = maxSlides2;
		mainSliderCur1 = 1;
		mainSliderCur2 = 1;
		$('.main .line2 .info-box-btns-right').eq(0)
		.bind("click", function(){
			mainSliderClearInterval();
			mainSliderNext(0);
		});
		$('.main .line2 .info-box-btns-left').eq(0)
		.bind("click", function(){
			mainSliderClearInterval();
			mainSliderPrev(0);
		});
		$('.main .line2 .info-box-btns-right').eq(1)
		.bind("click", function(){
			mainSliderClearInterval();
			mainSliderNext(1);
		});
		$('.main .line2 .info-box-btns-left').eq(1)
		.bind("click", function(){
			mainSliderClearInterval();
			mainSliderPrev(1);
		});

		mainSliderIntervalId = setInterval(function(){
			mainSliderNext();
		}, timeToChangeSlide);
	}

	/**
	 * Функция при нажатии на стрелочку вправо
	 */
	function mainSliderNext(j = -1)
	{
		switch(j) {
			case 0:
				mainSliderCur1++;
				if (mainSliderCur1 > mainSliderMax1)
					mainSliderCur1 = 1;
				mainSliderUpdate(0);
				break;
			case 1:
				mainSliderCur2++;
				if (mainSliderCur2 > mainSliderMax2)
					mainSliderCur2 = 1;
				mainSliderUpdate(1);
				break;
			case -1:
				mainSliderCur1++;
				if (mainSliderCur1 > mainSliderMax1)
					mainSliderCur1 = 1;
				mainSliderCur2++;
				if (mainSliderCur2 > mainSliderMax2)
					mainSliderCur2 = 1;
				mainSliderUpdate();
				break;
		}
		
		
		
	}

	/**
	 * Функция при нажатии на стрелочку влево
	 */
	function mainSliderPrev(j)
	{
		switch(j) {
			case 0:
				mainSliderCur1--;
				if (mainSliderCur1 < 1)
					mainSliderCur1 = mainSliderMax1;
				mainSliderUpdate(0);
				break;
			case 1:
				mainSliderCur2--;
					if (mainSliderCur2 < 1)
						mainSliderCur2 = mainSliderMax2;
				mainSliderUpdate(1);
				break;
		}
	}

	/**
	 * Останавливает автослайдинг,
	 * если он есть
	 */
	function mainSliderClearInterval()
	{
		if (mainSliderIntervalId != -1){
			clearInterval(mainSliderIntervalId);
			mainSliderIntervalId = -1;
		}
	}

	/**
	 * Обновление картинок слайдера с анимацией
	 */
	function mainSliderUpdate(j = -1)
	{
		// обновить оба слайдера?
		var both = (j === -1) ? true : false;
		// обновить первый слайдер только?
		var first = (j === 0) ? true : false;
		// обновить второй слайдер только?
		var second = (j === 1) ? true : false;
		// var bg = '.main .line2';
		var slider = '.main .line2 .info-box';
		// $(bg).fadeTo(0, 1).fadeTo(0, 0.7);
		// $(bg).css("background-image",
		// 	"url(/img/main-line2-bg" + mainSliderCur1 + ".jpg")
		// 	.fadeTo(500, 1);
		if (first || both) {
			$(slider).eq(0).fadeTo(0, 1).fadeTo(0, 0);
			$(slider).eq(0).css("background-image",
				"url(/img/main-line2-slider" + mainSliderCur1 + ".png")
				.fadeTo(1000, 1);
		}
		if (second || both) {
			// время до обновления
			var msInterval = second ? 0 : 500;
			setTimeout(function(){
				$(slider).eq(1).fadeTo(0, 1).fadeTo(0, 0);
				$(slider).eq(1).css("background-image",
					"url(/img/main-line2-slider" + mainSliderCur2 + ".png")
				.fadeTo(1000, 1);
			}, msInterval);
		}
		
	}

	////////////////////////////////////////////////
	// The line 3 carousel's functionality script //
	////////////////////////////////////////////////
	/**
	 * Init
	 */
	function initLine3Slider(){
		// count elements num
		// and push their src's to the portraits array
		$('.line3-carousel-portraits img').each(function(){
			iNumElements++;
			var curPortait = $(this).attr("src");
			portraits.push(curPortait);
		});

		// generate circles
		if (iNumElements > 0){
			var circlesHtml = '<img src="img/line3-circle-active.png" alt="">';
			var i;
			for (i=2; i <= iNumElements; i++){
				circlesHtml +='<img style="margin-left: 5px" src="img/line3-circle.png" alt="">';
			}
			$('.line3-carousel-circles').html(circlesHtml);
			var sCirclesWidth = iNumElements * 18 + 'px';
			$('.line3-carousel-circles').css('width', sCirclesWidth);
		}

		// generate sections array
		$('.line3-carousel-info section').each(function(){
			sections.push($(this).html());
		});

		// bind click function on circles
		var i = 1;
		$('.line3-carousel-circles img').each(function(){
			var newI = i;
			$(this).bind("click", function(){
				pickElement(newI);
			});
			i++;
		});

		// simulate click on the 1st tab
		pickElement(1);
	}

	/**
	 * Function which triggers when clicking
	 * on a circle beneath the reviews.
	 * 
	 * @param  {int} id section-id to show
	 */
	function pickElement(id){
		// set section's content
		$('.line3-carousel-info .active')
			.html(sections[id-1]);

		// set active circle
		var i = 1;
		$('.line3-carousel-circles img').each(function(){
			$(this).attr("src", passiveImgSrc);
			if (i == id)
				$(this).attr("src", activeImgSrc);
			i++;
		});

		// set portraits
		var prevId = id - 1;
		var nextId = id + 1;
		if (prevId < 1)
			prevId = iNumElements;
		if (nextId > iNumElements)
			nextId = 1;
		var i = 1;
		$('.line3-carousel-portraits img').each(function(){
			if (i == 1){
				$(this).attr("src", portraits[prevId-1]);
				$(this).unbind("click");
				$(this).bind("click", function(){
					pickElement(prevId);
				});
			}
			else if (i == 2){
				$(this).attr("src", portraits[id-1]);
				$(this).unbind("click");
				$(this).bind("click", function(){
					pickElement(id);
				});
			}
			else if (i == 3){
				$(this).attr("src", portraits[nextId-1]);
				$(this).unbind("click");
				$(this).bind("click", function(){
					pickElement(nextId);
				});
			}
			i++;
		});

		// animation
		$('.line3-carousel-info .active').slideUp(0);
		$('.line3-carousel-info .active').slideDown(500);
	}

	//////////////////////////////////////////////////////////////
	// Скрипт подсветки текущей страницы красным цветом в меню  //
	//////////////////////////////////////////////////////////////
	$(document).ready(function(){
		// Убираем все классы "active"
		$('nav a.active').removeClass("active");

		// Получаем URL текущей открытой страницы без параметров
		var curHref = window.location.href.toString()
		    .split(window.location.host)[1]
		        .split('&')[0];

		// Проходим по каждой ссылке в меню. Если она = открытой, 
		// помечаем её классом "active"
		$('nav a').each(function(){
		    var curMenuLinkURL = $(this).attr('href');
		    if (curMenuLinkURL == curHref){
		        $(this).addClass("active");
		        
		        return false;
		    }
		});
	});

	// Отзывы

	// очистим ошибки
	$('#review-textarea').on('keyup', function() {
		$(this).siblings('.review-form-error').hide();
	});

	// добавление отзыва
	$('#add-review').on('click', function() {
		var elem = $(this);
		var parent = elem.closest('.review-form-container');
		var val = elem.siblings('#review-textarea').val();
		var errorElem = elem.siblings('.review-form-error');
		var successElem = elem.siblings('.review-form-success');

		if (val.replace(/\s/g, '').length == 0) {
			$(successElem).hide();
			$(errorElem).text('Пожалуйста, введите отзыв!');
			$(errorElem).show();
			return false;
		} else if(val.replace(/\s/g, '').length < 5) {
			$(successElem).hide();
			$(errorElem).text('Отзыв должен быть длинной не менее 5 символов!');
			$(errorElem).show();
			return false;
		}

		var isLike = parseInt($(parent).find('[name="review-type"]:checked').val());

	
		if (isLike !== 1 && isLike !== 0) {
			$(errorElem).text('Что то пошло не так, обновите страницу!');
			$(errorElem).show();
			return false;
		}

		elem.prop('disabled', true);

		$.ajax({
			url: '/reviews/add-review/',
			type: 'POST',
			data: {'_csrf-frontend': _csrf, text: val, is_like: isLike},
			success: function (response) {
				console.log(response);

				switch (response['status']) {
					case 'ok':
						$(errorElem).hide();
						$(successElem).show();
						setTimeout(function(){
							$(successElem).hide();
						}, 5000);
						elem.siblings('#review-textarea').val('');
						break;

					case 'login': 
						$(errorElem).text('Вы не авторизованы!');
						$(errorElem).show();
						break;

					case 'moder':
						$(errorElem).text('Ваш отзыв проходит модерацию!');
						$(errorElem).show();
						break;

					case 'fail':
						$(errorElem).text('Произошла ошибка, обновите страницу!');
						$(errorElem).show();
						break;
				}

				elem.prop('disabled', false);

			},
			error: function (err) {
				console.log(err);
				elem.prop('disabled', false);
			}

		})
	});

	// sales

	// sale silder
	$('#sale-slider').bxSlider({
		responsive: false,
		auto: true,
	});

	// guset gallery
	$('.guests-container').lightGallery({
	    thumbnail:true
	}); 

});	

////////////////////
// Videos scripts //
////////////////////
/**
 * Triggers when #video-i clicked.
 */
function playVideo(i)
{
    var link = linksVideo[i];
    document.getElementById('video-' + i).innerHTML = 
        '<iframe width="1140" height="590" src="https://www.youtube.com/embed/' + link + '?showinfo=0&rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>';
}