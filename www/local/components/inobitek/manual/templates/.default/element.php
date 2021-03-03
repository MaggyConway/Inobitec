<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
/*
if($arResult["VARIABLES"]["SECTION_CODE"]){
  $arResult["VARIABLES"]['SECTION_ID'] = CIBlockFindTools::GetSectionID( $arResult['VARIABLES']['SECTION_ID'], $arResult['VARIABLES']['SECTION_CODE'], array('IBLOCK_ID' => $arParams['IBLOCK_ID']) );
}
if($arResult["VARIABLES"]["ELEMENT_CODE"]){
  $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], '=CODE' => $arResult["VARIABLES"]["ELEMENT_CODE"], 'SECTION_ID' => $arResult["VARIABLES"]['SECTION_ID'] );
  
  $rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID"));
  if ($arElement = $rsElement->Fetch())
      $arResult["VARIABLES"]['ELEMENT_ID'] = $arElement["ID"];
  
  
}*/


if($arResult["VARIABLES"]["ELEMENT_ID"]){
		
	$preElement = CIBlockElement::GetByID($arResult["VARIABLES"]["ELEMENT_ID"]);

	if($element = $preElement -> GetNext()){
      if($element["ACTIVE"] == "Y"){
		echo $element['DETAIL_TEXT'];

		$APPLICATION -> SetPageProperty('h1', $element['NAME']);
		$APPLICATION -> SetPageProperty('title', $element['NAME']);
      }else{
        require "404.php";
      }

	}else{
        require "404.php";
      }
}else{
    require "404.php";
  }
?>

