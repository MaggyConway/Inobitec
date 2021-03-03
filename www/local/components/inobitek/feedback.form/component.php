<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $USER;

if (strlen($arParams["EMA"])) {
	# code...
}

if ($USER->IsAuthorized()) {
	$arResult["USER"] = CUser::GetById($USER->GetID())->Fetch();
	$arResult["USER"]["FULL_NAME"] = $USER->GetFullName();
    global $USER_FIELD_MANAGER;
    $arFields = $USER_FIELD_MANAGER->GetUserFields("USER");
    $arResult["USER_FIELDS"] = $arFields;
    $obEnum = new CUserFieldEnum;
    foreach ($arResult["USER_FIELDS"] as $field) {
        if ($field["USER_TYPE_ID"] == "enumeration") {
            $rsEnum = $obEnum->GetList(array(), array("USER_FIELD_ID" => $field["ID"]));
            while($arEnum = $rsEnum->GetNext()){
                $arResult["ENUMS"][$field["FIELD_NAME"]][$arEnum["ID"]] = $arEnum;
            }
        }
    }
    
    $typeId = $arResult["ENUMS"]["UF_USER_TYPE"][$arResult['USER']['UF_USER_TYPE']]["XML_ID"];
    if ($typeId == 'legal_entity'){
      $arResult["USER"]["NAME"] = $arResult["USER"]["UF_COMPANY_NAME"];
    }
    
}
if ($_POST['send'] == 'Y') 
{
	$text = htmlspecialchars($_POST['text']);
	$email = htmlspecialchars($_POST['EMAIL']);
	$arEvent = array(
		'AUTHOR' => $arResult['USER']['FULL_NAME'],
		'AUTHOR_EMAIL' => $arResult['USER']['EMAIL'],
		'TEXT' => $text,
		'EMAIL_TO' => $email
	);
	$SITE_ID = trim($_POST['site']);
	$arSite = CSite::GetByID($SITE_ID)->Fetch();
	
	CModule::IncludeModule("form");
    $formID = ($SITE_ID == 's1') ? 1 : 2;
    if($SITE_ID == 's1'){
      $dataForMessage = Array(
            "form_text_1" => $arResult['USER']['FULL_NAME'],
            "form_text_2" => "  ",
            "form_text_3" => $arResult['USER']['PERSONAL_PHONE'],
            "form_email_4" => $arResult['USER']['EMAIL'],
            "form_textarea_5" => $text,
        );
    }else{
      $dataForMessage = Array(
            "form_text_11" => $arResult['USER']['FULL_NAME'],
            "form_text_12" => "  ",
            "form_text_13" => $arResult['USER']['PERSONAL_PHONE'],
            "form_email_14" => $arResult['USER']['EMAIL'],
            "form_textarea_15" => $text,
          );
    }
    
    if ($RESULT_ID = CFormResult::Add($formID, $dataForMessage))
    {
        //echo json_encode("Результат #".$RESULT_ID." успешно создан");
        //die();
    }
    else
    {
        global $strError;
        echo json_encode(array('error' => $strError));
        die();
    }  
	
	CEvent::Send('FEEDBACK_FORM', $SITE_ID, $arEvent, 'N', "", array(), $arSite['LANGUAGE_ID']);
	echo json_encode($arEvent);
	die();
}
$this->IncludeComponentTemplate();
?>
