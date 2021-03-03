<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;

$goodBasketId = htmlspecialchars($_GET["itemBasketId"]);
$siteId = htmlspecialchars($_GET["siteId"]);
$newCount = htmlspecialchars((int)$_GET["count"]);

if(!$goodBasketId)
  return false;
if($newCount < 1)
  return false;
if(!$siteId)
  $siteId = "s1";

$UNIQUECODE = false;
$arBasketItems = array();
$dbBasketItems = CSaleBasket::GetList(
  array(),
  array(
    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
    "ORDER_ID" => "NULL",
    "LID" => SITE_ID
    ),
  false,
  false,
  array()
);
while ($arItems = $dbBasketItems->GetNext()) {
    if($goodBasketId != $arItems["ID"]){
       continue;
     }
    $arItems["PROPS"] = Array();
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "UNIQUE_CODE"));
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
      if($arProp["CODE"] == "UNIQUE_CODE"){
        $UNIQUECODE = $arProp["VALUE"];
      }
    }
}

if(!$UNIQUECODE)
  return false;

$dbBasketItems = CSaleBasket::GetList(
  array(),
  array(
    "FUSER_ID" => CSaleBasket::GetBasketUserID(),
    "ORDER_ID" => "NULL",
    "LID" => SITE_ID
    ),
  false,
  false,
  array()
);
while ($arItems = $dbBasketItems->GetNext()) {
    $arItems["PROPS"] = Array();
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "UNIQUE_CODE"));
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
      if($arProp["CODE"] == "UNIQUE_CODE" && $arProp["VALUE"] == $UNIQUECODE){
        $arFields = array(
          "QUANTITY" => $newCount,
       );
       CSaleBasket::Update($arItems["ID"], $arFields);
      }
    }
}

recalculateLicensePrice();