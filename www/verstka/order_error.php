<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("");
?>
<div class="page_basket">
	<div class="page_basket_message">
		<div class="page_basket_message_img">
			<img src="/verstka/img/svg/error.svg" />
		</div>
		<div class="page_basket_message_title red">
			что-то пошло не так
		</div>
		<p>Попробуйте перейти к оплате<br/>из корзины ещё раз, пожалуйста</p>
		<div class="buttons_box center">
			<a href="basket.php" class="btn_n">В корзину</a>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
