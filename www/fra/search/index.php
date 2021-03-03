<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Chercher");
?><?$APPLICATION->IncludeComponent(
	"bitrix:search.page",
	"custom",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_SHADOW" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DEFAULT_SORT" => "rank",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FILTER_NAME" => "",
		"NO_WORD_LOGIC" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "custom_search",
		"PAGER_TITLE" => "Результаты поиска",
		"PAGE_RESULT_COUNT" => "15",
		"RESTART" => "N",
		"SHOW_ITEM_DATE_CHANGE" => "N",
		"SHOW_ITEM_TAGS" => "N",
		"SHOW_ORDER_BY" => "N",
		"SHOW_TAGS_CLOUD" => "N",
		"SHOW_WHEN" => "N",
		"SHOW_WHERE" => "N",
		"USE_LANGUAGE_GUESS" => "N",
		"USE_SUGGEST" => "N",
		"USE_TITLE_RANK" => "Y",
		"arrFILTER" => array("iblock_information_fr","iblock_products_fr","iblock_services_fr"),
		"arrFILTER_iblock_goods" => array(0=>"30",),
		"arrFILTER_iblock_information_fr" => array("all"),
		"arrFILTER_iblock_manual_fr" => array("all"),
		"arrFILTER_iblock_products_fr" => array("67","69","88"),
		"arrFILTER_iblock_services_fr" => array("all")
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>