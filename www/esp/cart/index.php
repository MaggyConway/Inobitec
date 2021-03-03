<?
define("HIDE_SIDEBAR", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//LocalRedirect("/eng/");
$APPLICATION->SetAdditionalCSS("/bitrix/templates/main/template_styles.css");

$APPLICATION->SetTitle("CARRO");
$no_f_links = $cartPreloader = true;
?>
  
<?$APPLICATION->includeComponent("inobitek:sale.basket.basket","",array());?>
  
  
  <?
  /*
  $APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "", Array(
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "PRICE",
			3 => "QUANTITY",
			4 => "SUM",
			5 => "PROPS",
			6 => "DELETE",
			7 => "DELAY",
		),
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"PATH_TO_ORDER" => "/personal/order/make/",	// Страница оформления заказа
		"HIDE_COUPON" => "N",	// Спрятать поле ввода купона
		"QUANTITY_FLOAT" => "N",	// Использовать дробное значение количества
		"PRICE_VAT_SHOW_VALUE" => "Y",	// Отображать значение НДС
		"TEMPLATE_THEME" => "site",	// Цветовая тема
		"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
		"AJAX_OPTION_ADDITIONAL" => "",
		"OFFERS_PROPS" => array(	// Свойства, влияющие на пересчет корзины
			
		)
	),
	false
);*/
    ?>
     <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
   