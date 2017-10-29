<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\CommonUser;
use frontend\components\Sms;

class ForgotPassword extends Model
{	
	private $response; // ответ от сервера
	public $phone;	// номер телефона
	private $sms_code; // массив для данных о смс коде
	public $code;	// смс код

	public static function ajaxLoad()
	{	
		$model = new self();
		$action = Yii::$app->request->post('action');

		if ($action == 'send') {

			$model->phone = Yii::$app->request->post('phone');
			return $model->sendSmsCode();

		} elseif ($action == 'verify') {

			$model->phone = Yii::$app->request->post('phone');
			$model->code = Yii::$app->request->post('code');
			return $model->changePassword();

		}
	}

	public function __construct()
	{
		Yii::$app->session->open();
		$this->sms_code = $_SESSION['forgot_password_sms'] ?? null;
	}

	// валидация на телефон
	// возвращает пользователя при необходимости
	private function phoneValidate($return_user = false)
	{
		if ($this->phone == null || !preg_match('/^9[0-9]{9}$/', $this->phone)) {
			$this->response = [
				'status' => 'fail',
				'field' => 'phone',
				'message' => 'Неверный номер!',
			];

			return false;
		}

		$user = CommonUser::findByPhone($this->phone);

		if ($user == null) {
			$this->response = [
				'status' => 'fail',
				'field' => 'phone',
				'message' => 'Номер не зарегистрирован!',
			];

			return false;
		}

		return $return_user ? $user : true;
	}

	// валидация на отправку смс
	public function sendSmsValidate()
	{
		if ($this->sms_code !== null && isset($this->sms_code['expired']) && $this->sms_code['expired'] > time()) {
			$this->response = [
				'status' => 'fail',
				'field' => 'timer',
				'seconds' => $this->sms_code['expired'] - time(),
			];

			return false;
		} 

		return true;
	}

	// генерирует смс
	public function generateSms()
	{
		$code = rand(1000, 9999);
		$message = "Код для восстановления пароля - $code";

		if ($this->sms_code == null) {

			$this->sms_code = [];
			$this->sms_code['code'] = $code;
			$this->sms_code['expired'] = time() + 60;
			$this->sms_code['try'] = 1;

		} else {
			$this->sms_code['code'] = $code;
			$this->sms_code['expired'] = time() + 60 + $this->sms_code['try'] * 30;
			$this->sms_code['try']++;
		}

		Sms::message($this->phone, $message);
	}

	// валидация СМС кода
	private function SMSCodeValidate()
	{
		if ($this->sms_code == null || $this->code == null) {
			$this->response = [
				'status' => 'fail',
				'field' => 'sms-code',
				'message' => 'СМС код не отправлен!',
			];

			return false;
		}

		if ($this->sms_code['code'] != $this->code) {
			$this->response = [
				'status' => 'fail',
				'field' => 'sms-code',
				'message' => 'Неверный СМС код!',
			];

			return false;
		}

		return true;
	}

	// отравка СМС кода
	public function sendSmsCode()
	{	
		if (!$this->phoneValidate()) return $this->response;
		if (!$this->sendSmsValidate()) return $this->response; 

		$this->generateSms();
		$this->response = ['status' => 'ok', 'seconds' => $this->sms_code['expired'] - time()];
		$_SESSION['forgot_password_sms'] = $this->sms_code;

		return $this->response;
	}

	// изменение пароля пользователя
	public function changePassword()
	{	
		if (!$this->SMSCodeValidate()) return $this->response;
		$user = $this->phoneValidate(true);
		if ($user === false) return $this->response;

		$new_password = Yii::$app->security->generateRandomString(8);
		$user->generatePasswordHash($new_password);

		if ($user->save(false)) {

			$this->response = [
				'status' => 'ok',
			];

			$message = "Ваш новый пароль - $new_password";
			Sms::message($this->phone, $message);
			$_SESSION['forgot_password_sms'] = [];

		} else {
			$this->response = [
				'status' => 'fail',
				'field' => 'sms-code',
				'message' => 'Произошла ошибка!',
			];
		}

		return $this->response;
	}

}