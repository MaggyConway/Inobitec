<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$arrModulesBlockIDs = array(32,33);



//$goodBasketId = 1;
$goodBasketId = $_GET["itemBasketId"];



if(!$goodBasketId)
  return false;

$mainGood = false;
if(!CModule::IncludeModule("iblock"))
  return; 
if(!CModule::IncludeModule("sale"))
  return;

$delItem = CSaleBasket::GetByID($goodBasketId);
if(!is_array($delItem))
  return array("error"  => "Нет такого товара в корзине");
$delItemUniqueCode = false;
$dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $delItem["ID"], "CODE" => "UNIQUE_CODE"));
while($arProp = $dbProp -> GetNext()){
  $arItems["PROPS"][] = $arProp;
  if($arProp["CODE"] == "UNIQUE_CODE"){
    $delItemUniqueCode = $arProp["VALUE"];
  }
}


$productInfo = GetElementSectionsID($delItem["PRODUCT_ID"]);
$sectionType = checkSectionType($productInfo[0], $productInfo[1]);

$resGoods = CIBlockElement::GetList(
    array('sort' => 'asc'),
    array('IBLOCK_ID' => $productInfo[1], "ID" => $delItem["PRODUCT_ID"]),
    false,
    false,
    array('ID', "NAME", "PROPERTY_LIC_TYPE")
);
$obGood = $resGoods->getNext();

if( (checkIfServer($delItem["PRODUCT_ID"]) || $sectionType == "dicomviewer" || $sectionType == "dicomserver") && !checkIfLicense($obGood) ){

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
      $arItems["PROPS"] = Array();
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "UNIQUE_CODE"));
      while($arProp = $dbProp -> GetNext()){
        $arItems["PROPS"][] = $arProp;
        if($arProp["CODE"] == "UNIQUE_CODE" && $arProp["VALUE"] == $delItemUniqueCode){
          CSaleBasket::Delete($arItems["ID"]);
        }
      }
  }
}
CSaleBasket::Delete($goodBasketId);
recalculateLicensePrice(true);
$rez = array("success" => $goodBasketId);
header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;