<?php 
$this->title = 'Восстановление пароля';

// common forgot-password.js
$js_file_name = Yii::getAlias('@frontend') . '/web/js/forgot-password.js';
$this->registerJsFile('/js/forgot-password.js?v=' . filemtime($js_file_name), [
	'position' => \yii\web\View::POS_END,
	'depends' => [
		'frontend\assets\jQueryAsset',
		'frontend\assets\AppAsset',
	],
]);
?>

<main id="forgot-password">

	<div class="container">

		<h1 class="forgot-password-main-title">Восстановление пароля</h1>
		
		<span class="forgot-password-text">
			Введите Ваш номер, далее Вам придет СМС код. 
			<br>
			После введите СМС код, и мы вышлем Вам пароль.
		</span>

		<form id="forgot-password-form">
			
			<div class="form-group phone-container">
				<label for="phone" class="control-label">Номер телефона</label>
				<input type="text" id="phone" class="form-control masked-phone" placeholder="+7 (999) 999-99-99" autocomplete="off">
				<div class="help-block"></div>
			</div>
			
			<div class="form-group sms-code-container">
				<label for="sms-code control-label">СМС код</label>
				<input type="text" id="sms-code" class="form-control" maxlength="4" disabled autocomplete="off">
				<div class="help-block"></div>
			</div>
			
			<div class="timer-container">
				До повтороной отправки СМС осталось 
				<span id="timer">00:00</span>
			</div>
			
			<button type="sibmit" id="forgot-password-submit" class="btn btn-success">Отправить</button>

		</form>
	
	</div>

</main>


<div class="modal fade" tabindex="-1" id="success-modal">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">&times;</span>
		        </button>
		    </div>
			<div class="modal-body">
				<span class="h2">Новый пароль был Вам отправлен по СМС!</span>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
			</div>
		</div>	
	</div>
</div>
