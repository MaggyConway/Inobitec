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
//print_r($arResult["PROPERTIES"]["ATT_TEMPLATE"]);
//print_r($arResult["PROPERTIES"]["ATT_TEMPLATE"]["VALUE"]);
if($arResult["PROPERTIES"]["ATT_TEMPLATE"]["VALUE"] && file_exists($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/cases/".$arResult["PROPERTIES"]["ATT_TEMPLATE"]["VALUE"].".php")){
  include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/cases/".$arResult["PROPERTIES"]["ATT_TEMPLATE"]["VALUE"].".php");
}else{
  include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/cases/default.php");
}
//include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/viewerTemplate.php");
//print_r($arResult);
?>
