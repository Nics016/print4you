<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\RequestCallForm;

$model = new RequestCallForm();

?>

<main class="franchise">
		<!-- LINE1 -->
		<div class="line1">
			<div class="container">
				<div class="title-line-bg"></div>
				<!-- TITLE-LINE -->
				<div class="title-line clearfix">
					<div class="title-line-left">
						<h1 class="subtitle">
							Печать на футболках print4you
							<span class="title">Франшиза</span>
						</h1>
					</div>

					<div class="title-line-right">
						<h3>Почему PRINT4YOU?</h3>
						<div class="title-line-right-item clearfix">
							<img src="/img/franchise-dash.png" alt="">
							<span>Развитие бизнеса за 1 год</span>
						</div>
						<div class="title-line-right-item clearfix">
							<img src="/img/franchise-dash.png" alt="">
							<span>Потребительский поток с 1 месяца</span>
						</div>
					</div>
				</div>
				<!-- END OF TITLE-LINE -->

				<img src="/img/franchise-banner.jpg" alt="" class="banner">

				<!-- PLUSES -->
				<div class="pluses">
					<div class="pluses-plus">
						<div class="pluses-plus-title-line clearfix">
							<img src="/img/franchise-01.png" alt="">
							<h2 class="title">
								Инвестиции для регионов <br>
								390.000 тысяч рублей
							</h2>
						</div>
						<div class="pluses-plus-info">
							<ul>
								<li>
									Ежемесячные отчисления - от 20.000 со второго месяца
								</li>	
								<li>Организация - 370.000 тысяч рублей</li>
								<li>Число рабочих мест - 1 место</li>
								<li>Площадь - 15 м2</li>
								<li>Средний чек - 550 рублей</li>
								<li>Чистая прибыль за месяц - 100.000 - 250.000</li>		
							</ul>
						</div>
					</div>

					<div class="pluses-plus">
						<div class="pluses-plus-title-line clearfix">
							<img src="/img/franchise-02.png" alt="">
							<h3 class="title">
								Инвестиции для крупных городов России <br>
								890.000 тысяч рублей
							</h3>
						</div>
						<div class="pluses-plus-info">
							<ul>
								<li>Ежемесячные отчисления - от 50.000 со второго месяца</li>
								<li>Организация - 840.000 тысяч рублей</li>
								<li>Число рабочих мест - 2 место</li>
								<li>Площадь - 40 м2</li>
								<li>Средний чек - 950 рублей</li>
								<li>Чистая прибыль за месяц - 600.000 - 1.500.000</li>	
							</ul>
						</div>
					</div>
				</div>
				<!-- END OF PLUSES -->
			</div>
		</div>
		<!-- END OF LINE1 -->
		<div class="line2">
			<div class="container">
				<div class="request">
					<h2 class="title">
						Заявка на франшизу
					</h2>
					<div class="underline"></div>
					<span>В поле "примечание" укажите ваш город</span>

					<div class="contacts-questions-form-wrapper">
						<?php $form = ActiveForm::begin(['action' => ['site/request-call-sent'], 'method' => 'POST', 'options' => ['class' => 'clearfix contacts-questions-form']]); ?>
							<div class="contacts-questions-form-left">
								<div class="contacts-questions-form-left-line">
									<?= $form->field($model, 'name')->textInput(['placeholder' => 'Ваше имя', 'class' => '', 'style' => ''])->label(false) ?>		
									<img src="/img/topline-lk.png" alt="">
								</div>
								<div class="contacts-questions-form-left-line">
									<?= $form->field($model, 'phone')->textInput(['placeholder' => 'Ваш телефон', 'class' => '', 'style' => ''])->label(false) ?>
									<img src="/img/topline-phone.png" alt="">
								</div>
								<div class="contacts-questions-form-left-line">
									<?= $form->field($model, 'email')->textInput(['placeholder' => 'Ваше Email', 'class' => '', 'style' => ''])->label(false) ?>
									<img src="/img/topline-mail.png" alt="">
								</div>
							</div>
							<div class="contacts-questions-form-right">
								<div class="clearfix">
									<?= $form->field($model, 'comment')->textArea(['placeholder' => 'Примечание', 'class' => '', 'style' => ''])->label(false) ?>
									<img src="/img/contacts-questions-form-msg.png" alt="">
								</div>
								<?= $form->field($model, 'form_type')->hiddenInput(['value' => RequestCallForm::FORM_TYPE_FRANCHISE])->label(false) ?>
								<input type="submit" value="Отправить">
							</div>
						<?php ActiveForm::end(); ?>
					</div>
				</div>
			</div>
		</div>
	</main>