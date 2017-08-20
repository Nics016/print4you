<?php 
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>

<main id="reviews">

	<h1 class="reviews-main-title">Отзывы</h1>

	<div class="container clearfix">
		
		<div class="left-column">
			
			<?php if (Yii::$app->user->isGuest): ?>
			<div class="not-login-container">
				<span class="not-login-text">
					<a href="#" data-toggle="modal" data-target="#loginRegisterModal">Войдите</a>
					или
					<a href="<?= Url::to(['/register/']) ?>">зарегистрируйтесь</a>,
					чтобы оставить отзыв!
				</span>
			</div>
			<?php else: ?>
				<div class="review-form-container">
					<span class="review-form-label">Оставьте свой отзыв:</span>
					<textarea id="review-textarea" placeholder="Введите ваш отзыв"></textarea>

					<div class="review-form-checkboxes clearfix">

						<div class="review-checkbox-container">
							<label for="type-like">
								<i class="review-icon like"></i>
							</label>
							<input id="type-like" type="radio" name="review-type" checked value="1">
						</div>

						<div class="review-checkbox-container">
							<label for="type-dislike">
								<i class="review-icon dislike"></i>
							</label>
							<input id="type-dislike" type="radio" name="review-type" value="0">
						</div>

					</div>
						
					<button id="add-review">Оставить отзыв</button>

					<div class="review-form-error"></div>
					<div class="review-form-success">Ваш отзыв отправлен на модерацию!</div>
				</div>
			<?php endif; ?>

			<div class="reviews-container">
				<span class="reviews-title">Наши отзывы:</span>
				<?php if (count($reviews) == 0): ?>
					<span class="not-reviews">Отзывов пока нет, будьте первыми, кто его оставит!</span>
				<?php else: ?>

					<?php 
					for ($i = 0; $i < count($reviews); $i++):
						$user_name = $reviews[$i]['user']['firstname'];
						$date = beatyTime(strtotime($reviews[$i]['created_at']));
						$text = $reviews[$i]['text'];
					?>
					<div class="review">
						<div class="review-top clearfix">
							<i class="review-icon like"></i>
							<span class="review-user-name"><?= $user_name ?></span>
							<span class="review-date"><?= $date ?></span>
						</div>
						<span class="review-text"><?= $text ?></span>
					</div>
					<?php endfor; ?>

				<?php endif; ?>
			</div>

			<div class="pagination-container">
				<?= LinkPager::widget(['pagination' => $pages]) ?>
			</div>
			
		</div>
	
		<div class="right-column">
			asdasd
		</div>

	</div>
</main>