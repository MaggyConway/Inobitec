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
$years = isset($_GET["years"]) ? $_GET["years"] : false ;

if(!$years || !$productId || !$licenseId){
  $rez = array("error" => "Переданны не все данные");
}else{
  $rez = addLicenseUpdate($licenseId,$years, $productId);
}
header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;



function addLicenseUpdate($licID, $years, $productId){
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
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arrItems["ID"], "CODE" => "LICENSE_ID", "YEARS"));
    $basketItemCode = false;
    $basketItemYears = false;
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
      if($arProp["CODE"] == "LICENSE_ID"){
          $basketItemCode = $arProp["VALUE"];
      }
      if($arProp["CODE"] == "YEARS"){
          $basketItemYears = $arProp["VALUE"];
      }
    }
  }
  
  if($basketItemCode == $licID && $basketItemYears)
    return array("error" => "Already in basket");
  
  
  
  $availableLicenseTypes = array("05", "10", "00");
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
    return array("error" => "Передано не продление лицензии");
  }
  
  if(!$obLicense["PROPERTY_LICENSE_KEY_VALUE"]){
     return array("error" => "No key for license");
  }
  
  
  
  
  
  if(!$obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"])
      return array("error" => "Uncorrect time ");
  $dtime = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
  $datetime1 = new DateTime();
  $interval = $dtime->diff($datetime1);
  $yearDif = $interval->format('%y');
  
  $availableYears = 0;
  if($yearDif >= 2  && $interval->invert == 1)
    $availableYears = 0;
  elseif($yearDif >= 1  && $interval->invert == 1)
    $availableYears = 1;
  elseif($yearDif >= 0 && $interval->invert == 1)
    $availableYears = 2;
  else{
    $availableYears = 3;
  }
  
  if($years > $availableYears)
    return array("error" => "To many years for update ", "years" => $years, "yearsDif" => $availableYears, "licID" => $licID, "dtime" => $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
  
  
  $calcPrice = $years* (0.3-(($years-1)*0.05)) * calcPriceForLicenseUpdate($obLicense);
  
  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRICE" => $calcPrice,
    "QUANTITY" => 1,
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
    "NAME" => "Ид связанной лицензии",
    "CODE" => "LICENSE_ID",
    "VALUE" => $licID
  );


  $arFields["PROPS"] = $arProps;
 
  $elemId =  CSaleBasket::Add($arFields);
  recalculateLicensePrice();
  return array("itemBasketId" => $elemId, "price" => $calcPrice);
          
}



