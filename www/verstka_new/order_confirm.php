<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("");
?>
<div class="page_basket">
	<div class="page_basket_message">
		<div class="page_basket_message_img">
			<img src="/verstka/img/svg/checkbox.svg" />
		</div>
		<div class="page_basket_message_title">
			ВАШ ЗАКАЗ оплачен
		</div>
		<p>Спасибо!<br/>мЫ СВЯЖЕМСЯ С ВАМИ В БЛИЖАЙШЕЕ ВРЕМЯ</p>
		<div class="buttons_box center">
			<a href="lk.php" class="btn_n big">Личный кабинет</a>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
