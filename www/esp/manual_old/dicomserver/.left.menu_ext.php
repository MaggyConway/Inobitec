<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;
$aMenuLinksExt = array();

if(CModule::IncludeModule('iblock'))
{
   $aMenuLinksExt = $APPLICATION->IncludeComponent("inobitek:menu.sections", "", array(
    "IS_SEF" => "Y",
    "SEF_BASE_URL" => "",
    "SECTION_PAGE_URL" => "",
    "DETAIL_PAGE_URL" => "",
    "IBLOCK_TYPE" => "manual_eng",
    "IBLOCK_ID" => 24,
    "DEPTH_LEVEL" => "3",
    "CACHE_TYPE" => "N",
 ), false, Array('HIDE_ICONS' => 'Y'));
}

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>