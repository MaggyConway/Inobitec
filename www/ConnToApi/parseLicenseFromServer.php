<?php


/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

define("LICENSE_IBLOCK_ID", 35);
define("GOODS_IBLOCK_ID", 30);
define("MODULES_IBLOCK_ID", 32);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/LicServerClass.php");

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");


$inobitecLicense = new inobitecLicense();
$resUsers = getSiteServerUsers();
$resLicServer = getServerLic($resUsers, $inobitecLicense);
$resLicSite = getOldLicense();
$modulesData = getModulesInfo();
$goodsData = getGoodsInfo();

print_r($resLicSite["offline"]);
print_r("<br><br>");
print_r($modulesData);
print_r("<br><br>");


print_r("ONLINE: <br><br>");
foreach($resLicServer["online"] as $licInfo){
  print_r($licInfo);
  print_r("<br><br>");
  if(isset($resLicSite["online"][$licInfo["id"]])){
    print_r("Update Online");
    print_r("<br><br>");
    updateAlreadyExistLic($resLicSite["online"][$licInfo["id"]], $licInfo, $goodsData, $modulesData);
    unset($resLicSite["online"][$licInfo["id"]]);
  }else{
    print_r("Create Online");
    print_r("<br><br>");
    createNewLic($licInfo, $resUsers[$licInfo["client"]["id"]], $modulesData, $goodsData);
  }
}

print_r("OFFLINE: <br><br>");
foreach($resLicServer["offline"] as $licInfo){ print_r($licInfo);
  if(isset($resLicSite["offline"][$licInfo["id"]])){
    print_r("Update Offline");
    print_r("<br><br>");
    updateAlreadyExistServLic($resLicSite["offline"][$licInfo["id"]], $licInfo, $goodsData, $modulesData);
    unset($resLicSite["offline"][$licInfo["id"]]);
  }else{
    print_r("Create Offline");
    print_r("<br><br>");
    createNewServLic($licInfo, $resUsers[$licInfo["client"]["id"]], $modulesData, $goodsData);
  }
}




// Получаем список лицензий на сайте
function getOldLicense(){
  $arrOldLic = array();
  $arSelectLic = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_LICSERVER_ID","PROPERTY_GOOD_INFO","PROPERTY_MODULES_INFO","PROPERTY_ORDER_ID","PROPERTY_BASKETITEM_ID","PROPERTY_USER_ID","PROPERTY_LICENSE_KEY","PROPERTY_GENERATION_TIME","PROPERTY_ACTIVATION_TIME", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_API_PRODUCT_TYPE");
  $arFilterLic = Array("IBLOCK_ID"=>IntVal(LICENSE_IBLOCK_ID));
  $resLic = CIBlockElement::GetList(Array(), $arFilterLic, false, false, $arSelectLic);
  while($obLic = $resLic->GetNext()){
    print_r($obLic["PROPERTY_API_PRODUCT_TYPE_ENUM_ID"] . "--" . $obLic["PROPERTY_LICSERVER_ID_VALUE"]);
    print_r("<br>");
    if($obLic["PROPERTY_API_PRODUCT_TYPE_ENUM_ID"] == 12)
      $arrOldLic["offline"][$obLic["PROPERTY_LICSERVER_ID_VALUE"]] = $obLic;
    else
      $arrOldLic["online"][$obLic["PROPERTY_LICSERVER_ID_VALUE"]] = $obLic;
  }
  return $arrOldLic;
}

// Получаем список пользователей на сайте которые связаны с сервером
function getSiteServerUsers(){
  $arrSiteServerUsers = array();
  $userParams = array(
      'SELECT' => array("UF_LIC_SERV_ID"),
      'FIELDS' => array(
          'ID',
          'ACTIVE',
          'IS_ONLINE'
      ),
  );
  $rsUsers = CUser::GetList($by="", $order="", array('>UF_LIC_SERV_ID' => 0), $userParams);
  while($obUsers = $rsUsers->GetNext()){ 
    $arrSiteServerUsers[$obUsers["UF_LIC_SERV_ID"]] = $obUsers;
  }
  return $arrSiteServerUsers;
}

