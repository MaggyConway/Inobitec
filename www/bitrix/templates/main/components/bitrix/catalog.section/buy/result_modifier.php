<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

/*
$dbSection = CIBlockSection::GetList(Array(), array("ID" => $arResult["VARIABLES"]["SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false ,Array("UF_TYPE"));
if($arSection = $dbSection->GetNext()){ 
   $arResult["SECTION_TYPE"] = $arSection; 
}*/
/** Новый блок для правки тайтла в английской версии */
if (LANGUAGE_ID == 'en') {
	$dbSection = CIBlockSection::GetList(Array(), array("ID" => $arResult["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false ,Array("ID","UF_NAME_EN"));
	if($arSection = $dbSection->GetNext()){ 
	   $arResult['ENG_TITLE'] = $arSection["UF_NAME_EN"];
	   $component->SetResultCacheKeys(array('ENG_TITLE'));
	}
}