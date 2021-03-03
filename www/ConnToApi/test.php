<?php
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
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

$basketItems = array();
$rsBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => 459));
while ($arBasketItem = $rsBasketItems->Fetch()) {
  $arBasketItem["PROPS"] = Array();
  $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arBasketItem["ID"]));
  while($arProp = $dbProp -> GetNext()){
   $arBasketItem["PROPS"][] = $arProp;
  }
  $basketItems[] = $arBasketItem;
}
$basketItems = sortOrder($basketItems);
print_r($basketItems);
exit();
/*
define("LICENSE_IBLOCK_ID", 35);
define("GOODS_IBLOCK_ID", 30);
define("MODULES_IBLOCK_ID", 32);



CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
*/
$rez = basketInfo();
print_R($rez);
exit();
//print_R($rez);
