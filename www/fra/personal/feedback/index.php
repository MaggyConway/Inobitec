<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Feedback / Personal Area / Inobitec");
global $USER;
if (!$USER->IsAuthorized()) {
  (SITE_ID == 's1') ? LocalRedirect("/login/?returnUrl=/personal/feedback/") : LocalRedirect("/eng/login/?returnUrl=/eng/personal/feedback/");
}

$APPLICATION->SetTitle("Espace personnel");
$APPLICATION->SetAdditionalCSS("/verstka_new/css/main.css");
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
    <div class="page_basket_personal_info_type_tab active" data-target="3">
        <div class="contact-us">
            <?$APPLICATION->IncludeComponent(
	"inobitek:feedback.form", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"EMAIL_TO" => "voxweb@gmail.com, market@inobitec.com"
	),
	false
);?>
            <div class="contact-us__contacts">
                <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "file", 
                    "PATH" => "include/phone.php"
                    )
                );?>
                <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "file", 
                    "PATH" => "include/email.php"
                    )
                );?>
                <p class="contact-us__contacts-address">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                        "AREA_FILE_SHOW" => "file", 
                        "PATH" => "include/address.php"
                        )
                    );?>
                </p>
            </div>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>