// Получаем список лицензий от сервера лицензий
function getServerLic($resUsers, &$inobitecLicense){
  $LicenseArray = array("online"=> array(), "offline" => array());
  foreach($resUsers as $userServerID => $val){
    
    $res = $inobitecLicense->getClientLicenses($userServerID);
    $LicenseArray["online"] = array_merge($LicenseArray["online"], $res["online"]);
    $LicenseArray["offline"] = array_merge($LicenseArray["offline"], $res["offline"]);

  }
  return $LicenseArray;
  
}


function getModulesInfo(){
  $arrModules = array();
  $arSelectMod = Array("ID", "PROPERTY_CODE_FROM_SERVER");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
  $arFilterMod = Array("IBLOCK_ID"=>IntVal(MODULES_IBLOCK_ID));
  $resMod = CIBlockElement::GetList(Array(), $arFilterMod, false, false, $arSelectMod);
  while($obMod = $resMod->GetNext()){
    $arrModules[$obMod["PROPERTY_CODE_FROM_SERVER_VALUE"]] = $obMod["ID"];
  }
  return $arrModules;
}

function getGoodsInfo(){
  $arrGoods = array();
  $arSelectGoods = Array("ID", "PROPERTY_SERVER_LIC_TYPE_CODE");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
  $arFilterGoods = Array("IBLOCK_ID"=>IntVal(GOODS_IBLOCK_ID));
  $resGoods = CIBlockElement::GetList(Array(), $arFilterGoods, false, false, $arSelectGoods);
  while($obGoods = $resGoods->GetNext()){
    if($obGoods["PROPERTY_SERVER_LIC_TYPE_CODE_VALUE"])
      $arrGoods[$obGoods["PROPERTY_SERVER_LIC_TYPE_CODE_VALUE"]] = $obGoods["ID"];
  }
  return $arrGoods;
}

// Обновляем данные по полученным от сервера
function updateAlreadyExistLic($siteData, $licInfo, $goodsData, $modulesData){
  
  print_r($siteData);
  print_r("<br><br>");
  print_r($licInfo);
  print_r("<br><br>");
  print_r($modulesData);
  print_r("<br/><br/>");
  $newGoodModules = array();
  foreach($licInfo["features"] as $feature){
    print_r($feature["id"]);
    print_r("<br/><br/>");
    print_r($modulesData[$feature["id"]]);
    print_r("<br/><br/>");
    $newGoodModules[] = $modulesData[$feature["id"]];
  }
  print_r($licInfo);
  print_r("<br/><br/>");
  $varGenTime = $varActTime = $varSupportEndTime = false;
  if($licInfo["generationDateTime"])
    $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["generationDateTime"])->getTimestamp();
  if($licInfo["activationDateTime"])
    $varActTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["activationDateTime"])->getTimestamp();
  if($licInfo["supportExpirationDate"])
    $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$licInfo["supportExpirationDate"])->getTimestamp();
  $propVal = array(
    "GOOD_INFO" => $goodsData[$licInfo["productName"]],
    "MODULES_INFO" => $newGoodModules,
    "LICENSE_KEY" => $licInfo["key"],
    "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
    "COMP_CODE"       => $licInfo["device"]["key"],
    "API_PRODUCT_NAME"  => $licInfo["productName"],
    "API_PRODUCT_TYPE" => 11, 
    "LICENSE_CODE" =>   $licInfo["type"]["code"],
    "SUPPORTED_PO"  => (isset($licInfo['type']['deprecated']) && !$licInfo['type']['deprecated']) ? 1 : 0,
  );
  print_r($propVal);
  print_r("<br><br>");
  if($varActTime){
    $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
  }
  if($varSupportEndTime){
    $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
  }
  
  CIBlockElement::SetPropertyValuesEx($siteData["ID"], $siteData["IBLOCK_ID"],$propVal);
  if($licInfo["key"]){
    print_r("set ACTIVE!!!!");
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
        "ACTIVE" => "Y"
    );
    $s_res = $el->Update($siteData["ID"], $arLoadProductArray);
  }
  
}

