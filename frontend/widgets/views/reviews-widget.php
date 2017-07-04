<!-- LINE3 -->
<?php 
use yii\helpers\Url;

if (count($reviews) > 0): 
	$active = floor(count($reviews) / 2);
?>
<div class="shelkography">
	<div class="line3">
		<div class="container">
			<div class="line3-title">
				<h3>
					Что говорят о нас
				</h3>
				<div class="line3-title-reviews">
					<h2>
						Наши клиенты
					</h2>
					<div class="line3-title-reviews-underline"></div>
				</div>
			</div>
			<div class="line3-carousel">
				<div class="line3-carousel-portraits clearfix">

					<?php for ($i = 0; $i < count($reviews); $i++): ?>
						<img src="/img/user-icon.png" alt="" 
							class="<?= $i == $active ? 'active' : '' ?>">
					<?php endfor; ?>

				</div>
				<div class="line3-carousel-info">
					<?php 
					for ($i = 0; $i < count($reviews); $i++): 
						$username = $reviews[$i]['user']['firstname'];
						$text = $reviews[$i]['text'];
					?>
						<section class="<?= $i == $active ? 'active' : '' ?>">
							<h4><?= $username ?></h4>
							<p><?= $text ?></p>
						</section>
					<?php endfor; ?>
				</div>
				<div class="line3-carousel-circles">
				</div>
			</div>

			<a href="<?= Url::to(['reviews/index']) ?>" class="line3-leaveReview">Оставить отзыв</a>
		</div>
	</div>
</div>
<!-- END OF LINE3 -->
<?php endif; ?>