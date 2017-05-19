var iNumElements = 0; // total elements size
var iCurElement = 1; // currently selected item
var portraits = [];
var sections = [];
var activeImgSrc = "img/line3-circle-active.png";
var passiveImgSrc = "img/line3-circle.png";

$(document).ready(function(){
	initLine3Slider();

	// activate bxslider
	$('.line5-slider').bxSlider({
		minSlides: 4,
 		maxSlides: 4,
 		slideWidth: 360,
  		slideMargin: 10
	});

    ///////////////////////////////////////////////////////////
    // Скрипт подсветки текущей страницы белым цветом в меню //
    ///////////////////////////////////////////////////////////

    // Убираем все классы "active"
    $('nav a.active').removeClass("active");

    // Получаем URL текущей открытой страницы без параметров
    var curHref = window.location.href.toString()
        .split(window.location.host)[1]
            .split('&')[0];

    // Проходим по каждой ссылке в меню. Если она = открытой, 
    // помечаем её классом "active", а все li над ней, которые имеют 
    // класс has-sub, помечаем классами "active" и "opened"
    $('nav a').each(function(){
        var curMenuLinkURL = $(this).attr('href');
        if (curMenuLinkURL == curHref){
            $(this).addClass("active");
            
            return false;
        }
    });

});

/**
 * The line 3 carousel's functionality script.
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

/**
 * Triggers when #video-1 clicked.
 */
function playVideo1(){
	document.getElementById('video-1').innerHTML = 
	'<iframe width="1140" height="590" src="https://www.youtube.com/embed/QIHnSxfZhEM?showinfo=0&rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>';
}