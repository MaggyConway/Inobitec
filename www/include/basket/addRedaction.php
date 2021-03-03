<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
define('PRODUCTS_IBLOCK_RU', 30);
define('MODULES_IBLOCK_RU', 32);


if(!CModule::IncludeModule("iblock"))
  return; 
if(!CModule::IncludeModule("sale"))
  return; 

$sectionID = isset($_GET["sectionID"]) ? $_GET["sectionID"] : false ;
$siteID = isset($_GET["siteID"]) ? $_GET["siteID"] : "s1" ;

define('SITE_ID_FOR_BASKET', $siteID);

if(!$sectionID)
  return false;

$rezult = defaultSectionData($sectionID);

header('Content-Type: application/json');
echo json_encode($rezult, JSON_HEX_QUOT | JSON_HEX_TAG);