//Добавляем новую лицензию
function createNewLic($licInfo, $userInfo, $modulesData, $goodsData){
  $newGoodModules = array();
  foreach($licInfo["features"] as $feature){
    $newGoodModules[] = $modulesData[$feature["id"]];
  }
  $name = "Лицензия " . $licInfo["productName"] . " для пользователя " . $userInfo["ID"];
 
  $varGenTime = $varActTime = $varSupportEndTime = false;
  if($licInfo["generationDateTime"])
    $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["generationDateTime"])->getTimestamp();
  if($licInfo["activationDateTime"])
    $varActTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["activationDateTime"])->getTimestamp();
  if($licInfo["supportExpirationDate"])
    $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$licInfo["supportExpirationDate"])->getTimestamp();
  print_r($licInfo);
  print_r("<br/><br/>");
  $el = new CIBlockElement;
  $propVal = array(
    "GOOD_INFO" => $goodsData[$licInfo["productName"]],
    "MODULES_INFO" => $newGoodModules,
    "USER_ID"   => $userInfo["ID"],
    "LICENSE_KEY" => $licInfo["key"],
    "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
    "COMP_CODE"       => $licInfo["device"]["key"],  
    "LICSERVER_ID"  => $licInfo["id"],
    "API_PRODUCT_NAME"  => $licInfo["productName"],
    "API_PRODUCT_TYPE" => 11, 
    "LICENSE_CODE" =>   $licInfo["type"]["code"],
    "SUPPORTED_PO"  => (isset($licInfo['type']['deprecated']) && !$licInfo['type']['deprecated']) ? 1 : 0,
  );
  if($varActTime){
    $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
  }
  if($varSupportEndTime){
    $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
  }

  $arLoadProductArray = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"         => LICENSE_IBLOCK_ID,
    "CODE"              => false,
    "NAME"              => $name,
    "ACTIVE"            => "Y",            // активен
    "PROPERTY_VALUES"   =>  $propVal,
    );
  if($el->Add($arLoadProductArray))
    print_r("OK");
  else
    print_r($el->LAST_ERROR);
  //$LIC_ID = $el->Add($arLoadProductArray);
  
  
}



// Обновляем данные по полученным от сервера
function updateAlreadyExistServLic($siteData, $licInfo, $goodsData, $modulesData){
  
  $newGoodModules = array();
  print_r($modulesData);
  print_r("<br/><br/>");
  foreach($licInfo["features"] as $feature){
    print_r($feature["id"]);
    print_r("<br/><br/>");
    print_r($modulesData[$feature["id"]]);
    print_r("<br/><br/>");
    $newGoodModules[] = $modulesData[$feature["id"]];
  }

  $varGenTime = $varActTime = $varSupportEndTime = false;
  if($licInfo["generationDate"])
    $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["generationDate"])->getTimestamp();
  if($licInfo["activationDate"])
    $varActTime = DateTime::createFromFormat('Y-n-j',$licInfo["activationDate"])->getTimestamp();
  if($licInfo["supportExpirationDate"])
    $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$licInfo["supportExpirationDate"])->getTimestamp();
  
  $licProduct = ($licInfo["productName"] == "viewer") ? "viewer_pro" : $licInfo["productName"];
  
  print_r($licProduct . "--" . $licInfo["productName"] . "---" . $goodsData[$licProduct] . "----!!!----" . strlen(trim($licInfo["key"])) . "---!!!---" . trim($licInfo["key"]));
  print_r("<br>");
  $licCode = false;
  if(strlen(trim($licInfo["key"])) == 29)
    $licCode = "00";
  if(strlen(trim($licInfo["key"])) == 19)
    $licCode = "ZZ";
  if(isset($licInfo["type"]) && isset($licInfo["type"]["code"]))
    $licCode = $licInfo["type"]["code"];
  
  print_r($licInfo);
  print_r("<br/><br/>");
  
  $propVal = array(
    "GOOD_INFO" => $goodsData[$licProduct],
    "MODULES_INFO" => $newGoodModules,
    "LICENSE_KEY" => trim($licInfo["key"]),
    "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
    "COMP_CODE"       => $licInfo["device"]["key"],
    "SERVER_CONNECTIONS"  =>   $licInfo["connectionCount"],
    "API_PRODUCT_NAME"  => $licInfo["productName"],
    "API_PRODUCT_TYPE" => 12,
    "SUPPORTED_PO"  => (isset($licInfo['type']['deprecated']) && !$licInfo['type']['deprecated']) ? 1 : 0,  
  );
  if($licCode)
    $propVal["LICENSE_CODE"] = $licCode;
  if($varActTime){
    $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
  }
  if($varSupportEndTime){
    $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
  }
  CIBlockElement::SetPropertyValuesEx($siteData["ID"], $siteData["IBLOCK_ID"],$propVal);
  //CIBlockElement::Update($obLicense["ID"], $arLoadProductArray);
  if($licInfo["key"]){
    print_r("set ACTIVE!!!!");
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
        "ACTIVE" => "Y"
    );
    $s_res = $el->Update($siteData["ID"], $arLoadProductArray);
  }
  
}

