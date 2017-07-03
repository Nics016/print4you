<?php 

namespace frontend\components;

class Sms {

	const API_ID = 'A9AA335E-3850-E528-3CBA-F74DDA2D0709';

	public static function sendCodeSms($number, $code) {

		if (!self::validatePhoneNumber($number)) return false;

		$ch = curl_init("https://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, [

			"api_id"	=>	self::API_ID,
			"to"		=>	$number,
			"text"		=>	"$code - код подтверждения для Вашего номера телефона. Sputnik",

		]);

		$body = curl_exec($ch);
		curl_close($ch);

		return true;

	}

	public static function message($number, $message)
	{	
		if (!self::validatePhoneNumber($number)) return false;

		$ch = curl_init("https://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, [

			"api_id"	=>	self::API_ID,
			"to"		=>	$number,
			"text"		=>	$message,

		]);

		$body = curl_exec($ch);
		curl_close($ch);

		return true;
	}

	public static function validatePhoneNumber($number) 
	{
		return preg_match('/^9[0-9]{9}$/', $number);
	}

}