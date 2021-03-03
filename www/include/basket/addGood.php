<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;

$productId = htmlspecialchars($_GET["productID"]);
$siteId = htmlspecialchars($_GET["siteId"]);
if(!$siteId)
  $siteId = "s1";

$data = getProductInfo($productId, $siteId);
$data["productID"] = $productId;
$data["uniqueCode"] = htmlspecialchars($_GET["uniqueCode"]);
$data["parenId"] = htmlspecialchars($_GET["parenId"]);
$data["productKey"] = htmlspecialchars($_GET["productKey"]);

if(!$data["price"])
  return false;
if(!$data["product"])
  return false;

//$rez = array("itemBasketId" => 7);


$rez = addGood($data);
recalculateLicensePrice();
//print_r($rez);
//exit;


header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;

function addGood($data){
  if(checkIfModule($data["product"]["IBLOCK_ID"])){
    return addModuleToBasket($data["productID"],$data["parenId"],$data);
  }elseif(checkIfServer($data["productID"])){
    return addGoodServer($data["productID"],$data["uniqueCode"],$data);
  }elseif(checkIfHasModules($data["product"]["ID"])){
    return addGoodWithModules($data["productID"],$data["uniqueCode"],$data);
  }else{
    return addDefaultGood($productId,$data);
  }
}






function addGoodWithModules($productId, $uniqueCode, $data){
  if(!$productId)
    return false;
  if(!$uniqueCode)
    return false;
  
  
  
  
  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRODUCT_PRICE_ID" => 1,
    "PRICE" => $data["price"]["PRICE"],
    "CURRENCY" => $data["price"]["CURRENCY"],
    "QUANTITY" => 1,
    "LID" => LANG,
    "DELAY" => "N",
    "CAN_BUY" => "Y",
    "NAME" => $data["product"]["NAME"],
  );

  $arProps = array();

  $arProps[] = array(
    "NAME" => "Уникальный код",
    "CODE" => "UNIQUE_CODE",
    "VALUE" => $uniqueCode
  );
  $arProps[] = array(
    "NAME" => "Англ название товара",
    "CODE" => "NAME_EN",
    "VALUE" => $data["product"]["PROPERTY_NAME_EN_VALUE"],
  );


  $arFields["PROPS"] = $arProps;

  $elemID = CSaleBasket::Add($arFields);
  return array("itemBasketId" => $elemID);
  
}

function addModuleToBasket($productId,$parentId,$data){
  if(!$productId)
    return false;
  if(!$parentId)
    return false;
  //$info = getProductInfo($productId);

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
  $quanity = 1;
  while ($arItems = $dbBasketItems->GetNext()) {
    $arItems["PROPS"] = Array();
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "UNIQUE_CODE"));
    while($arProp = $dbProp -> GetNext()){
      if($arProp["CODE"] == "UNIQUE_CODE" && $arProp["VALUE"] == $parentId && $arItems["PRODUCT_ID"] == $productId)
        return array("error" => "Для данной лицензии уже добавлен такой модуль");
      if($arProp["CODE"] == "UNIQUE_CODE" && $arProp["VALUE"] == $parentId)
        $quanity = $arItems["QUANTITY"];
      
    }
  }
  
  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRODUCT_PRICE_ID" => 1,
    "PRICE" => $data["price"]["PRICE"],
    "CURRENCY" => $data["price"]["CURRENCY"],
    "QUANTITY" => $quanity,
    "LID" => LANG,
    "DELAY" => "N",
    "CAN_BUY" => "Y",
    "NAME" => $data["product"]["NAME"],
  );

  $arProps = array();

  $arProps[] = array(
    "NAME" => "Уникальный код",
    "CODE" => "UNIQUE_CODE",
    "VALUE" => $parentId
  );
  $arProps[] = array(
    "NAME" => "Англ название товара",
    "CODE" => "NAME_EN",
    "VALUE" => $data["product"]["PROPERTY_NAME_EN_VALUE"],
  );
  $arFields["PROPS"] = $arProps;
  $elemID = CSaleBasket::Add($arFields);
  return array("itemBasketId" => $elemID);
}

function addDefaultGood($productId, $data){
  
    $arFields = array(
      "PRODUCT_ID" => $productId,
      "PRODUCT_PRICE_ID" => 1,
      "PRICE" => $data["price"]["PRICE"],
      "CURRENCY" => $data["price"]["CURRENCY"],
      "QUANTITY" => 1,
      "LID" => LANG,
      "DELAY" => "N",
      "CAN_BUY" => "Y",
      "NAME" => $data["product"]["NAME"],
    );
    $elemID = CSaleBasket::Add($arFields);
    return array("itemBasketId" => $elemID);
  
}



function addGoodServer($productId, $uniqueCode, $data){
  if(!$data["productKey"])
    return false;
  if(!preg_match("/^([0-9]{4}-){3}[0-9]{4}$/", $data["productKey"]))
    return false;
  $existCode = checkIfExistCompCode($data["productKey"]);
  if($existCode["existCode"])
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
  while ($arItem = $dbBasketItems->GetNext()) {
    $arrParentID = CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
    if(is_array($arrParentID)){
      return array("error" => "Уже добавлен сервер");
    }
  }
  $arFields = array(
    "PRODUCT_ID" => $productId,
    "PRODUCT_PRICE_ID" => 1,
    "PRICE" => $data["price"]["PRICE"],
    "CURRENCY" => $data["price"]["CURRENCY"],
    "QUANTITY" => 1,
    "LID" => LANG,
    "DELAY" => "N",
    "CAN_BUY" => "Y",
    "NAME" => $data["product"]["NAME"],
  );

  $arProps = array();

  $arProps[] = array(
    "NAME" => "Уникальный код",
    "CODE" => "UNIQUE_CODE",
    "VALUE" => $uniqueCode
  );
  $arProps[] = array(
    "NAME" => "Код компьютера",
    "CODE" => "PRODUCT_CODE",
    "VALUE" => $data["productKey"]
  );
  $arProps[] = array(
    "NAME" => "Англ название товара",
    "CODE" => "NAME_EN",
    "VALUE" => $data["product"]["PROPERTY_NAME_EN_VALUE"],
  );
  $arFields["PROPS"] = $arProps;

  $elemID = CSaleBasket::Add($arFields);
  return array("itemBasketId" => $elemID);
}
?>
