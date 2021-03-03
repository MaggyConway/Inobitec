<? 
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if ($USER->IsAuthorized()) {
	$arResult['USER_ID'] = $USER->GetID();
} else {
	return;
}
$arResult['USER'] = CUser::GetById($arResult['USER_ID'])->Fetch();
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
$this->IncludeComponentTemplate();
?>