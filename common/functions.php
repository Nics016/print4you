<?php 

function dd(...$vars) {
	echo '<pre>';
	for ($i = 0; $i < count($vars); $i++) {
		print_r($vars[$i]);
	}
	echo '</pre>';
}

function dump(...$vars) {
	echo '<pre>';
	for ($i = 0; $i < count($vars); $i++) {
		var_dump($vars[$i]);
	}
	echo '</pre>';
}