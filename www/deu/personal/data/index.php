<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "PERSÖNLICHE DATEN FEEDBACK / Mein Konto / Inobitec");
global $USER;
if (!$USER->IsAuthorized()) {
  LocalRedirect("/deu/login/?returnUrl=/deu/personal/data/");
}

$APPLICATION->SetTitle("Mein Konto");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
?>
<div class="page_lk">
	<?$APPLICATION->IncludeComponent("bitrix:menu","personal_menu",Array(
	        "ROOT_MENU_TYPE" => "personal", 
	        "MAX_LEVEL" => "1",  
	        "USE_EXT" => "N",
	        "DELAY" => "N",
	        "ALLOW_MULTI_SELECT" => "N",
	        "MENU_CACHE_TYPE" => "N", 
	        "MENU_CACHE_TIME" => "3600", 
	        "MENU_CACHE_USE_GROUPS" => "N", 
	        "MENU_CACHE_GET_VARS" => "" 
    	)
	);?>
	<div class="page_basket_personal_info_type_tab" style="display: block;" data-target="2">
		<?$APPLICATION->includeComponent("inobitek:user.personal","",array());?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>