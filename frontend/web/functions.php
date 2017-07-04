<?php 

// красивый формат времени4
function beatyTime($timestamp) {
	$day = date('j', $timestamp);
	$month = date('n', $timestamp);
	$year = date('Y', $timestamp);
	$time = date('H:i', $timestamp);

	switch ($month) {
		case 1:
			return "$day Января $year в $time";
		
		case 2:
			return "$day Февраля $year в $time";

		case 3:
			return "$day Марта $year в $time";

		case 4:
			return "$day Апреля $year в $time";

		case 5:
			return "$day Мая $year в $time";

		case 6:
			return "$day Июня $year в $time";

		case 7:
			return "$day Июля $year в $time";

		case 8:
			return "$day Августа $year в $time";

		case 9:
			return "$day Сентября $year в $time";

		case 10:
			return "$day Октября $year в $time";

		case 11:
			return "$day Ноября в $time";

		case 12:
			return "$day Декабря в $time";
	}

}