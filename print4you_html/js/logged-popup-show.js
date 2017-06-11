////////////////////////////////////////
// Скрипт при наведении мышкой на имя //
////////////////////////////////////////
$(document).ready(function(){
	// show
	$(".topline-elem4").bind("mouseenter", function(){
		$('.topline-popup').slideDown();
	});
	// hide
	$(".topline-popup").bind("mouseleave", function(){
		$('.topline-popup').slideUp();
	});	
	$(".topmenu").bind("mouseenter", function(){
		if ($('.topline-popup').is(":visible")){
			$('.topline-popup').slideUp();
		}
	});
});