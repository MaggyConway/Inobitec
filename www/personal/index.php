<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Лицензионные ключи / Личный кабинет / Инобитек");
global $USER;
if (!$USER->IsAuthorized()) {
  (SITE_ID == 's1') ? LocalRedirect("/login/?returnUrl=/personal/") : LocalRedirect("/eng/login/?returnUrl=/eng/personal/");
}
//$rsUser = CUser::GetByID($USER->GetID());
//$arUser = $rsUser->Fetch();
//if($arUser["LID"] != SITE_ID){
//  if(SITE_ID == 's1')
//    LocalRedirect("/eng/personal/");
//  else
//    LocalRedirect("/personal/");
//}

//$APPLICATION->SetAdditionalCSS("/verstka_new/keys/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Личный кабинет");
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
	<!--div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1"-->
		<?$APPLICATION->IncludeComponent("inobitek:user.orders.list", "", array());?>
	<!--/div-->
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
