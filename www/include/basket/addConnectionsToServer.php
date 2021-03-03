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
$licenseId = isset($_GET["licenseId"]) ? $_GET["licenseId"] : false ;
$conn = isset($_GET["conn"]) ? $_GET["conn"] : false ;
$siteID = isset($_GET["siteID"]) ? $_GET["siteID"] : "s1" ;
define('SITE_ID_FOR_BASKET', $siteID);

if(!$conn || !$productId || !$licenseId){
  $rez = array("error" => "Переданны не все данные");
}else{
  $rez = addConnectionsToServer($licenseId,$conn, $productId);
}
header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;



function addConnectionsToServer($licID, $conn, $productId){
  global $USER;
   $resUpdateLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "PROPERTY_USER_ID" => $USER->GetID(), 'ACTIVE' => "Y"),
              false,
              false,
              array('ID', 'PROPERTY_LICENSE_ID')
          );
  $arrLicenseWithUpdates = array();
  while($obLicenseWithUpdates = $resUpdateLicense->getNext()){
    $arrLicenseWithUpdates[] = $obLicenseWithUpdates['PROPERTY_LICENSE_ID_VALUE'];
  }
  
  if(in_array($licID, $arrLicenseWithUpdates))
    return array("error" => "This license alredy have updates");
  
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
  
  while ($arrItems = $dbBasketItems->GetNext()) {
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arrItems["ID"], "CODE" => "LICENSE_ID", 'CONNECTIONS'));
    $basketItemCode = false;
    $basketItemConn = false;
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
      if($arProp["CODE"] == "LICENSE_ID"){
          $basketItemCode = $arProp["VALUE"];
      }
      if($arProp["CODE"] == "CONNECTIONS")
        $basketItemConn = $arProp["VALUE"];
    }
  }
  
  if($basketItemCode == $licID && $basketItemConn)
    return array("error" => "Already in basket");
  
  
  
  $availableLicenseTypes = array("00");
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_USER_ID" => $USER->GetID(), 'ID' => $licID),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_LICENSE_KEY", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_LICENSE_CODE", "PROPERTY_NEXT_LICENSE_ID")
          );
  $obLicense = $resLicense->getNext();
  if(!$obLicense)
    return array("error" => "No such product for update");
  
  if(!in_array($obLicense["PROPERTY_LICENSE_CODE_VALUE"], $availableLicenseTypes))
      return array("error" => "License have unavailable type");
  
  if($obLicense["PROPERTY_NEXT_LICENSE_ID_VALUE"] && $obLicense["PROPERTY_NEXT_LICENSE_ID_VALUE"] > 0)
      return array("error" => "License have been updated unavailable type");
  
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
    return array("error" => "Its not an license update", '11' => $obGood["PROPERTY_LIC_TYPE_VALUE"], '$productId' => $productId);
  }
  
  if(!$obLicense["PROPERTY_LICENSE_KEY_VALUE"]){
     return array("error" => "No key for license");
  }
  
  $obPrice = CPrice::GetBasePrice($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
  $calcPrice = $obPrice["PRICE"] * $conn;
  
 
  
  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRICE" => $calcPrice,
    "QUANTITY" => 1,
    "LID" => LANG,
    "CAN_BUY" => "Y",
    "CURRENCY" => "RUB",
    "NAME" => "Дополнительные подключения для Inobitec DICOM Server: +" . $conn,
    "CALLBACK_FUNC" => "",
    "MODULE" => "",
    "NOTES" => "",
    "ORDER_CALLBACK_FUNC" => "",
  );
 
  $arProps = array();

  $arProps[0] = array(
    "NAME" => "Дополнительные подключения",
    "CODE" => "CONNECTIONS",
    "VALUE" => $conn,
  );
  
  $arProps[] = array(
    "NAME" => "Англ название товара",
    "CODE" => "NAME_EN",
    "VALUE" => "Additional connecntions for Inobitec DICOM Server: +" . $conn,
  );
  $arProps[] = array(
    "NAME" => "Ид связанной лицензии",
    "CODE" => "LICENSE_ID",
    "VALUE" => $licID
  );
  $arProps[] = array(
    "NAME" => "Стоимость одного подключения (руб)",
    "CODE" => "ADDITIONALCONSPRICE",
    "VALUE" => $obPrice["PRICE"],
  );


  $arFields["PROPS"] = $arProps;
 
  
  $elemId =  CSaleBasket::Add($arFields);
  recalculateLicensePrice();
  
  $calcPrice_EN = CCurrencyRates::ConvertCurrency($obPrice["PRICE"], "RUB", "USD");
  $calcPrice_EN = number_format(floor($calcPrice_EN), 0, '', '') * $conn;
  
  $calcPrice = (SITE_ID_FOR_BASKET == 's1')  ? $calcPrice : $calcPrice_EN;
  
  return array("itemBasketId" => $elemId, "price" => $calcPrice);
          
}
