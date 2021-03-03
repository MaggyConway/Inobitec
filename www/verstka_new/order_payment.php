<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("");
?>
<div class="page_basket">
	<div class="page_basket_message">
		<div class="page_basket_message_img">
			<img src="/verstka/img/svg/rub_blue.svg" />
		</div>
		<div class="page_basket_message_title">
			Ждём оплату
		</div>
		<p>счёт действителен в течение<br/>5 банковских дней</p>
		<p>СПАСИБО ЗА ЗАКАЗ</p>
		<div class="buttons_box center">
			<a href="#" class="btn_n red big">скачать PDF счёт</a>
			<a href="#" class="btn_n">Наши продукты</a>
		</div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
