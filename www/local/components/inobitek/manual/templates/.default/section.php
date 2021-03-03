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


//print_r($arResult);
if($arResult["VARIABLES"]["SECTION_ID"]){
	$preSection = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]);

	if($section = $preSection -> GetNext()){
      if($section["ACTIVE"] == "Y"){
		echo $section['DESCRIPTION'];

		$APPLICATION -> SetPageProperty('h1', $section['NAME']);
		$APPLICATION -> SetPageProperty('title', $section['NAME']);
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
