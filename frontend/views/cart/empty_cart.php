<?php 
use yii\helpers\Url;
?>

<span class="empty-cart">В вашей корзине пусто, самое время сделать крутой принт!</span>
<div class="empty-links-container clearfix">	
	<a href="<?= Url::to(['constructor/']) ?>" class="empty-link empty-link-left">Сделать принт</a>
	<a href="<?= Url::to(['/']) ?>" class="empty-link empty-link-right">Вернуться на главную</a>
</div>