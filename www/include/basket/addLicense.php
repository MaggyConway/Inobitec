<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

if (!CModule::IncludeModule("sale"))
  return false;

if(!CModule::IncludeModule("iblock"))
  return false;

if(!CModule::IncludeModule("catalog"))
  return false;

$productId = isset($_GET["productId"]) ? $_GET["productId"] : false ;
$basketItemId = isset($_GET["basketItemId"]) ? $_GET["basketItemId"] : false ;
$years = isset($_GET["years"]) ? $_GET["years"] : false ;



if(!$years || !$productId){
  $rez = array("error" => "Переданны не все данные");
}else{
  $rez = addLicense($basketItemId,$years, $productId);
}
header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;

function addLicense($basketItemId,$years, $productId){
  $availableYears = array(1,2);
  
  
  $obProductInfo = CIBlockElement::GetByID($productId);
  $productInfo = $obProductInfo->getNext();
  $resGoods = CIBlockElement::GetList(
      array('sort' => 'asc'),
      array('IBLOCK_ID' => $productInfo["IBLOCK_ID"], "ID" => $productInfo["ID"]),
      false,
      false,
      array('ID', "NAME", "PROPERTY_LIC_TYPE", "PROPERTY_NAME_EN")
  );
  $obGood = $resGoods->getNext();
  if(!$obGood["PROPERTY_LIC_TYPE_VALUE"] || $obGood["PROPERTY_LIC_TYPE_VALUE"] != 'license'){
    return array("error" => "Передано не продление лицензии");
  }
  
  
  if(!in_array($years,$availableYears)){
    return array("error" => "Недопустимое чилсо лет");
  }
  $arrItem = CSaleBasket::GetByID($basketItemId);
  if(!checkIfGoodhasLic($arrItem["PRODUCT_ID"])){
    return array("error" => "Не является товаром");
  }
  $UNIQUECODE = false;
  $quanity = 1;
  $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arrItem["ID"], "CODE" => "UNIQUE_CODE"));
  while($arProp = $dbProp -> GetNext()){
    $arItems["PROPS"][] = $arProp;
    if($arProp["CODE"] == "UNIQUE_CODE"){
      $UNIQUECODE = $arProp["VALUE"];
    }
  }
  $quanity = $arrItem["QUANTITY"];
  if(!$UNIQUECODE){
    return array("error" => "Не прсотавлен уникальный код");
  }
  
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
  
  $price = 0;
  
  
  while ($arrItems = $dbBasketItems->GetNext()) {
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arrItems["ID"], "CODE" => "UNIQUE_CODE"));
    $basketItemCode = false;
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
      if($arProp["CODE"] == "UNIQUE_CODE"){
          $basketItemCode = $arProp["VALUE"];
      }
    }
    if($basketItemCode != $UNIQUECODE)
      continue;
    $obProductInfo = CIBlockElement::GetByID($arrItems["PRODUCT_ID"]);
    $productInfo = $obProductInfo->getNext();
    $resGoods = CIBlockElement::GetList(
        array('sort' => 'asc'),
        array('IBLOCK_ID' => $productInfo["IBLOCK_ID"], "ID" => $productInfo["ID"]),
        false,
        false,
        array('ID', "NAME", "PROPERTY_LIC_TYPE", "PROPERTY_NAME_EN")
    );
    $obGoodBasket = $resGoods->getNext();
    if($obGoodBasket["PROPERTY_LIC_TYPE_VALUE"] && $obGoodBasket["PROPERTY_LIC_TYPE_VALUE"] == 'license'){
      return array("error" => "Для этого товара уже добавлено продление");
    }else{
      $price += $arrItems["PRICE"];
    }
  }
  $calcPrice = $years* (0.3-(($years-1)*0.05)) * $price;

  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRICE" => $calcPrice,
    "QUANTITY" => $quanity,
    "LID" => LANG,
    "CAN_BUY" => "Y",
    "CURRENCY" => "RUB",
    "NAME" => $obGood["NAME"],
    "CALLBACK_FUNC" => "",
    "MODULE" => "",
    "NOTES" => "",
    "ORDER_CALLBACK_FUNC" => "",
  );
 
  $arProps = array();

  $arProps[0] = array(
    "NAME" => "Увеличение срока действия подписки на (кол-во лет)",
    "CODE" => "YEARS",
    "VALUE" => $years,
  );
  
  $arProps[] = array(
    "NAME" => "Англ название товара",
    "CODE" => "NAME_EN",
    "VALUE" => $obGood["PROPERTY_NAME_EN_VALUE"],
  );
  $arProps[] = array(
    "NAME" => "Уникальный код",
    "CODE" => "UNIQUE_CODE",
    "VALUE" => $UNIQUECODE
  );


  $arFields["PROPS"] = $arProps;
 
  $elemId =  CSaleBasket::Add($arFields);
  return array("itemBasketId" => $elemId);
  
}





function chekIfNotInBasket($productId){
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
  while ($arItem = $dbBasketItems->GetNext()) {
    if($arItem["PRODUCT_ID"] == $productId)
      return false;
      
  }
  return true;
}

 

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

