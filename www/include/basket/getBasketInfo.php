<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

define('PRODUCTS_IBLOCK_RU', 30);
define('MODULES_IBLOCK_RU', 32);


if(!CModule::IncludeModule("iblock"))
  return; 
if(!CModule::IncludeModule("sale"))
  return; 


$sectionID = isset($_GET["sectionID"]) ? $_GET["sectionID"] : false ;
$siteID = isset($_GET["siteID"]) ? $_GET["siteID"] : "s1" ;

define('SITE_ID_FOR_BASKET', $siteID);

$rez = basketInfo();
\Bitrix\Main\Diag\Debug::dumpToFile($rez, '', '_logBasketInfo1.log');
if($sectionID){
  $defaultData = defaultSectionData($sectionID);
  foreach($defaultData as $key => $value){
    if(!isset($rez[$key])){
      $rez[$key] = $value;
    }
  }
}
\Bitrix\Main\Diag\Debug::dumpToFile($rez, '', '_logBasketInfo2.log');

header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;