//Добавляем новую лицензию
function createNewServLic($licInfo, $userInfo, $modulesData, $goodsData){
  
  print_r($licInfo);
  $newGoodModules = array();
  foreach($licInfo["features"] as $feature){
    $newGoodModules[] = $modulesData[$feature["id"]];
  }
  $name = "Лицензия " . $licInfo["productName"] . " для пользователя " . $userInfo["ID"];
 
  $varGenTime = $varActTime = $varSupportEndTime = false;
  if($licInfo["generationDate"])
    $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$licInfo["generationDate"])->getTimestamp();
  if($licInfo["activationDate"])
    $varActTime = DateTime::createFromFormat('Y-n-j',$licInfo["activationDate"])->getTimestamp();
  if($licInfo["supportExpirationDate"])
    $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$licInfo["supportExpirationDate"])->getTimestamp();
  $licProduct = ($licProduct == "viewer") ? "viewer_pro" : $licInfo["productName"];
  $el = new CIBlockElement;
  $licCode = false;
  if(strlen(trim($licInfo["key"])) == 29)
    $licCode = "00";
  if(strlen(trim($licInfo["key"])) == 19)
    $licCode = "ZZ";
  if(isset($licInfo["type"]) && isset($licInfo["type"]["code"]))
    $licCode = $licInfo["type"]["code"];
  $propVal = array(
    "GOOD_INFO" => $goodsData[$licProduct],
    "MODULES_INFO" => $newGoodModules,
    "USER_ID"   => $userInfo["ID"],
    "LICENSE_KEY" => trim($licInfo["key"]),
    "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
    "LICSERVER_ID"  => $licInfo["id"],
    "COMP_CODE" =>   $licInfo["device"]["key"],
    "SERVER_CONNECTIONS"  =>   $licInfo["connectionCount"],
    "API_PRODUCT_NAME"  => $licInfo["productName"],
    "API_PRODUCT_TYPE" => 12,
    "SUPPORTED_PO"  => (isset($licInfo['type']['deprecated']) && !$licInfo['type']['deprecated']) ? 1 : 0,  
  );
  if($licCode)
    $propVal["LICENSE_CODE"] = $licCode;
  if($varActTime){
    $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
  }
  if($varSupportEndTime){
    $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
  }
  $arLoadProductArray = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"         => LICENSE_IBLOCK_ID,
    "CODE"              => false,
    "NAME"              => $name,
    "ACTIVE"            => "Y",            // активен
    "PROPERTY_VALUES"   =>  $propVal,
    );
  if($el->Add($arLoadProductArray))
    print_r("OK");
  else
    print_r($el->LAST_ERROR);
  //$LIC_ID = $el->Add($arLoadProductArray);
  
  
}





