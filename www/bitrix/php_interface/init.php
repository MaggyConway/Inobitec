<?

require_once($_SERVER["DOCUMENT_ROOT"]."/ConnToApi/LicServerClass.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/include/customCatalog.php');

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/*
 * 00 рецептов
 * 01 рецепт
 * 02-04 рецепта
 * 05-20 рецептов
 * X1 - рецепт  (2 < х < 10)
 * X2-X4 - рецепта (2 < х < 10)
 * Х5-Х9 - рецептов (2 < х < 10)
 * ['рецепт', 'рецепта', 'рецептов']
  */

function addToLog($msg){

   file_put_contents($_SERVER['DOCUMENT_ROOT']."/eventLogs.log",
                     date("Y-m-d H:i:s")
                     . $msg . "\r\n",
                     FILE_APPEND);
  } 

function decl($n, $forms){
  $modulo = $n % 10;
  $dec = $n % 100;
  return $n . ' ' . (($dec > 9 && $dec < 20) || $modulo > 4 || $modulo == 0 ? $forms[2] : ($modulo == 1 ? $forms[0] : $forms[1]));
}

/*
* Получаем по названию элементов ИД из типа "Список",
* если в указанном списке нет такого значения, заводим его
* 
* @params  $values     - array() or string - содержит текстовые названия свойств
*          $IBLOCK_ID  - ид инфоблока у кторогоп роставляем свойство
*          $CODE       - кодовый идентификатор свойства
* 
* return array() - список ИД свойств, соответсвующих переданным названиям
* 
*/
function getPropertyEnumID($values, $IBLOCK_ID, $CODE)
{
 $rez = array();
 $ibpenum = new CIBlockPropertyEnum;
 $property = CIBlockProperty::GetByID($CODE, $IBLOCK_ID)->GetNext();
 $PROPERTY_ID = $property['ID'];
 if(is_array($values)){  //если передан массив
   $lowerValues = array();
   foreach($values as $key => $value){
     $lowerValues[$key] = mb_strtolower($value);
   }
   $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$CODE));
   while($enum_fields = $property_enums->GetNext()){
     //print_r($enum_fields["ID"] . " --- " . $enum_fields["VALUE"] . "<br/>");
     if(in_array(mb_strtolower($enum_fields["VALUE"]), $lowerValues)){
       $rez[] = $enum_fields["ID"];
       $this->del_from_array($enum_fields["VALUE"], $values, true);
       break;
     }

   }
   if( count($values) > 0 ){

     foreach($values as $value){
       if($PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$value)))
         $rez[] = $PropID;
       else
          echo "Error: ".$ibpenum->LAST_ERROR; 
     }
   }
 }else{ //если передано одно значение
   $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>$CODE));
   $find = false;
   while($enum_fields = $property_enums->GetNext()){
     //print_r($enum_fields["ID"] . " --- " . $enum_fields["VALUE"] . "<br/>");
     if(mb_strtolower($enum_fields["VALUE"]) == mb_strtolower($values))
       $rez[] = $enum_fields["ID"];
   }
   if( count($rez) == 0 )
     if($PropID = $ibpenum->Add(Array('PROPERTY_ID'=>$PROPERTY_ID, 'VALUE'=>$values)))
       $rez[] = $PropID;
     else
        echo "Error: ".$ibpenum->LAST_ERROR; 
 }
 return $rez;
}

function getProductInfo($producrId, $siteId = "s1"){
 
  $price = CPrice::GetBasePrice($producrId);
  $res = CIBlockElement::GetByID($producrId);
  $ar_res = $res->GetNext();
  $resGoods = CIBlockElement::GetList(
      array('sort' => 'asc'),
      array('IBLOCK_ID' => $ar_res["IBLOCK_ID"], "ID" => $ar_res["ID"]),
      false,
      false,
      array('ID', "NAME", "PROPERTY_NAME_EN")
  );
  $obGood = $resGoods->getNext();
  $ar_res["PROPERTY_NAME_EN_VALUE"] = $obGood["PROPERTY_NAME_EN_VALUE"];
  return(array("price" => $price, "product" => $ar_res));
}

function checkSectionType($sectionID, $iBlockID){
  $rsSections2 = CIBlockSection::GetList(
      array(), 
      array(
          "ID" => $sectionID,
          "IBLOCK_ID" => $iBlockID
      ), 
      false, 
      array("ID",  "UF_*")
  );
  $arSection2 = $rsSections2->Fetch();
  if(!$arSection2)
    return false;
  return $arSection2["UF_TYPE"];
}

function checkIfServer($productId){
  $arrParentID = CCatalogSku::GetProductInfo($productId);
  if(!is_array($arrParentID))
    return false;
  $resGoods = CIBlockElement::GetList(
      array('sort' => 'asc'),
      array('IBLOCK_ID' => $arrParentID["IBLOCK_ID"], "ID" => $arrParentID["ID"]),
      false,
      false,
      array('ID', "PROPERTY_LIC_TYPE")
  );
  
  if(!$obGood = $resGoods->getNext())
    return false;
  if(!$obGood["PROPERTY_LIC_TYPE_VALUE"] || $obGood["PROPERTY_LIC_TYPE_VALUE"] != 'server')
    return false;
  return true;
}

function checkIfGoodhasLic($productId){
  $arrParentID = CCatalogSku::GetProductInfo($productId);
  if(!is_array($arrParentID)){
    $obProductInfo = CIBlockElement::GetByID($productId);
    $arrParentID = $obProductInfo->getNext();
    if(!is_array($arrParentID)){
      return false;
    }
  }
  $resGoods = CIBlockElement::GetList(
      array('sort' => 'asc'),
      array('IBLOCK_ID' => $arrParentID["IBLOCK_ID"], "ID" => $arrParentID["ID"]),
      false,
      false,
      array('ID', "PROPERTY_LIC_TYPE")
  );
  
  if(!$obGood = $resGoods->getNext()){
    return false;
  }
  if(!$obGood["PROPERTY_LIC_TYPE_VALUE"] && !($obGood["PROPERTY_LIC_TYPE_VALUE"] == 'server' || $obGood["PROPERTY_LIC_TYPE_VALUE"] == 'pro' || $obGood["PROPERTY_LIC_TYPE_VALUE"] == 'lite') ){
    return false;
  }
  return true;
} 

function checkIfLicense($obGood){
  if(!$obGood)
    return false;
  if(!$obGood["PROPERTY_LIC_TYPE_VALUE"] || $obGood["PROPERTY_LIC_TYPE_VALUE"] != 'license')
    return false;
  return true;
  
}

function checkIfModule($blockId){
  $arrModulesBlockIDs = array(32,33);
  if(in_array($blockId,$arrModulesBlockIDs))
    return true;
  return false;
}

function checkIfHasModules($productId){
  
  if(1 == 1)
    return true;
  return false;
}

function defaultSectionData($sectionID){
  $rsSections = CIBlockSection::GetList(
      array(), 
      array(
          "ID" => $sectionID,
          "IBLOCK_CODE" => "goods_".SITE_ID
      ), 
      false, 
      array("ID", "IBLOCK_ID", "UF_*")
  );
  $arSection = $rsSections->Fetch();
  if(!$arSection)
    return false;
  $iBlockID = $arSection["IBLOCK_ID"];
  
  $sectionType = checkSectionType($sectionID, $iBlockID);
  if($sectionType == "dicomviewer"){
    return dicomviewerDefaulData($sectionID, $iBlockID);
  }elseif($sectionType == "dicomserver"){
    return dicomserverDefaulData($sectionID, $iBlockID);
  }
}

function dicomviewerDefaulData($sectionID, $iBlockID)
{
    $resModules = CIBlockElement::GetList(
        ['sort' => 'asc'],
        ['IBLOCK_CODE' => 'modules_' . SITE_ID, "ACTIVE" => "Y"],
        false,
        false,
        [
            'ID',
            'NAME',
            'DETAIL_TEXT',
            'CATALOG_GROUP_1',
            "PROPERTY_NAME_EN",
            "PROPERTY_DESCRIPTION_EN",
            'PROPERTY_NAME_FR',
            'PROPERTY_DESCRIPTION_FR',
            'PROPERTY_NAME_DE',
            'PROPERTY_NAME_ES'
        ]
    );
    $arrModules = [];
    while ($obModule = $resModules->getNext()) {
        $item = [];

        $tmp_name = $obModule["NAME"];
        if (SITE_ID_FOR_BASKET == "s2") {
            $tmp_name = $obModule["PROPERTY_NAME_EN_VALUE"];
        }
        if (SITE_ID_FOR_BASKET == "s3") {
            $tmp_name = $obModule["PROPERTY_NAME_FR_VALUE"];
        }
        if (SITE_ID_FOR_BASKET == "s4"){
            $tmp_name = $obModule["PROPERTY_NAME_DE_VALUE"];
        }
        if (SITE_ID_FOR_BASKET == "s5"){
            $tmp_name = $obModule["PROPERTY_NAME_ES_VALUE"];
        }

        $item["name"] = $tmp_name;
        $priceCheck = '';
        if (SITE_ID_FOR_BASKET == 's1') {
            $priceCheck = $obModule["CATALOG_PRICE_1"];
        }elseif(SITE_ID_FOR_BASKET == 's2'){
            $priceCheck = CCurrencyRates::ConvertCurrency($obModule["CATALOG_PRICE_1"], "RUB", "USD" );
        }else{
            $priceCheck = CCurrencyRates::ConvertCurrency($obModule["CATALOG_PRICE_1"], "RUB", "EUR" );
        }

        $item["price"] = (SITE_ID_FOR_BASKET == 's1') ? number_format($priceCheck, 0, '', ' ') : number_format(
            floor($priceCheck),
            0,
            '',
            ''
        );
        $item["inbasket"] = false;
        $item["quantity"] = 1;

        $item["detailText"] = (SITE_ID_FOR_BASKET == "s2") ? $obModule["~PROPERTY_DESCRIPTION_EN_VALUE"]["TEXT"] : $obModule["DETAIL_TEXT"];
        $item["productID"] = $obModule["ID"];
        $arrModules[] = $item;
    }

    $resGoods = CIBlockElement::GetList(
        ['sort' => 'asc'],
        ['IBLOCK_ID' => $iBlockID, "IBLOCK_SECTION_ID" => $sectionID],
        false,
        false,
        [
            'ID',
            'NAME',
            'DETAIL_TEXT',
            'CATALOG_GROUP_1',
            "PROPERTY_LIC_TYPE",
            "PROPERTY_SHORT_NAME",
            "DETAIL_PICTURE",
            "PROPERTY_PREVIEW_TEXT_EN",
            "PROPERTY_NAME_EN",
            "PROPERTY_SHORT_NAME_EN",
            "PROPERTY_IMAGE_EN",
            "PROPERTY_IMAGE_RU",
        ]
    );
    $arrGoods = [];

    while ($obGoods = $resGoods->getNext()) {
        $imageLogo = (SITE_ID_FOR_BASKET == "s2") ? CFile::GetFileArray(
            $obGoods["PROPERTY_IMAGE_EN_VALUE"]
        ) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]);
        $item = [];
        if ($imageLogo && isset($imageLogo["SRC"]))
            $item["img"] = $imageLogo["SRC"];
        $item["shortName"] = (SITE_ID_FOR_BASKET == "s2") ? $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_VALUE"];
        $item["detailText"] = (SITE_ID_FOR_BASKET == "s2") ? $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"] : $obGoods["DETAIL_TEXT"];
        $priceCheck = (SITE_ID_FOR_BASKET == 's1') ? $obGoods["CATALOG_PRICE_1"] : CCurrencyRates::ConvertCurrency(
            $obGoods["CATALOG_PRICE_1"],
            "RUB",
            "USD"
        );
        $item["price"] = (SITE_ID_FOR_BASKET == 's1') ? number_format($priceCheck, 0, '', ' ') : number_format(
            floor($priceCheck),
            0,
            '',
            ''
        );
        $item["inbasket"] = false;
        $item["quantity"] = 1;
        $item["license"] = [];
        $item["productID"] = $obGoods["ID"];
        $item["basketId"] = md5($obGoods["ID"] . time());
        if ($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "pro") {
            $item["modules"] = $arrModules;
            $arrGoods["pro"][] = $item;
        }elseif ($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "lite") {
            $arrGoods["lite"][] = $item;
        }elseif ($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "license") {
            $arrGoods["viewerLic"] = $item;
        }
    }
    return $arrGoods;
}

function dicomserverDefaulData($sectionID, $iBlockID){
  $resGoods = CIBlockElement::GetList(
      array('sort' => 'asc'),
      array('IBLOCK_ID' => $iBlockID, "IBLOCK_SECTION_ID" => $sectionID),
      false,
      false,
      array('ID', 'NAME', 'DETAIL_TEXT', "PROPERTY_LIC_TYPE", "PROPERTY_SHORT_NAME", "DETAIL_PICTURE", "PROPERTY_PREVIEW_TEXT_EN", "PROPERTY_NAME_EN", "PROPERTY_SHORT_NAME_EN", "PROPERTY_IMAGE_EN", "PROPERTY_IMAGE_RU")
  );
  $arrGoods = array();
  while($obGoods = $resGoods->getNext()){
    $imageLogo = (SITE_ID_FOR_BASKET == "s1") ? CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_EN_VALUE"]);
    $item = array();
    if($imageLogo && isset($imageLogo["SRC"]))
      $item["img"] = $imageLogo["SRC"];
    $item["inbasket"] = false;
    $item["productID"] = $obGoods["ID"];
    if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "license"){
      $arrGoods["serverLic"] = $item;
      continue;
    }
    if($obGoods["PROPERTY_LIC_TYPE_VALUE"] != "server")
      continue;
    $item["shortName"] = (SITE_ID_FOR_BASKET == "s2") ? $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_VALUE"];
    $item["detailText"] = (SITE_ID_FOR_BASKET == "s2") ? $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"] : $obGoods["DETAIL_TEXT"];
    $item["basketId"] = md5($obGoods["ID"].time());
    $item["license"] = array();
    
    $arrOffers = CCatalogSKU::getOffersList(
        $obGoods["ID"], 
        $iblockID = 0,
        $skuFilter = array(),
        $fields = array(),
        $propertyFilter = array()
    );
    $arrOffersID = array();
    foreach($arrOffers[$obGoods["ID"]] as $key => $value){
      $arrOffersID["IDs"][] = $value["ID"];
      $arrOffersID["blockID"] = $value["IBLOCK_ID"];
    }
    $resOffers = CIBlockElement::GetList(
        array('sort' => 'asc'),
        array('IBLOCK_ID' => $arrOffersID["blockID"], "ID" => $arrOffersID["IDs"]),
        false,
        false,
        array('ID', 'NAME', 'CATALOG_GROUP_1', 'SORT', "PROPERTY_CONNECTIONS", "PROPERTY_NAME_EN", "PROPERTY_NAME_FR")
    );
    while($obOffer = $resOffers->getNext()){
      $offer["productID"] = $obOffer["ID"];
	  
		$tmp_name = $obOffer["NAME"];
		if(SITE_ID_FOR_BASKET == "s2"){
			$tmp_name = $obOffer["PROPERTY_NAME_EN_VALUE"];
		}
		if(SITE_ID_FOR_BASKET == "s3"){
			$tmp_name = $obOffer["PROPERTY_NAME_FR_VALUE"];
		}
	  
      $offer["name"] = $tmp_name;//(SITE_ID_FOR_BASKET == "s2") ? $obOffer["PROPERTY_NAME_EN_VALUE"] : $obOffer["NAME"];
      if($obOffer["PROPERTY_CONNECTIONS_VALUE"] == 1){
        $priceCheck = (SITE_ID_FOR_BASKET == 's1')  ? $obOffer["CATALOG_PRICE_1"] : CCurrencyRates::ConvertCurrency($obOffer["CATALOG_PRICE_1"], "RUB", "USD");
        $offer["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format(floor($priceCheck), 0, '', '');
      }else{
        $priceCheck = str_replace(" ","",$item["offers"][1]["price"]) * $obOffer["PROPERTY_CONNECTIONS_VALUE"];
        $offer["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format($priceCheck, 0, '', '');
      }
      $offer["inbasket"] = false;
      $offer["num"] = $obOffer["PROPERTY_CONNECTIONS_VALUE"];
      $item["offers"][$obOffer["PROPERTY_CONNECTIONS_VALUE"]] = $offer;
    }
    $arrGoods["server"] = $item;
  }
  return $arrGoods;
}

function basketInfo(){

  $resModules = CIBlockElement::GetList(
    array('sort' => 'asc'),
    array('IBLOCK_CODE' => 'modules_'.SITE_ID, "ACTIVE" => "Y"),
    false,
    false,
    array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', 'PROPERTY_NAME_EN', 'PROPERTY_DESCRIPTION_EN', 'PROPERTY_NAME_FR', 'PROPERTY_DESCRIPTION_FR','PROPERTY_NAME_DE','PROPERTY_NAME_ES')
  );
  $arrModules = array();
  while($obModule = $resModules->getNext()){
    $item = array();
	
	$tmp_name = $obModule["NAME"];
	if(SITE_ID_FOR_BASKET == "s2"){
		$tmp_name = $obModule["PROPERTY_NAME_EN_VALUE"];
	}
	if(SITE_ID_FOR_BASKET == "s3"){
		$tmp_name = $obModule["PROPERTY_NAME_FR_VALUE"];
	}
	if(SITE_ID_FOR_BASKET == "s4"){
		$tmp_name = $obModule["PROPERTY_NAME_DE_VALUE"];
	}
	if(SITE_ID_FOR_BASKET == "s5"){
		$tmp_name = $obModule["PROPERTY_NAME_ES_VALUE"];
	}
	
	
    $item["name"] = $tmp_name;
    $priceCheck = (SITE_ID_FOR_BASKET == 's1')  ? $obModule["CATALOG_PRICE_1"] : CCurrencyRates::ConvertCurrency($obModule["CATALOG_PRICE_1"], "RUB", "USD");
    $item["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format(floor($priceCheck), 0, '', '');
    $item["inbasket"] = false;
    $item["quantity"] = 1;
    $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obModule["~DETAIL_TEXT"] : $obModule["~PROPERTY_DESCRIPTION_EN_VALUE"]["TEXT"];
    $item["productID"] = $obModule["ID"];
    $arrModules[] = $item;
  }
  $arrProducts = array();
  $basketModules = array();
  $arRezData = array();
  $arrLic = array();
  
  $arBasketItems = array();
  $dbBasketItems = CSaleBasket::GetList(
    array(),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "ORDER_ID" => "NULL",
        //"LID" => SITE_ID
      ),
    false,
    false,
    array()
  );
  
  $arrProUniqueCodeToID = array();
  while ($arItems = $dbBasketItems->GetNext()) {
    
    $arItems["PROPS"] = Array();
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => array("UNIQUE_CODE", "LICENSE_ID", "CONNECTIONS")));
    while($arProp = $dbProp -> GetNext()){
      $arItems["PROPS"][] = $arProp;
    }
    
    
    if(checkIfServer($arItems["PRODUCT_ID"])){
      $arrParentID = CCatalogSku::GetProductInfo($arItems["PRODUCT_ID"]);
      $resGoods = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array('IBLOCK_ID' => $arrParentID["IBLOCK_ID"], "ID" => $arrParentID["ID"]),
          false,
          false,
          array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', "PROPERTY_LIC_TYPE", "PROPERTY_SHORT_NAME", "DETAIL_PICTURE", "PROPERTY_SHORT_NAME_EN", "PROPERTY_NAME_EN", "PROPERTY_PREVIEW_TEXT_EN", "PROPERTY_IMAGE_EN", "PROPERTY_IMAGE_RU")
      );
      $obGoods = $resGoods->getNext();
      $imageLogo = (SITE_ID_FOR_BASKET == "s2") ? CFile::GetFileArray($obGoods["PROPERTY_IMAGE_EN_VALUE"]) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]);
      $item = array();
      $uniqueCode = getOrderItemPropByCode($arItems["PROPS"], "UNIQUE_CODE");
      if($imageLogo && isset($imageLogo["SRC"]))
        $item["img"] = $imageLogo["SRC"];
      $item["shortName"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["PROPERTY_SHORT_NAME_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"];
      $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["~DETAIL_TEXT"] : $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"];
      $item["inbasket"] = true;
      $item["quantity"] = $arItems["QUANTITY"];
      $item["license"] = array();
      
      $item["productID"] = $obGoods["ID"];
      $item["basketId"] = $uniqueCode;
      $item["cartitemId"] = $arItems["ID"];
      $dbPropServ = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "PRODUCT_CODE"));
      $arPropServ = $dbPropServ -> GetNext();
      $item["productCode"] = $arPropServ["VALUE"];

      $arrOffers = CCatalogSKU::getOffersList(
          $obGoods["ID"], 
          $iblockID = 0,
          $skuFilter = array(),
          $fields = array(),
          $propertyFilter = array()
      );
      $arrOffersID = array();
      foreach($arrOffers[$obGoods["ID"]] as $key => $value){
        $arrOffersID["IDs"][] = $value["ID"];
        $arrOffersID["blockID"] = $value["IBLOCK_ID"];
      }
      $resOffers = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array('IBLOCK_ID' => $arrOffersID["blockID"], "ID" => $arrOffersID["IDs"]),
          false,
          false,
          array('ID', 'NAME', 'CATALOG_GROUP_1', 'SORT', 'PROPERTY_CONNECTIONS', 'PROPERTY_NAME_EN')
      );
      while($obOffer = $resOffers->getNext()){
        if($obOffer["ID"] == $arItems["PRODUCT_ID"]){
          $item["serverConnections"] = $obOffer["PROPERTY_CONNECTIONS_VALUE"];
        }
        $offer["productID"] = $obOffer["ID"];
        $offer["name"] = (SITE_ID_FOR_BASKET == "s1") ? $obOffer["NAME"] : $obOffer["PROPERTY_NAME_EN_VALUE"];
		if($obOffer["PROPERTY_CONNECTIONS_VALUE"] == 1){
          $priceCheck = (SITE_ID_FOR_BASKET == 's1')  ? $obOffer["CATALOG_PRICE_1"] : CCurrencyRates::ConvertCurrency($obOffer["CATALOG_PRICE_1"], "RUB", "USD");
          $offer["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format(floor($priceCheck), 0, '', '');
        }else{
          $priceCheck = str_replace(" ","",$item["offers"][1]["price"]) * $obOffer["PROPERTY_CONNECTIONS_VALUE"];
          $offer["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format($priceCheck, 0, '', '');
        }
        $offer["inbasket"] = false;
        $offer["num"] = $obOffer["PROPERTY_CONNECTIONS_VALUE"];
        $item["offers"][$obOffer["PROPERTY_CONNECTIONS_VALUE"]] = $offer;
      }
      $arBasketItems["server"] = $item;
      
      continue;
    }
    
    
    $productInfo = GetElementSectionsID($arItems["PRODUCT_ID"]);
    $sectionType = checkSectionType($productInfo[0], $productInfo[1]);
    $resGoods = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array('IBLOCK_ID' => $productInfo[1], "ID" => $arItems["PRODUCT_ID"]),
          false,
          false,
          array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', "PROPERTY_LIC_TYPE", "PROPERTY_SHORT_NAME", "DETAIL_PICTURE", "PROPERTY_NAME_EN", 'PROPERTY_PREVIEW_TEXT_EN', "PROPERTY_SHORT_NAME_EN", "PROPERTY_IMAGE_EN", "PROPERTY_IMAGE_RU", 'PROPERTY_NAME_FR', 'PROPERTY_DESCRIPTION_FR')
      );
    $arrGoods = array();
    $obGoods = $resGoods->getNext();
    if(!$obGoods)
      continue;
    
    if(checkIfLicense($obGoods)){
      $imageLogo = CFile::GetFileArray($obGoods["DETAIL_PICTURE"]);
      $item = array();
      if($imageLogo && isset($imageLogo["SRC"]))
        $item["img"] = $imageLogo["SRC"];
      $priceCheck = (SITE_ID_FOR_BASKET == 's1' || $arItems["CURRENCY"] == 'USD')  ? $arItems["PRICE"] : CCurrencyRates::ConvertCurrency($arItems["PRICE"], "RUB", "USD");
      $item["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format(floor($priceCheck), 0, '', '');
      
		$tmp_name = $obGoods["NAME"];
		if(SITE_ID_FOR_BASKET == "s2"){
			$tmp_name = $obGoods["PROPERTY_NAME_EN_VALUE"];
		}
		if(SITE_ID_FOR_BASKET == "s3"){
			$tmp_name = $obGoods["PROPERTY_NAME_FR_VALUE"];
		}
	  
	  $item["name"] = $tmp_name;
      $item["cartitemId"] = $arItems["ID"];
      $item["itemsINfo"] = $arItems;
      $item["inbasket"] = true;
      $item["quantity"] = $arItems["QUANTITY"];
      $item["productID"] = $arItems["PRODUCT_ID"];
      $item["PROPS"] = $arItems["PROPS"];

      
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "YEARS"));
      while($arProp = $dbProp -> GetNext()){
        $item['years'] = $arProp["VALUE"];
      }
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "ADDITIONALCONSPRICE"));
      if($arProp = $dbProp -> GetNext()){
        $pricePerConn = $arProp["VALUE"];
        $conNums = false;
        $dbPropConNum = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => "CONNECTIONS"));
        while($arPropConnNum = $dbPropConNum -> GetNext()){
          $conNums = $arPropConnNum["VALUE"];
        }
        $priceCheck = (SITE_ID_FOR_BASKET == 's1' || $arItems["CURRENCY"] == 'USD')  ? $pricePerConn : CCurrencyRates::ConvertCurrency($pricePerConn, "RUB", "USD");
        $item["price"] = (SITE_ID_FOR_BASKET == 's1')  ? $item["price"] : number_format(floor($priceCheck), 0, '', '') * $conNums;
      }
      $arrLic[] = $item;
      continue;
    }
    
    if($sectionType == "dicomviewer"){
      if(!isset($arrProducts[$arItems["PRODUCT_ID"]])){
        
          $imageLogo = (SITE_ID_FOR_BASKET == "s2") ? CFile::GetFileArray($obGoods["PROPERTY_IMAGE_EN_VALUE"]) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]);
          $item = array();
          if($imageLogo && isset($imageLogo["SRC"]))
            $item["img"] = $imageLogo["SRC"];
          $item["shortName"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["PROPERTY_SHORT_NAME_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"];
          $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["~DETAIL_TEXT"] : $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"];

          $priceCheck = '';
          if (SITE_ID_FOR_BASKET == 's1') {
              $priceCheck = $obGoods["CATALOG_PRICE_1"];
          }elseif(SITE_ID_FOR_BASKET == 's2'){
              $priceCheck = CCurrencyRates::ConvertCurrency($obGoods["CATALOG_PRICE_1"], "RUB", "USD" );
          }else{
              $priceCheck = CCurrencyRates::ConvertCurrency($obGoods["CATALOG_PRICE_1"], "RUB", "EUR" );
          }

          $item["price"] = (SITE_ID_FOR_BASKET == 's1')  ? number_format($priceCheck, 0, '', ' ') : number_format(floor($priceCheck), 0, '', '');
          $item["SITE_ID_FOR_BASKET"] = SITE_ID_FOR_BASKET;
          $item["inbasket"] = true;
          $item["quantity"] = $arItems["QUANTITY"];
          $item["productID"] = $obGoods["ID"];
          $item["basketId"] = "" ;
          $item["lic"] = $obGoods["PROPERTY_LIC_TYPE_VALUE"];
          $item["license"] = array();
          if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "pro"){
            $item["modules"] = $arrModules;
            $arrProducts[$arItems["PRODUCT_ID"]] = $item;
          }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "lite"){
            $arrProducts[$arItems["PRODUCT_ID"]] = $item;
          }
        
      }
      if($arrProducts[$arItems["PRODUCT_ID"]]["lic"] == "pro"){
        $uniqueCode = getOrderItemPropByCode($arItems["PROPS"], "UNIQUE_CODE");
        $arBasketItems["pro"][$arItems["ID"]] = $arrProducts[$arItems["PRODUCT_ID"]];
        $arBasketItems["pro"][$arItems["ID"]]["basketId"] = ($uniqueCode) ? $uniqueCode : "";
        $arBasketItems["pro"][$arItems["ID"]]["inbasket"] = true;
        $arBasketItems["pro"][$arItems["ID"]]["quantity"] = $arItems["QUANTITY"];
        $arBasketItems["pro"][$arItems["ID"]]["cartitemId"] = $arItems["ID"];
        $arrProUniqueCodeToID[$arBasketItems["pro"][$arItems["ID"]]["basketId"]] = $arItems["ID"];
      }elseif($arrProducts[$arItems["PRODUCT_ID"]]["lic"] == "lite"){
        $uniqueCode = getOrderItemPropByCode($arItems["PROPS"], "UNIQUE_CODE");
        $arBasketItems["lite"][$arItems["ID"]] = $arrProducts[$arItems["PRODUCT_ID"]];
        $arBasketItems["lite"][$arItems["ID"]]["basketId"] = ($uniqueCode) ? $uniqueCode : "";
        $arBasketItems["lite"][$arItems["ID"]]["inbasket"] = true;
        $arBasketItems["lite"][$arItems["ID"]]["quantity"] = $arItems["QUANTITY"];
        $arBasketItems["lite"][$arItems["ID"]]["cartitemId"] = $arItems["ID"];
        $arrProUniqueCodeToID[$arBasketItems["lite"][$arItems["ID"]]["basketId"]] = $arItems["ID"];
      }
        
    }elseif($sectionType == "dicomserver"){
      
    }elseif(!$sectionType){
      $basketModules[] = $arItems;
    }
    
  }
  $arRezData["listOfExtLic"] = getListOfExtLic();
  $newListOfExtLic = array();
  foreach($basketModules as $mod){
    $uniqueCode = getOrderItemPropByCode($mod["PROPS"], "UNIQUE_CODE");
    if($uniqueCode){
      foreach($arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]["modules"] as $key => $value){
        if($value["productID"] == $mod["PRODUCT_ID"]){
          $arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]["modules"][$key]["inbasket"] = true;
          $arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]["modules"][$key]["quantity"] = $mod["QUANTITY"];
          $arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]["modules"][$key]["cartitemId"] = $mod["ID"];
        }
      }
      
    }
  }
  //print_r($arrLic);
  foreach($arrLic as $lic){
    $uniqueCode = getOrderItemPropByCode($lic["PROPS"], "UNIQUE_CODE");
    if($uniqueCode){
      if(isset($arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]))
        $arBasketItems["pro"][$arrProUniqueCodeToID[$uniqueCode]]["license"] = $lic;
      if(isset($arBasketItems["lite"][$arrProUniqueCodeToID[$uniqueCode]]))
        $arBasketItems["lite"][$arrProUniqueCodeToID[$uniqueCode]]["license"] = $lic;
      if($arBasketItems["server"]["basketId"] == $uniqueCode)
        $arBasketItems["server"]["license"] = $lic;
      
    }else{
      
      $idLicenseForUpdate = getOrderItemPropByCode($lic["PROPS"], "LICENSE_ID");
      if($idLicenseForUpdate){
        if(isset($arRezData["listOfExtLic"]["pro"][$idLicenseForUpdate])){
          $arRezData["listOfExtLic"]["pro"][$idLicenseForUpdate]["updateLic"] = $lic;
          $arRezData["listOfExtLic"]["pro"][$idLicenseForUpdate]['inbasket'] = true;
        }
        if(isset($arRezData["listOfExtLic"]["lite"][$idLicenseForUpdate])){
          $arRezData["listOfExtLic"]["lite"][$idLicenseForUpdate]["updateLic"] = $lic;
          $arRezData["listOfExtLic"]["lite"][$idLicenseForUpdate]['inbasket'] = true;
        }
        if(isset($arRezData["listOfExtLic"]["server"][$idLicenseForUpdate])){
          $newConnections = getOrderItemPropByCode($lic["PROPS"], "CONNECTIONS");
          if($newConnections){
            $arRezData["newServConn"] = $arRezData["listOfExtLic"]["server"][$idLicenseForUpdate];
            $arRezData["newServConn"]['inbasket'] = true;
            $arRezData["newServConn"]["updateLic"] = $lic;
            $arRezData["newServConn"]["updateLic"]["connectionsNum"] = $newConnections;
          }else{
            $arRezData["listOfExtLic"]["server"][$idLicenseForUpdate]["updateLic"] = $lic;
            $arRezData["listOfExtLic"]["server"][$idLicenseForUpdate]['inbasket'] = true;
          }
        }
      }
    }
  }
  
  foreach($arRezData["listOfExtLic"]["pro"] as $val){
    unset($val["PROPS"]);
    $newListOfExtLic["pro"][] = $val;
  }
  foreach($arRezData["listOfExtLic"]["server"] as $val){
    unset($val["PROPS"]);
    $newListOfExtLic["server"][] = $val;
  }
  foreach($arRezData["listOfExtLic"]["lite"] as $val){
    unset($val["PROPS"]);
    $newListOfExtLic["lite"][] = $val;
  }
  $arRezData["listOfExtLic"] = $newListOfExtLic;
 
  foreach($arBasketItems['lite'] as $value){
    $arRezData['lite'][] = $value;
  }
  foreach($arBasketItems['pro'] as $value){
    $arRezData['pro'][] = $value;
  }
  $arRezData["server"] = $arBasketItems["server"];
  
  $arFilter = array(
    "CURRENCY" => "USD"
    );
  $by = "date";
  $order = "desc";
  $db_rate = CCurrencyRates::GetList($by, $order, $arFilter);
  $ar_rate = $db_rate->Fetch();
  $arRezData['USD_CUR'] = $ar_rate['RATE'];
    \Bitrix\Main\Diag\Debug::dumpToFile($arRezData, '', '_logBasketInfo.log');
    return $arRezData;
  
}

function getListOfExtLic($getForLKData = false){
  global $USER;
  $availableLicenseTypes = array("05", "10", "00");
  $userID = $USER->GetID();
  if(!$userID)
    return false;
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_USER_ID" => $USER->GetID()),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_LICENSE_KEY", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_LICENSE_CODE", "PROPERTY_NEXT_LICENSE_ID", "PROPERTY_GENERATION_TIME", "PROPERTY_ORDER_ID")
          );
  
  
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
  
  $resModules = CIBlockElement::GetList(
    array('sort' => 'asc'),
    array('IBLOCK_CODE' => 'modules_s1', "ACTIVE" => "Y"),
    false,
    false,
    array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', 'PROPERTY_NAME_EN', 'PROPERTY_DESCRIPTION_EN', 'PROPERTY_NAME_FR', 'PROPERTY_DESCRIPTION_FR')
  );
  $arrModules = array();
  while($obModule = $resModules->getNext()){
    $item = array();
	
	$tmp_name = $obModule["NAME"];
	if(SITE_ID_FOR_BASKET == "s2"){
		$tmp_name = $obModule["PROPERTY_NAME_EN_VALUE"];
	}
	if(SITE_ID_FOR_BASKET == "s3"){
		$tmp_name = $obModule["PROPERTY_NAME_FR_VALUE"];
	}
	
    $item["name"] = $tmp_name; //(SITE_ID_FOR_BASKET == "s1") ? $obModule["NAME"] : $obModule["PROPERTY_NAME_EN_VALUE"];
    $item["price"] = number_format($obModule["CATALOG_PRICE_1"], 0, '', ' ');
    $item["price_en"] = number_format(floor(CCurrencyRates::ConvertCurrency($obModule["CATALOG_PRICE_1"], "RUB", "USD")), 0, '', '');
    $item["inbasket"] = true;
    $item["quantity"] = 1;
    $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obModule["~DETAIL_TEXT"] : $obModule["~PROPERTY_DESCRIPTION_EN_VALUE"]["TEXT"];
    $item["productID"] = $obModule["ID"];
    $arrModules[ $obModule["ID"]] = $item;
  }
  
  
  $arr = array();
  $arrProducts = array();
  while($obLicense = $resLicense->getNext()){
    $connOrder = CSaleOrder::GetByID($obLicense["PROPERTY_ORDER_ID_VALUE"]);
    //print_r($obLicense);
    if(!in_array($obLicense["PROPERTY_LICENSE_CODE_VALUE"], $availableLicenseTypes))
      continue;
    if(!$obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"])
      continue;

    if($obLicense["PROPERTY_NEXT_LICENSE_ID_VALUE"] && $obLicense["PROPERTY_NEXT_LICENSE_ID_VALUE"] > 0 && !$getForLKData)
      continue;
    if(SITE_ID == 's1'){
      $dtime = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
      $dtimeGen = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_GENERATION_TIME_VALUE"]);
      
    }else{
      $time = trim(preg_replace("/[a-z]/i", "", $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]));
      $dtime = DateTime::createFromFormat("m/d/Y G:i:s", $time);
      $time2 = trim(preg_replace("/[a-z]/i", "", $obLicense["PROPERTY_GENERATION_TIME_VALUE"]));
      $dtimeGen = DateTime::createFromFormat("m/d/Y G:i:s", $time2);
    }
    
    
    $canUpdateLic = true;
    $datetime1 = new DateTime();
    $interval = $dtime->diff($datetime1);
    $yearDif = $interval->format('%y');
    if($dtimeGen){
      /*if( ($dtimeGen->format('%y') == $datetime1->format('%y')) && ($dtimeGen->format('%m') == $datetime1->format('%m')) && ($dtimeGen->format('%d') == $datetime1->format('%d')) ){
        $canUpdateLic = false;
        //continue;
      }*/
    }
    /*print_r($obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
    print_r("<br>");
    print_r($yearDif);
    print_r("<br>");
    print_r($interval->format('%Y-%m-%d %H:%i:%s'));
    print_r("<br><br><br>");*/

    $actual = true;
    if($yearDif >= 2 && $interval->invert == 1){
      $canUpdateLic = false;
      //continue;
    }elseif($yearDif >= 1 && $interval->invert == 1){
      $obLicense["MAX_EXT_YEARS"] = 1;
    }
    elseif($yearDif >= 0 && $interval->invert == 1)
      $obLicense["MAX_EXT_YEARS"] = 2;
    else{
      $obLicense["MAX_EXT_YEARS"] = 3;
      $actual = false;
    }
    
    
    if($obLicense["PROPERTY_LICENSE_KEY_VALUE"]){
      $productInfo = GetElementSectionsID($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
      $sectionType = checkSectionType($productInfo[0], $productInfo[1]);
      $resGoods = CIBlockElement::GetList(
            array('sort' => 'asc'),
            array('IBLOCK_ID' => $productInfo[1], "ID" => $obLicense["PROPERTY_GOOD_INFO_VALUE"]),
            false,
            false,
            array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', "PROPERTY_LIC_TYPE", "PROPERTY_SHORT_NAME", "DETAIL_PICTURE", "PROPERTY_NAME_EN", 'PROPERTY_PREVIEW_TEXT_EN', "PROPERTY_SHORT_NAME_EN", "PROPERTY_IMAGE_EN", "PROPERTY_IMAGE_RU")
        );
      $arrGoods = array();
      $obGoods = $resGoods->getNext();
      $currency = $connOrder["CURRENCY"];
      if(!$currency)
        $currency = (SITE_ID_FOR_BASKET =='s1')?'RUB':'USD';
      if(!$obGoods)
        continue;
       if($sectionType == "dicomviewer"){
        if(!isset($arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]])){
            
                    
            $imageLogo = (SITE_ID_FOR_BASKET == "s2") ? CFile::GetFileArray($obGoods["PROPERTY_IMAGE_EN_VALUE"]) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]);
            $item = array();
            
            if($imageLogo && isset($imageLogo["SRC"]))
              $item["img"] = $imageLogo["SRC"];
            $item["shortName"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["PROPERTY_SHORT_NAME_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"];
            $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["~DETAIL_TEXT"] : $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"];
            $item["price"] = number_format($obGoods["CATALOG_PRICE_1"], 0, '', ' ');
            $item["price_en"] = number_format(floor(CCurrencyRates::ConvertCurrency($obGoods["CATALOG_PRICE_1"], "RUB", "USD")), 0, '', '');
            $item["currency"] = $currency;
            $item["order"] = $connOrder["CURRENCY"];
            $item["productID"] = $obGoods["ID"];
            $item["inbasket"] = false;
            $item["basketId"] = "" ;
            
            $item["lic"] = $obGoods["PROPERTY_LIC_TYPE_VALUE"];
            if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "pro"){
              $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]] = $item;
            }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "lite"){
              $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]] = $item;
            }

        }
        if($arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]]["lic"] == "pro"){
          $addItem = $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]];
          $addItem["oldLicID"] = $obLicense["ID"];
          $addItem["maxYears"] = $obLicense["MAX_EXT_YEARS"];
          $addItem["licKey"] = $obLicense["PROPERTY_LICENSE_KEY_VALUE"];
          $addItem["endData"] = (SITE_ID_FOR_BASKET == "s1") ? $dtime->format("d.m.Y") : $dtime->format('m.d.Y');
          $addItem["actualLic"] = $actual;
          $addItem["canUpdateLic"] = $canUpdateLic;
          if(in_array($obLicense["ID"], $arrLicenseWithUpdates)){
              $addItem["haveUpdates"] = true;
              $addItem["MAX_EXT_YEARS"] = 0;
            }
          if(is_array($obLicense["PROPERTY_MODULES_INFO_VALUE"]) && count($obLicense["PROPERTY_MODULES_INFO_VALUE"]) > 0 ){
            foreach($obLicense["PROPERTY_MODULES_INFO_VALUE"] as $val){
              $addItem["modules"][] = $arrModules[$val];
            }
          }
          $arr["pro"][$obLicense["ID"]] = $addItem;
        }elseif($arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]]["lic"] == "lite"){
          $addItem = $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]];
          $addItem["oldLicID"] = $obLicense["ID"];
          $addItem["maxYears"] = $obLicense["MAX_EXT_YEARS"];
          $addItem["licKey"] = $obLicense["PROPERTY_LICENSE_KEY_VALUE"];
          $addItem["endData"] = (SITE_ID_FOR_BASKET == "s1") ? $dtime->format("d.m.Y") : $dtime->format('m.d.Y');
          $addItem["actualLic"] = $actual;
          $addItem["canUpdateLic"] = $canUpdateLic;
          if(in_array($obLicense["ID"], $arrLicenseWithUpdates)){
              $addItem["haveUpdates"] = true;
              $addItem["MAX_EXT_YEARS"] = 0;
            }
          $arr["lite"][$obLicense["ID"]] = $addItem;
          
        }

      }elseif($sectionType == "dicomserver"){
          if(!isset($arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]])){
            $imageLogo = (SITE_ID_FOR_BASKET == "s2") ? CFile::GetFileArray($obGoods["PROPERTY_IMAGE_EN_VALUE"]) : CFile::GetFileArray($obGoods["PROPERTY_IMAGE_RU_VALUE"]);
            $item = array();
            
            if($imageLogo && isset($imageLogo["SRC"]))
              $item["img"] = $imageLogo["SRC"];
            $item["shortName"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["PROPERTY_SHORT_NAME_VALUE"] : $obGoods["PROPERTY_SHORT_NAME_EN_VALUE"];
            $item["detailText"] = (SITE_ID_FOR_BASKET == "s1") ? $obGoods["~DETAIL_TEXT"] : $obGoods["~PROPERTY_PREVIEW_TEXT_EN_VALUE"]["TEXT"];
            $item["price"] = $obGoods["CATALOG_PRICE_1"];
            $item["price_en"] = number_format(floor(CCurrencyRates::ConvertCurrency($obGoods["CATALOG_PRICE_1"], "RUB", "USD")), 0, '', '');
            $item["currency"] = $currency;
            $item["order"] = $connOrder["CURRENCY"];
            $item["productID"] = $obGoods["ID"];
            $item["inbasket"] = false;
            $item["basketId"] = "" ;
            
            $item["lic"] = $obGoods["PROPERTY_LIC_TYPE_VALUE"];
            if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "server"){
              $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]] = $item;
            }
          }  //print_r("dicomserver");
          
          if($arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]]["lic"] == "server"){
            $addItem = $arrProducts[$obLicense["PROPERTY_GOOD_INFO_VALUE"]];
            $addItem["numConnection"] = $obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"];
            $addItem["price"] = number_format($addItem["price"], 0, '', '');
            $addItem["price_en"] = number_format(floor(CCurrencyRates::ConvertCurrency($addItem["price"], "RUB", "USD")), 0, '', '');
            $item["currency"] = $currency;
            $item["order"] = $connOrder["CURRENCY"];
            $addItem["oldLicID"] = $obLicense["ID"];
            $addItem["compCode"] = $obLicense["PROPERTY_COMP_CODE_VALUE"];
            $addItem["maxYears"] = $obLicense["MAX_EXT_YEARS"];
            $addItem["licKey"] = $obLicense["PROPERTY_LICENSE_KEY_VALUE"];
            $addItem["endData"] = (SITE_ID_FOR_BASKET == "s1") ? $dtime->format("d.m.Y") : $dtime->format('m.d.Y');
            $addItem["actualLic"] = $actual;
            $addItem["canUpdateLic"] = $canUpdateLic;
            if(in_array($obLicense["ID"], $arrLicenseWithUpdates)){
                $addItem["haveUpdates"] = true;
                $addItem["MAX_EXT_YEARS"] = 0;
              }
            $arr["server"][$obLicense["ID"]] = $addItem;
          }
        
        
      }

    }
  }
  return $arr;
}

function checkIfExistCompCode($compCode){
  global $USER;
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_COMP_CODE" => $compCode),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_LICENSE_KEY", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_LICENSE_CODE", "PROPERTY_NEXT_LICENSE_ID", "PROPERTY_GENERATION_TIME")
          );
  $existCode = $currentUser = false;
  while($obLicense = $resLicense->getNext()){
    $existCode = true;
    if($obLicense["PROPERTY_USER_ID_VALUE"] == $USER->GetID()){
      $currentUser = true;
    }
  }
  return array('existCode' => $existCode, 'currentUser' => $currentUser);
}

function GetElementSectionsID($ID)
{
  $res   =   CIBlockElement::GetByID($ID);
  $ar_res   =   $res->GetNext();
  return(array($ar_res['IBLOCK_SECTION_ID'], $ar_res['IBLOCK_ID']));
}

function calculatePrice($productId, $sectionID, $iblockID){
  $licSectionType = checkSectionType($sectionID, $iblockID);
  $arBasketItems = array();
  $dbBasketItems = CSaleBasket::GetList(
    array(),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "ORDER_ID" => "NULL",
        //"LID" => SITE_ID
      ),
    false,
    false,
    array()
  );
  $serverPrice = 0;
  $viewerPrice = 0;
  while ($arItems = $dbBasketItems->GetNext()) {
    /*print_r($arItems);
    print_r("<br><br>");*/
    $obProductInfo = CIBlockElement::GetByID($arItems["PRODUCT_ID"]);
    $productInfo = $obProductInfo->getNext();
    $resGoods = CIBlockElement::GetList(
        array('sort' => 'asc'),
        array('IBLOCK_ID' => $productInfo["IBLOCK_ID"], "ID" => $productInfo["ID"]),
        false,
        false,
        array('ID', "NAME", "PROPERTY_LIC_TYPE")
    );
    $obGood = $resGoods->getNext();
    if(checkIfLicense($obGood)){
      continue;
    }
    
    
    if(checkIfServer($arItems["PRODUCT_ID"])){
      $serverPrice+=$arItems["PRICE"];
      continue;
    }
    $res   =   CIBlockElement::GetByID($arItems["PRODUCT_ID"]);
    $ar_res   =   $res->GetNext();
    
    $sectionType = checkSectionType($ar_res['IBLOCK_SECTION_ID'], $ar_res['IBLOCK_ID']);
    if($sectionType == "dicomviewer"){
      $viewerPrice+=$arItems["PRICE"]; 
        
    }elseif($sectionType == "dicomserver"){
      
    }elseif(!$sectionType){
      $viewerPrice+=$arItems["PRICE"];
    }
  }
  if($licSectionType == "dicomviewer"){
    return $viewerPrice;
  }elseif($licSectionType == "dicomserver"){
    return $serverPrice;
  }
}

function recalculateLicensePrice($delZero = false){
  global $USER;
  if(!CModule::IncludeModule("iblock"))
    return false; 
  if(!CModule::IncludeModule("sale"))
    return false; 
  if(!CModule::IncludeModule("catalog"))
    return false;
  
  $arBasketItems = array();
  $dbBasketItems = CSaleBasket::GetList(
    array(),
    array(
      "FUSER_ID" => CSaleBasket::GetBasketUserID(),
      "ORDER_ID" => "NULL",  
      ),
    false,
    false,
    array()
  );
  $goodPrices = array();
  $goodLics = array();
  $newConnections = array();
  $n = 1;
  while ($arItems = $dbBasketItems->GetNext()) {
      $obProductInfo = CIBlockElement::GetByID($arItems["PRODUCT_ID"]);
      $productInfo = $obProductInfo->getNext();
      $resGoods = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array('IBLOCK_ID' => $productInfo["IBLOCK_ID"], "ID" => $productInfo["ID"]),
          false,
          false,
          array('ID', "NAME", "PROPERTY_LIC_TYPE")
      );
      $obGood = $resGoods->getNext();
      $basketItemCode = false;
      if(checkIfLicense($obGood)){
      //  print_r("<br><br><br><br>");
      //  print_r($arItems);
      //  print_r("<br><br>");
        
        $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => array("UNIQUE_CODE", "YEARS", "LICENSE_ID", "CONNECTIONS")));
        while($arProp = $dbProp -> GetNext()){
        //  print_r($arProp);
        //  print_r("<br><br>");
          if($arProp["CODE"] == "UNIQUE_CODE"){
            $basketItemCode = $arProp["VALUE"];
          }
          if($arProp["CODE"] == "YEARS"){
            $arItems["PROPS_YEARS"] =  $arProp["VALUE"];
          }
          if($arProp["CODE"] == "LICENSE_ID"){
            $arItems["IF_LIC_UPDATE"] =  "Y";
            $arItems["OLD_LIC_ID"] =  $arProp["VALUE"];
          }
          if($arProp["CODE"] == "CONNECTIONS"){
            $arItems["NEW_COON"] =  $arProp["VALUE"];
          }
          
        }
        if($arItems["NEW_COON"] && $arItems["IF_LIC_UPDATE"]){
          $newConnections[$arItems["OLD_LIC_ID"]] = $arItems["NEW_COON"];
        }
        
       // print_r($arItems);
       // print_r("<br><br>");
        
        if($basketItemCode)
          $goodLics[$basketItemCode] = $arItems;
        else
          $goodLics[$n++] = $arItems;

      }else{
        
        $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItems["ID"], "CODE" => array("UNIQUE_CODE")));
        while($arProp = $dbProp -> GetNext()){
          if($arProp["CODE"] == "UNIQUE_CODE"){
            $basketItemCode = $arProp["VALUE"];
          }
        }
        if(isset($goodPrices[$basketItemCode]))
          $goodPrices[$basketItemCode] += $arItems["PRICE"];
        else
          $goodPrices[$basketItemCode] = $arItems["PRICE"];
      }
  }

  foreach($goodLics as $uniqueCode => $licData){
    //print_r($licData);
    //print_r("<br>");
    if(isset($licData["IF_LIC_UPDATE"]) && $licData["IF_LIC_UPDATE"] == "Y"){
      if(!$USER->IsAuthorized()){
        CSaleBasket::Delete($licData["ID"]);
        continue;
      }
      //print_r("TEST");
      //print_r("<br>");
      if($licData["PROPS_YEARS"]){
       // print_r("TEST1");
        //print_r("<br>");
        $resLicense = CIBlockElement::GetList(
                array('sort' => 'asc'),
                array('IBLOCK_CODE' => 'license', "PROPERTY_USER_ID" => $USER->GetID(), 'ID' => $licData["OLD_LIC_ID"]),
                false,
                false,
                array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_LICENSE_KEY", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_LICENSE_CODE", "PROPERTY_NEXT_LICENSE_ID")
            );
        $obLicense = $resLicense->getNext();
        $newConn = isset($newConnections[$licData["OLD_LIC_ID"]])? $newConnections[$licData["OLD_LIC_ID"]] : false;
        /*print_r("<br>");
        print_r($newConnections[$licData["OLD_LIC_ID"]]);
        print_r("<br>");
        print_r($obLicense);
        print_r("<br>");
        print_r($licData["PROPS_YEARS"]);
        print_r("<br>");
        print_r($newConn);
        print_r("<br>");*/
        $newPrice = $licData["PROPS_YEARS"]* (0.3-(($licData["PROPS_YEARS"]-1)*0.05)) * calcPriceForLicenseUpdate($obLicense, $newConn);
       // print_r($newPrice);
       // print_r("<br>");
        if($licData["PRICE"] != $newPrice){
          $arFields = array(
            "PRICE" => $newPrice,
          );
          CSaleBasket::Update($licData["ID"], $arFields);
        }
      }
      continue;
    }
    $calcPrice = $licData["PROPS_YEARS"]* (0.3-(($licData["PROPS_YEARS"]-1)*0.05)) * $goodPrices[$uniqueCode];
    $arFields = array(
      "PRICE" => $calcPrice,
    );
    
    if($calcPrice == 0){
        CSaleBasket::Delete($licData["ID"]);
    }else{
      CSaleBasket::Update($licData["ID"], $arFields);
    }
  }

}

function calcPriceForLicenseUpdate($obLicense, $newConns = false){
  $price = 0;
  
  if(!$obLicense["PROPERTY_GOOD_INFO_VALUE"])
    return false;
  
  $obPrice = CPrice::GetBasePrice($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
  $price += $obPrice["PRICE"];
  if($obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"] > 0){
    if(!$newConns)
      $price = $price * $obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"];
    else
      $price = $price * ($obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"] + $newConns);
  }
  
  
  
  if(is_array($obLicense["PROPERTY_MODULES_INFO_VALUE"]) && count($obLicense["PROPERTY_MODULES_INFO_VALUE"]) > 0 ){
    foreach($obLicense["PROPERTY_MODULES_INFO_VALUE"] as $val){
      $obPriceModule = CPrice::GetBasePrice($val);
      $price += $obPriceModule["PRICE"];
    }
  }
  
  return $price;
}

function devideOrder($items, $getForLKdata = false){
  if(!defined('SITE_ID_FOR_BASKET')) {
    define('SITE_ID_FOR_BASKET', SITE_ID);
  }
  $proArr = array();
  $liteArr = array();
  $serverArr = array();
  $modulesArr = array();
  $otherArr = array();
  $licServ = array();
  $licView = array();
  $sortArr = array();
  $arrProUniqueCodeToID = array();
  $arrProBasketitemToID = array();
  $ListOfExtLic = getListOfExtLic($getForLKdata);
  $updateArr = array();
  
  foreach( $items as $key => $item ){
    $flag = false;
    $data = getProductInfo($item["PRODUCT_ID"]);
    $data["productID"] = $productId;
    
    
    if(checkIfModule($data["product"]["IBLOCK_ID"])){
      $modulesArr[$key] = $item;
      $flag = true;
    }else{
      $resGoods = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array("ID" => $item["PRODUCT_ID"], "IBLOCK_ID" => $data["product"]["IBLOCK_ID"]),
          false,
          false,
          array('ID', 'DETAIL_TEXT', "PROPERTY_LIC_TYPE")
      );
      $arrGoods = array();
      $obGoods = $resGoods->getNext();
      
      if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "lite"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $liteArr[$key] = $item;
        $flag = true;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $arrProBasketitemToID[$item["ID"]] = $key;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "pro"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $proArr[$key] = $item;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $arrProBasketitemToID[$item["ID"]] = $key;
        $flag = true;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "server"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $serverArr[$key] = $item;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $arrProBasketitemToID[$item["ID"]] = $key;
        $flag = true;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "license"){
        $arFilterSection = array('IBLOCK_ID' => $data["product"]["IBLOCK_ID"], 'ID' => $data["product"]["IBLOCK_SECTION_ID"]);
        $arSelectSection = array("CODE");
        $rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilterSection, $arSelectSection);
        $arSect = $rsSections->GetNext();
        if($arSect["CODE"] == "dicomviewer"){
          $licView[$key] = $item;
          $flag = true;
        }elseif($arSect["CODE"] == "dicomserver"){
          $licServ[$key] = $item;
          $flag = true;
        }
        
      }
    }
    
    if(!$flag){
      $otherArr[$key] = $item;
    }
    
   
  }
  foreach($modulesArr as $keyMod => $module){
    foreach($module["PROPS"] as $prop){
      if($prop["CODE"] == "UNIQUE_CODE"){
        
        if(strlen(strval($prop["VALUE"])) < 6){
          $proArr[$arrProBasketitemToID[$prop["VALUE"]]]["modules"][$keyMod] = $module;
        }else{

          $proArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["modules"][$keyMod] = $module;

        }
      }
    }
   }
   
  foreach($licView as $key => $lic){
    foreach($lic["PROPS"] as $prop){
      if($prop["CODE"] == "UNIQUE_CODE"){
        if(isset($liteArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){
          $liteArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
          unset($licView[$key]);
        }
        if(isset($proArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){
          $proArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
          unset($licView[$key]);
        }
        
      }elseif($prop["CODE"] == "LICENSE_ID"){
        
        
        $idLicenseForUpdate = getOrderItemPropByCode($lic["PROPS"], "LICENSE_ID");
        if($prop["VALUE"]){
          if(isset($ListOfExtLic["pro"][$prop["VALUE"]])){
            $updateArr[$key] = $lic;
            $updateArr[$key]['item'] = $ListOfExtLic["pro"][$prop["VALUE"]];
            unset($licView[$key]);
          }
          if(isset($ListOfExtLic["lite"][$prop["VALUE"]])){
            $updateArr[$key] = $lic;
            $updateArr[$key]['item'] = $ListOfExtLic["lite"][$prop["VALUE"]];
            unset($licView[$key]);
          }
          
        }
        
        
      }
    }
  }
  foreach($licServ as $key => $lic){
    foreach($lic["PROPS"] as $prop){
      if($prop["CODE"] == "UNIQUE_CODE"){
        if(isset($serverArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){
          $serverArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
          unset($licServ[$key]);
        }
      }elseif($prop["CODE"] == "LICENSE_ID"){
        $idLicenseForUpdate = getOrderItemPropByCode($lic["PROPS"], "LICENSE_ID");
        if(isset($ListOfExtLic["server"][$prop["VALUE"]])){
          $updateArr[$key] = $lic;
          $updateArr[$key]['item'] = $ListOfExtLic["server"][$prop["VALUE"]];
          unset($licServ[$key]);
        }
      }
    }
  }
  return array("pro" => $proArr,"lite" => $liteArr,"viewerLic" => $licView,"server" => $serverArr, "serverLic" => $licServ, "updateArr" => $updateArr, "other" => $otherArr); 
}

function sortOrder($items){
  $proArr = array();
  $liteArr = array();
  $serverArr = array();
  $modulesArr = array();
  $otherArr = array();
  $licServ = array();
  $licView = array();
  $sortArr = array();
  $arrProUniqueCodeToID = array();
  foreach( $items as $key => $item ){
    $flag = false;
    $data = getProductInfo($item["PRODUCT_ID"]);
    $data["productID"] = $item["PRODUCT_ID"];


    if(SITE_ID != "s1"){
      $enName = getEnOrderItemName($item);
      if($enName)
        $item["NAME"] = $enName;
    }

    if(checkIfModule($data["product"]["IBLOCK_ID"])){
      $modulesArr[$key] = $item;
      $modulesArr[$key]["NAME"] = "—" . $modulesArr[$key]["NAME"] . Loc::getMessage('ORDER_SORT_ADDITIONAL_MODULE');
      $flag = true;
    }elseif(checkIfServer($data["productID"])){
      $serverArr[$key] = $item;
      $flag = true;
    }else{
      $resGoods = CIBlockElement::GetList(
          array('sort' => 'asc'),
          array("ID" => $item["PRODUCT_ID"], "IBLOCK_ID" => $data["product"]["IBLOCK_ID"]),
          false,
          false,
          array('ID', 'DETAIL_TEXT', "PROPERTY_LIC_TYPE")
      );
      $arrGoods = array();
      $obGoods = $resGoods->getNext();

      if($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "lite"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $liteArr[$key] = $item;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $flag = true;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "pro"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $proArr[$key] = $item;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $flag = true;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "server"){
        $uniqueCode = getOrderItemPropByCode($item["PROPS"], "UNIQUE_CODE");
        $serverArr[$key] = $item;
        $arrProUniqueCodeToID[$uniqueCode] = $key;
        $flag = true;
      }elseif($obGoods["PROPERTY_LIC_TYPE_VALUE"] == "license"){

        $arFilterSection = array('IBLOCK_ID' => $data["product"]["IBLOCK_ID"], 'ID' => $data["product"]["IBLOCK_SECTION_ID"]);
        $arSelectSection = array("CODE");
        $rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFilterSection, $arSelectSection);
        $arSect = $rsSections->GetNext();
        if($arSect["CODE"] == "dicomviewer"){
          $licView[$key] = $item;
          $flag = true;
        }elseif($arSect["CODE"] == "dicomserver"){
          $licServ[$key] = $item;
          $flag = true;
        }

      }
    }

    if(!$flag){
      $otherArr[$key] = $item;
    }
  }

   foreach($modulesArr as $keyMod => $module){
      foreach($module["PROPS"] as $prop){

        if($prop["CODE"] == "UNIQUE_CODE"){
          if(strlen(strval($prop["VALUE"])) < 6)
            $proArr[$prop["VALUE"]]["modules"][$keyMod] = $module;
          else
        $proArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["modules"][$keyMod] = $module;

        }
      }
    }

    foreach($licView as $key => $lic){
      foreach($lic["PROPS"] as $prop){
        if($prop["CODE"] == "UNIQUE_CODE"){
          if(isset($liteArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){

            $liteArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
            unset($licView[$key]);
          }
          if(isset($proArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){
            $proArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
            unset($licView[$key]);
          }
        }elseif($prop["CODE"] == "LICENSE_ID"){
          $licView[$key]["UPDATE"] = "UPDATE";
          $licID =  $prop["VALUE"];
          $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $licID),
              false,
              false,
              array('ID', 'IBLOCK_ID', "PROPERTY_GOOD_INFO")
          );
          $obLicense = $resLicense->getNext();
          $productId = $obLicense["PROPERTY_GOOD_INFO_VALUE"];

          $resProduct = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'goods_s1', "ID" => $productId),
              false,
              false,
              array('ID', 'IBLOCK_ID', 'NAME', "PROPERTY_NAME_EN")
          );
          $obProduct = $resProduct->getNext();
          $licView[$key]["NAME"] .= (SITE_ID != "s1")?"#br#".$obProduct["PROPERTY_NAME_EN_VALUE"]:"#br#".$obProduct["NAME"];
        }

      }
    }
    foreach($licServ as $key => $lic){
      foreach($lic["PROPS"] as $prop){
        if($prop["CODE"] == "UNIQUE_CODE"){
          if(isset($serverArr[$arrProUniqueCodeToID[$prop["VALUE"]]])){
            $serverArr[$arrProUniqueCodeToID[$prop["VALUE"]]]["license"][$key] = $lic;
            unset($licServ[$key]);
          }
        }elseif($prop["CODE"] == "LICENSE_ID"){
          $licServ[$key]["UPDATE"] = "UPDATE";
          $licID =  $prop["VALUE"];
          $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $licID),
              false,
              false,
              array('ID', 'IBLOCK_ID', "PROPERTY_GOOD_INFO")
          );
          $obLicense = $resLicense->getNext();
          $productId = $obLicense["PROPERTY_GOOD_INFO_VALUE"];

          $resProduct = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'goods_s1', "ID" => $productId),
              false,
              false,
              array('ID', 'IBLOCK_ID', 'NAME', "PROPERTY_NAME_EN", 'PROPERTY_NAME_FR')
          );
          $obProduct = $resProduct->getNext();
          $licServ[$key]["NAME"] .= (SITE_ID != "s1")?"#br#".$obProduct["PROPERTY_NAME_EN_VALUE"]:"#br#".$obProduct["NAME"];
        }
      }
    }

    foreach($liteArr as $keyLite => $valLite){
      $sortArr[] = $valLite;
      if(isset($valLite['license']) && count($valLite["license"]) > 0){
        $lic = reset($valLite['license']);
        $lic["NAME"] .= "#br#".$valLite["NAME"];
        $sortArr[] = $lic;
      }
    }

    foreach($proArr as $keyPro => $valPro){
      $sortArr[] = $valPro;
      if(isset($valPro["modules"]) && count($valPro["modules"]) > 0)
        foreach($valPro["modules"] as $keyMod => $mod)
          $sortArr[] = $mod;
      if(isset($valPro['license']) && count($valPro["license"])){
        $lic = reset($valPro['license']);
        $lic["NAME"] .= "#br#".$valPro["NAME"];
         $sortArr[] = $lic;
      }
    }


    foreach($licView as $keyLV => $valLV){
      if(count($liteArr) > 0){
        $liteItem = reset($liteArr);
        $valLV["NAME"] .= "#br#".$liteItem["NAME"];
      }
      if(count($proArr) > 0){
        $proItem = reset($proArr);
        $valLV["NAME"] .= "#br#".$proItem["NAME"];
      }
      $sortArr[] = $valLV;
    }

    foreach($serverArr as $keySer => $valSer){
      $sortArr[] = $valSer;
      if(isset($valSer["license"]) && count($valSer["license"]) > 0){
        $lic = reset($valSer['license']);
        $lic["NAME"] .= "#br#".$valSer["NAME"];
         $sortArr[] = $lic;
      }
    }

    foreach($licServ as $keyLS => $valLS){
      if(count($serverArr) > 0){
        $serverItem = reset($serverArr);
        $arrParentGood = CCatalogSku::GetProductInfo($serverItem["PRODUCT_ID"]);
        if(is_array($arrParentGood))
          $resGoods = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_ID' => $arrParentGood["IBLOCK_ID"], "ID" => $arrParentGood["ID"]),
              false,
              false,
              array('ID', "NAME", "PROPERTY_NAME_EN")
          );
          $arrGood = $resGoods->getNext();
          if(SITE_ID != "s1" && $arrGood["PROPERTY_NAME_EN_VALUE"]){
            $valLS["NAME"] .= "#br#".$arrGood["PROPERTY_NAME_EN_VALUE"];
          }else{
            $valLS["NAME"] .= "#br#".$arrGood["NAME"];
          }
      }
      $sortArr[] = $valLS;
    }
    foreach($otherArr as $keyOth => $valOth){
      $sortArr[] = $valOth;
    }

  return $sortArr;


}

function getEnOrderItemName($item){
  
  foreach($item["PROPS"] as $prop){
    if($prop["CODE"] == "NAME_EN")
      return $prop["VALUE"];
  }
  return false;
}

function getOrderItemPropByCode($arrItemProps, $propCode){
  foreach($arrItemProps as $prop){
    if($propCode == $prop["CODE"])
      return $prop["VALUE"];
  }
  return false;
}

function intToWordsEng($x) {
  $nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
                  "eight", "nine", "ten", "eleven", "twelve", "thirteen",
                  "fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
                  "nineteen", "twenty", 30 => "thirty", 40 => "forty",
                  50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
                  90 => "ninety" );


  if(!is_numeric($x))
     $w = '#';
  else if(fmod($x, 1) != 0)
     $w = '#';
  else {
     if($x < 0) {
        $w = 'minus ';
        $x = -$x;
     } else
        $w = '';
     // ... now $x is a non-negative integer.

     if($x < 21)   // 0 to 20
        $w .= $nwords[$x];
     else if($x < 100) {   // 21 to 99
        $w .= $nwords[10 * floor($x/10)];
        $r = fmod($x, 10);
        if($r > 0)
           $w .= '-'. $nwords[$r];
     } else if($x < 1000) {   // 100 to 999
        $w .= $nwords[floor($x/100)] .' hundred';
        $r = fmod($x, 100);
        if($r > 0)
           $w .= ' and '. intToWordsEng($r);
     } else if($x < 1000000) {   // 1000 to 999999
        $w .= intToWordsEng(floor($x/1000)) .' thousand';
        $r = fmod($x, 1000);
        if($r > 0) {
           $w .= ' ';
           if($r < 100)
              $w .= 'and ';
           $w .= intToWordsEng($r);
        }
     } else {    //  millions
        $w .= intToWordsEng(floor($x/1000000)) .' million';
        $r = fmod($x, 1000000);
        if($r > 0) {
           $w .= ' ';
           if($r < 100)
              $word .= 'and ';
           $w .= intToWordsEng($r);
        }
     }
  }
  return $w;
}

function getPersonTypeFromUser($userTypeID){
  switch($userTypeID){
      case 2:
          //Ранее в этом случае было просто 2. Но ID типа плательщика Юридическое лицо различается для разных сайтов.
          return SITE_ID == 's2' ? 4 : 1;
        break;
      case 3:
          return 3;
        break;
      case 4:
          return 2;
        break;
      default:
        return false;
        break;
    }
    return false;
}

function changeDefaultPersonType(){
  global $USER;
  if ($USER->IsAuthorized() && !isset($_POST["PERSON_TYPE_OLD"])){
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $personType = getPersonTypeFromUser($arUser["UF_USER_TYPE"]);
    if($personType > 0){
      $_POST["PERSON_TYPE_OLD"] = 1;
      $_POST["PERSON_TYPE"] = $personType;
    }
  }
  return false;
}

function getLegalFormByUserType($userType){
  switch($userType){
    case 2:
      return "ООО";
      break;
    case 4:
      return "Частное лицо";
      break;
    case 3:
      return "ИП";
      break;
    default:
      return false;
      break;
  }
}

function getFirmName($name, $legalForm = false){
  if(!$legalForm || $legalForm == '')
    return trim($name);
  $pos = stripos(trim($name), $legalForm);
  if($pos === 0)
    if(substr(trim($name),strlen(trim($legalForm)),1) == ' ')
    return substr(trim($name), strlen(trim($legalForm)));
  return trim($name);
}

function addUserToAPI($userId){
  $rsUser = CUser::GetByID($userId);
  $arUser = $rsUser->Fetch();
  if($arUser["UF_LIC_SERV_ID"] > 0)
    return false;
  $legalForm = getLegalFormByUserType($arUser["UF_USER_TYPE"]);
  if($arUser['UF_LEGAL_FORM'])
    $legalForm = $arUser['UF_LEGAL_FORM'];
  if(!$legalForm)
    return false;
  $name = false;
  $contact = false;
  if($arUser["UF_USER_TYPE"] == 2){
    $name = getFirmName($arUser["UF_COMPANY_NAME"], $legalForm);
    if($arUser["UF_CEO_FULL_NAME"])
      $contact = $arUser["UF_CEO_FULL_NAME"];
  }else{
    $name = trim($arUser["LAST_NAME"] . " " . $arUser["NAME"] . " " . $arUser["SECOND_NAME"]);
    if(strlen($name) == 0 )
      $name = false;
    $contact = $name;
  }
  if(!$name)
    return false;
  
  
  $arData = array(
    "legal_form" => $legalForm,
    "e_mail" => $arUser["LOGIN"],
    "name" => $name,
    "tax_id" => ($arUser["UF_TAX_ID"])?$arUser["UF_TAX_ID"]:false,
    "contact_person" => $contact,
    "phone" => ($arUser["PERSONAL_PHONE"])?$arUser["PERSONAL_PHONE"]:false,
    "legal_address" => ($arUser["UF_LEGAL_ADDRESS"])?$arUser["UF_LEGAL_ADDRESS"]:false,
  );
  $inobitecLicense = new inobitecLicense();
  $rez = $inobitecLicense->createClient(
          $arData['legal_form'],
          $arData['name'],
          $arData['e_mail'],
          $arData['tax_id'],
          $arData['contact_person'],
          $arData['phone'],
          $arData['legal_address']);
  if(isset($rez["id"])){
    $user = new CUser;
    $fields = Array(
      "UF_LIC_SERV_ID"    => $rez["id"]
    );
    $user->Update($userId, $fields);
  }

}

function updateAllLicense($orderID){
  $resUpdateLicensees = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "PROPERTY_ORDER_ID" => $orderID, 'ACTIVE' => 'Y'),
              false,
              false,
              array('ID')
          );
  while($obUpdateLic = $resUpdateLicensees->getNext()){
    //print_r($obLic["ID"] . "   ");
    updateLicenseToAPI($obUpdateLic["ID"]);
  }
}

function addOrderLicToApi($orderID){
  $resLicensees = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_ORDER_ID" => $orderID, "PROPERTY_LICSERVER_ID" => false),
              false,
              false,
              array('ID')
          );
  $inobitecLicense = new inobitecLicense();
  while($obLic = $resLicensees->getNext()){
    //print_r($obLic["ID"] . "   ");
    addLicenseToAPI($obLic["ID"], $inobitecLicense);
    sleep(1);
  }
}

function prepareLicenseForUpdateToAPI($orderID){
  $availableLicenseTypes = array("05", "10", "00");
  $resLicensees = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "PROPERTY_ORDER_ID" => $orderID, "ACTIVE" => "Y"),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_LICENSE_ID", "PROPERTY_IF_LIC_UPDATE", "PROPERTY_YEARS", "PROPERTY_IF_ADD_SERV_CONN", "PROPERTY_SERVER_CONNECTIONS")
          );
  $allLicUpdateArr = array();
  while($obUpdateLic = $resLicensees->getNext()){
    if(!$obUpdateLic)
      continue;
    if(!$obUpdateLic["PROPERTY_LICENSE_ID_VALUE"])
      continue;
    
    $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_LICENSE_KEY", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_LICENSE_CODE", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_ACTIVATION_TIME")
          );
    $obLicense = $resLicense->getNext();
    
    if(!in_array($obLicense["PROPERTY_LICENSE_CODE_VALUE"], $availableLicenseTypes))
      return false;
  
    if(!$obLicense)
      return false;
    $goodData = getLicenseApiData($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
    if(!$goodData)
      return false;
    if($obUpdateLic["PROPERTY_IF_ADD_SERV_CONN_VALUE"] == 0){
      unset($obUpdateLic["PROPERTY_IF_ADD_SERV_CONN_VALUE"]);
      unset($obUpdateLic["~PROPERTY_IF_ADD_SERV_CONN_VALUE"]);
      unset($obUpdateLic["PROPERTY_IF_ADD_SERV_CONN_VALUE_ID"]);
      unset($obUpdateLic["~PROPERTY_IF_ADD_SERV_CONN_VALUE_ID"]);
      
      unset($obUpdateLic["PROPERTY_SERVER_CONNECTIONS_VALUE"]);
      unset($obUpdateLic["~PROPERTY_SERVER_CONNECTIONS_VALUE"]);
      unset($obUpdateLic["PROPERTY_SERVER_CONNECTIONS_VALUE_ID"]);
      unset($obUpdateLic["~PROPERTY_SERVER_CONNECTIONS_VALUE_ID"]);
    }
    
    if($obUpdateLic["PROPERTY_IF_LIC_UPDATE_VALUE"] == 0){
      unset($obUpdateLic["PROPERTY_IF_LIC_UPDATE_VALUE"]);
      unset($obUpdateLic["~PROPERTY_IF_LIC_UPDATE_VALUE"]);
      unset($obUpdateLic["PROPERTY_IF_LIC_UPDATE_VALUE_ID"]);
      unset($obUpdateLic["~PROPERTY_IF_LIC_UPDATE_VALUE_ID"]);
      
      unset($obUpdateLic["PROPERTY_YEARS_VALUE"]);
      unset($obUpdateLic["~PROPERTY_YEARS_VALUE"]);
      unset($obUpdateLic["PROPERTY_YEARS_VALUE_ID"]);
      unset($obUpdateLic["~PROPERTY_YEARS_VALUE_ID"]);
    }
    $obUpdateLic["goodDataInfo"] = $goodData;
    $obUpdateLic["oldLicDataInfo"] = $obLicense;
    foreach($obUpdateLic as $key => $value){
      $allLicUpdateArr[$obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]][$key] = $value;
      
    }
    $allLicUpdateArr[$obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]]["oldUpdateLic"][] = $obUpdateLic["ID"];
  }
  //print_r($allLicUpdateArr);
  $inobitecLicense = new inobitecLicense();
  $oldLicData = $inobitecLicense->getClientLicenses($arUser["UF_LIC_SERV_ID"]);

  foreach($allLicUpdateArr as $licData){
    updateLicenseToAPIParseData($licData, $inobitecLicense, $oldLicData);
    sleep(1);
  }
  
}

function updateLicenseToAPIParseData($obUpdateLic, $inobitecLicense, $oldLicData){
  
 // print_r($obUpdateLic);
  
  //print_r("<br><br><br>");
  
  $obLicense = $obUpdateLic["oldLicDataInfo"];
  $goodData = $obUpdateLic["goodDataInfo"];
  $dtime = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
  $datetime1 = new DateTime();
  $interval = $dtime->diff($datetime1);
  $yearDif = $interval->format('%y');
  
  if($interval->invert == 0)
    $yearDif = 0;
  
  
  $years = (int)$yearDif;
  if(isset($obUpdateLic["PROPERTY_YEARS_VALUE"]))
    $years += (int)$obUpdateLic["PROPERTY_YEARS_VALUE"];
  $type = $goodData["PROPERTY_UNIQUE_CODE_VALUE"];
  $productName = $goodData["PROPERTY_SERVER_LIC_TYPE_CODE_VALUE"];
  $modules = false;
  if(is_array($obLicense["PROPERTY_MODULES_INFO_VALUE"]) && count($obLicense["PROPERTY_MODULES_INFO_VALUE"]) > 0 )
    $modules = getModulesApiInfo($obLicense["PROPERTY_MODULES_INFO_VALUE"]);
  
  
  if($obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "05" || $obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "10"){
    //addToLog("TEST UPDATE LICENSE 05 - 10 ");
    if($obUpdateLic["PROPERTY_IF_LIC_UPDATE_VALUE"] != 1)
      return false;
   // print_r("UpdateViewer");
    //Если срок истек
    if($interval->invert == 0){
        //addToLog("TEST UPDATE LICENSE OLD");
        $days = customCatalog::convertYearsToDays((int)$obUpdateLic["PROPERTY_YEARS_VALUE"]);
        if($days)
          $years = false;
        $rez = $inobitecLicense->changePermanentLicense($obLicense["PROPERTY_LICSERVER_ID_VALUE"], $productName, $modules, $type, $years, $days);
        //print_r($rez);
        //addToLog(print_r($rez, true));
        $license = array_shift($rez);
        if($license["livingState"] == "changed")
          $license = array_shift($rez);
        //print_r($license);
        //addToLog(print_r($license, true));


        $name = "Лицензия " . $license["productName"] . " для пользователя " . $obLicense["PROPERTY_USER_ID_VALUE"];
        $varGenTime = $varActTime = $varSupportEndTime = false;
        if($license["generationDate"])
          $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
        if($license["activationDate"])
          $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
        if($license["supportExpirationDate"])
          $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();

        $el = new CIBlockElement;
        $propVal = array(
          "GOOD_INFO" => $obLicense["PROPERTY_GOOD_INFO_VALUE"],
          "SUPPORT_YEARS" =>  $years,  
          "MODULES_INFO" => $obLicense["PROPERTY_MODULES_INFO_VALUE"],
          "USER_ID"   => $obLicense["PROPERTY_USER_ID_VALUE"],
          "LICENSE_KEY" => $license["key"],
          "SUPPORTED_PO"  => (isset($license['type']['deprecated']) && !$license['type']['deprecated']) ? 1 : 0,  
          "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
          "COMP_CODE"       => $license["device"]["key"],  
          "LICSERVER_ID"  => $license["id"],
          "API_PRODUCT_NAME"  => $license["productName"],
          "API_PRODUCT_TYPE" => 11, 
          "LICENSE_CODE" =>   $obLicense["PROPERTY_LICENSE_CODE_VALUE"],
          "PRIVIOUS_LICENSE_ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]
        );
        if($varActTime){
          $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
        }
        if($varSupportEndTime){
          $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
        }
        $arLoadProductArray = Array(
          "IBLOCK_SECTION_ID" => false,
          "IBLOCK_ID"         => 35,
          "CODE"              => false,
          "NAME"              => $name,
          "ACTIVE"            => "Y",            // активен
          "PROPERTY_VALUES"   =>  $propVal,  
          );
        if($id = $el->Add($arLoadProductArray)){
          //print_r("OK  " . $id);
          //addToLog("OK  " . $id);
          
          $propOldLicNewVal = Array(
            "NEXT_LICENSE_ID" =>  $id,
          );
          $checkRez = CIBlockElement::SetPropertyValuesEx($obUpdateLic["PROPERTY_LICENSE_ID_VALUE"],false,$propOldLicNewVal);
          //print_r($checkRez);

          $arUpdateLicArray = Array(
            "ACTIVE" => "N"
          );
          $s_res = $el->Update($obUpdateLic["ID"], $arUpdateLicArray);
          $propLicUpdateObNewVal = Array(
            "UPDATE_LICENSE_ID" =>  $id,
          );
          CIBlockElement::SetPropertyValuesEx($obUpdateLic["ID"],false,$propLicUpdateObNewVal);
        }else{
         // print_r($el->LAST_ERROR);
          addToLog($el->LAST_ERROR);
        }
    }else{
      //addToLog("TEST UPDATE LICENSE NEW");
      //addToLog(print_r($oldLicData,true));
      $prevDays = $interval->days;
      foreach($oldLicData["online"] as $key => $oldLicDataArr){
        if($obLicense["PROPERTY_LICSERVER_ID_VALUE"] == $oldLicDataArr["id"]){
          print_r("<br>");
          print_r($oldLicDataArr);
          print_r("<br>");
          //addToLog(print_r($oldLicDataArr,true));
          $prevDays = $oldLicDataArr["supportDayCount"];
        }
      }
      /*print_r("<br>");
      print_r("PrevDays - ".$prevDays);
      print_r("<br>");*/
      //addToLog("PrevDays - ".$prevDays);
      $days = customCatalog::convertYearsToDays((int)$obUpdateLic["PROPERTY_YEARS_VALUE"],$prevDays);
      /*print_r("<br>");
      print_r("UpdateDays - ".$days);
      print_r("<br>");*/
      //addToLog("UpdateDays - ".$days);
      //print_r(array($obLicense["PROPERTY_LICSERVER_ID_VALUE"], false, $days));
      //addToLog(print_r(array($obLicense["PROPERTY_LICSERVER_ID_VALUE"], false, $days),true));
      if($days)
        $years = false;
      $rez = $inobitecLicense->updatePermanentLicense($obLicense["PROPERTY_LICSERVER_ID_VALUE"], false, $days);
      //print_r($rez);
      $license = $rez;
      if($license["livingState"] == "changed")
        $license = array_shift($rez);
      //print_r($license);
      
      /*addToLog(print_r($rez, true));
      addToLog(print_r($license, true));*/
      if($license["generationDate"])
        $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
      if($license["activationDate"])
        $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
      if($license["supportExpirationDate"])
        $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();
      if($varSupportEndTime){
        $propVal = array();
        $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
        CIBlockElement::SetPropertyValuesEx($obLicense["ID"],false,$propVal);
      }
      $arUpdateLicArray = Array(
        "ACTIVE" => "N"
      );
      $el = new CIBlockElement;
      $s_res = $el->Update($obUpdateLic["ID"], $arUpdateLicArray);
      
      
    }
    //print_r($data);
  }elseif($obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "00"){
    $connections = $obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"];
    if(isset($obUpdateLic["PROPERTY_SERVER_CONNECTIONS_VALUE"]))
      $connections+= $obUpdateLic["PROPERTY_SERVER_CONNECTIONS_VALUE"];
    $deviceKey = $obLicense["PROPERTY_COMP_CODE_VALUE"];
    $userID = getUserApiID($obLicense["PROPERTY_USER_ID_VALUE"]);
    print_r("UpdateServer:");
    
    
    if(isset($obUpdateLic["PROPERTY_YEARS_VALUE"])){
      /*print_r("<br> UpdateServerYears:");
      print_r(" UserID:".$userID);
      print_r(" type:".$type);
      print_r(" productName:".$productName);
      print_r(" deviceKey:".$deviceKey);
      print_r(" years:".$years);
      print_r(" connections:".$connections);*/
      
        
      $days = customCatalog::convertYearsToDays((int)$obUpdateLic["PROPERTY_YEARS_VALUE"]);
      if($days)
        $years = false;
      if($interval->invert == 1)
        $days += $interval->days + 1;
      $rez = $inobitecLicense->createNewOfflinetLicense(
          $userID,
          $type,
          $productName,
          $deviceKey,  
          $years,
          $connections,
          false,
          $days    
        );
    }else{
      $activationTime = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_ACTIVATION_TIME_VALUE"]);
      $activationData = $activationTime->format('Y-m-d');
      $years = $obLicense["PROPERTY_SUPPORT_YEARS_VALUE"];
      
      /*print_r("<br> UpdateOnlyConns:");
      print_r(" UserID:".$userID);
      print_r(" type:".$type);
      print_r(" productName:".$productName);
      print_r(" deviceKey:".$deviceKey);
      print_r(" years:".$years);
      print_r(" connections:".$connections);
      print_r(" activationData:".$activationData);*/
      $days = customCatalog::convertYearsToDays((int)$obUpdateLic["PROPERTY_YEARS_VALUE"]);
      if($days)
        $years = false;
      $rez = $inobitecLicense->createNewOfflinetLicense(
          $userID,
          $type,
          $productName,
          $deviceKey,  
          $years,
          $connections,
          $activationData,
          $days    
        );
    }
    /*print_r($rez);
    addToLog(print_r($rez, true));*/
    $license = $rez;
    /*print_r($license);
    addToLog(print_r($license, true));*/
    $name = "Лицензия " . $license["productName"] . " для пользователя " . $obLicense["PROPERTY_USER_ID_VALUE"];
    $varGenTime = $varActTime = $varSupportEndTime = false;
    if($license["generationDate"])
      $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
    if($license["activationDate"])
      $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
    if($license["supportExpirationDate"])
      $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();
    
    $el = new CIBlockElement;
    $propVal = array(
      "GOOD_INFO" => $obLicense["PROPERTY_GOOD_INFO_VALUE"],
      "SUPPORT_YEARS" =>  $years, 
      "MODULES_INFO" => $obLicense["PROPERTY_MODULES_INFO_VALUE"],
      "USER_ID"   => $obLicense["PROPERTY_USER_ID_VALUE"],
      "LICENSE_KEY" => $license["key"],
      "SUPPORTED_PO"  => (isset($license['type']['deprecated']) && !$license['type']['deprecated']) ? 1 : 0,  
      "SERVER_CONNECTIONS" => $connections,
      "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
      "COMP_CODE"       => $license["device"]["key"],  
      "LICSERVER_ID"  => $license["id"],
      "API_PRODUCT_NAME"  => $license["productName"],
      "API_PRODUCT_TYPE" => 12, 
      "LICENSE_CODE" =>   $obLicense["PROPERTY_LICENSE_CODE_VALUE"],
      "PRIVIOUS_LICENSE_ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]
    );
    if($varActTime){
      $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
    }
    if($varSupportEndTime){
      $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
    }
    $arLoadProductArray = Array(
      "IBLOCK_SECTION_ID" => false,
      "IBLOCK_ID"         => 35,
      "CODE"              => false,
      "NAME"              => $name,
      "ACTIVE"            => "Y",            // активен
      "PROPERTY_VALUES"   =>  $propVal,  
      );
    if($id = $el->Add($arLoadProductArray)){
      /*print_r("OK  " . $id);
      addToLog("OK  " . $id);*/
      
      $propOldLicNewVal = Array(
        "NEXT_LICENSE_ID" =>  $id,
      );
      $ts_res = CIBlockElement::SetPropertyValuesEx($obUpdateLic["PROPERTY_LICENSE_ID_VALUE"], false,$propOldLicNewVal);
     /* print_r($ts_res);
      addToLog(print_r($ts_res, true));*/
      $propLicUpdateObNewVal = Array(
        "UPDATE_LICENSE_ID" =>  $id,
      );
      foreach($obUpdateLic["oldUpdateLic"] as $obUpdateLicBitID){
        $arUpdateLicArray = Array(
          "ACTIVE" => "N"
        );
        $s_res = $el->Update($obUpdateLicBitID, $arUpdateLicArray);
       /* print_r("<br>Try to update obUpdateLic " . $obUpdateLic["ID"] . " -- ");
        addToLog("Try to update obUpdateLic " . $obUpdateLic["ID"] . " -- ");
        print_r($propLicUpdateObNewVal);
        addToLog(print_r($propLicUpdateObNewVal, true));
        print_r("<br>");*/
        $ts_res = CIBlockElement::SetPropertyValuesEx($obUpdateLicBitID, false,$propLicUpdateObNewVal);
      }
    }
    
     print_r("UPDATE OFFLINE SERVER LICENSE");
  }
  
}

function updateLicenseToAPI($updateLicenseID){
  $availableLicenseTypes = array("05", "10", "00");
  
  $resUpdateLic = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "ID" => $updateLicenseID, "ACTIVE" => "Y"),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_LICENSE_ID", "PROPERTY_IF_LIC_UPDATE", "PROPERTY_YEARS")
          );
  $obUpdateLic = $resUpdateLic->getNext();
  if(!$obUpdateLic)
    return false;
  if(!$obUpdateLic["PROPERTY_LICENSE_ID_VALUE"])
    return false;
  
  
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_LICENSE_KEY", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_LICENSE_CODE", "PROPERTY_SUPPORT_END_TIME")
          );
  $obLicense = $resLicense->getNext();
  
  if(!in_array($obLicense["PROPERTY_LICENSE_CODE_VALUE"], $availableLicenseTypes))
      return false;
  
  if(!$obLicense)
    return false;
  $goodData = getLicenseApiData($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
  if(!$goodData)
    return false;
  
  $dtime = DateTime::createFromFormat("d.m.Y G:i:s", $obLicense["PROPERTY_SUPPORT_END_TIME_VALUE"]);
  $datetime1 = new DateTime();
  $interval = $dtime->diff($datetime1);
  $yearDif = $interval->format('%y');
  
  if($interval->invert == 0)
    $yearDif = 0;
  
  
  $years = (int)$obUpdateLic["PROPERTY_YEARS_VALUE"] + (int)$yearDif;
  $type = $goodData["PROPERTY_UNIQUE_CODE_VALUE"];
  $productName = $goodData["PROPERTY_SERVER_LIC_TYPE_CODE_VALUE"];
  $modules = false;
  if(is_array($obLicense["PROPERTY_MODULES_INFO_VALUE"]) && count($obLicense["PROPERTY_MODULES_INFO_VALUE"]) > 0 )
    $modules = getModulesApiInfo($obLicense["PROPERTY_MODULES_INFO_VALUE"]);
  
  $inobitecLicense = new inobitecLicense();
  if($obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "05" || $obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "10"){
    if($obUpdateLic["PROPERTY_IF_LIC_UPDATE_VALUE"] != 1)
      return false;
    $rez = $inobitecLicense->changePermanentLicense($obLicense["PROPERTY_LICSERVER_ID_VALUE"], $productName, $modules, $type, $years);
    print_r($rez);
    $license = array_shift($rez);
    if($license["livingState"] == "changed")
      $license = array_shift($rez);
    print_r($license);
    
    
    $name = "Лицензия " . $license["productName"] . " для пользователя " . $obLicense["PROPERTY_USER_ID_VALUE"];
    $varGenTime = $varActTime = $varSupportEndTime = false;
    if($license["generationDate"])
      $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
    if($license["activationDate"])
      $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
    if($license["supportExpirationDate"])
      $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();
    
    $el = new CIBlockElement;
    $propVal = array(
      "GOOD_INFO" => $obLicense["PROPERTY_GOOD_INFO_VALUE"],
      "MODULES_INFO" => $obLicense["PROPERTY_MODULES_INFO_VALUE"],
      "USER_ID"   => $obLicense["PROPERTY_USER_ID_VALUE"],
      "LICENSE_KEY" => $license["key"],
      "SUPPORTED_PO"  => (isset($license['type']['deprecated']) && !$license['type']['deprecated']) ? 1 : 0,  
      "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
      "COMP_CODE"       => $license["device"]["key"],  
      "LICSERVER_ID"  => $license["id"],
      "API_PRODUCT_NAME"  => $license["productName"],
      "API_PRODUCT_TYPE" => 11, 
      "LICENSE_CODE" =>   $license["type"]["code"],
      "PRIVIOUS_LICENSE_ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]
    );
    if($varActTime){
      $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
    }
    if($varSupportEndTime){
      $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
    }
    $arLoadProductArray = Array(
      "IBLOCK_SECTION_ID" => false,
      "IBLOCK_ID"         => 35,
      "CODE"              => false,
      "NAME"              => $name,
      "ACTIVE"            => "Y",            // активен
      "PROPERTY_VALUES"   =>  $propVal,  
      );
    if($id = $el->Add($arLoadProductArray)){
      print_r("OK  " . $id);
      $propUpdateVal = Array(
        "UPDATE_LICENSE_ID" =>  $id,
      );
      CIBlockElement::SetPropertyValuesEx($obLicense["ID"], $obUpdateLic["IBLOCK_ID"],$propUpdateVal);
      $arUpdateLicArray = Array(
        "ACTIVE" => "N"
      );
      $s_res = $el->Update($obUpdateLic["ID"], $arUpdateLicArray);
    }else
      print_r($el->LAST_ERROR);
    
    //print_r($data);
  }elseif($obLicense["PROPERTY_LICENSE_CODE_VALUE"] == "00"){
    $connections = $obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"];
    $deviceKey = $obLicense["PROPERTY_COMP_CODE_VALUE"];
    $userID = getUserApiID($obLicense["PROPERTY_USER_ID_VALUE"]);
    $rez = $inobitecLicense->createNewOfflinetLicense(
          $userID,
          $type,
          $productName,
          $deviceKey,  
          $years,
          $connections
        );
    $license = array_shift($rez);
    $name = "Лицензия " . $license["productName"] . " для пользователя " . $obLicense["PROPERTY_USER_ID_VALUE"];
    $varGenTime = $varActTime = $varSupportEndTime = false;
    if($license["generationDate"])
      $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
    if($license["activationDate"])
      $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
    if($license["supportExpirationDate"])
      $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();
    
    $el = new CIBlockElement;
    $propVal = array(
      "GOOD_INFO" => $obLicense["PROPERTY_GOOD_INFO_VALUE"],
      "MODULES_INFO" => $obLicense["PROPERTY_MODULES_INFO_VALUE"],
      "USER_ID"   => $obLicense["PROPERTY_USER_ID_VALUE"],
      "LICENSE_KEY" => $license["key"],
      "SUPPORTED_PO"  => (isset($license['type']['deprecated']) && !$license['type']['deprecated']) ? 1 : 0,  
      "GENERATION_TIME"  => ConvertTimeStamp($varGenTime, "FULL"),
      "COMP_CODE"       => $license["device"]["key"],  
      "LICSERVER_ID"  => $license["id"],
      "API_PRODUCT_NAME"  => $license["productName"],
      "API_PRODUCT_TYPE" => 12, 
      "LICENSE_CODE" =>   $license["type"]["code"],
      "PRIVIOUS_LICENSE_ID" => $obUpdateLic["PROPERTY_LICENSE_ID_VALUE"]
    );
    if($varActTime){
      $propVal["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
    }
    if($varSupportEndTime){
      $propVal["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
    }
    $arLoadProductArray = Array(
      "IBLOCK_SECTION_ID" => false,
      "IBLOCK_ID"         => 35,
      "CODE"              => false,
      "NAME"              => $name,
      "ACTIVE"            => "Y",            // активен
      "PROPERTY_VALUES"   =>  $propVal,  
      );
    if($id = $el->Add($arLoadProductArray)){
      print_r("OK  " . $id);
      $propUpdateVal = Array(
        "UPDATE_LICENSE_ID" =>  $id,
      );
      CIBlockElement::SetPropertyValuesEx($obLicense["ID"], $obUpdateLic["IBLOCK_ID"],$propUpdateVal);
      $arUpdateLicArray = Array(
        "ACTIVE" => "N"
      );
      $s_res = $el->Update($obUpdateLic["ID"], $arUpdateLicArray);
    }
    
     print_r("UPDATE OFFLINE SERVER LICENSE");
  }
  
  
}

function addLicenseToAPI($licenseID, $inobitecLicense){
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $licenseID, "ACTIVE" => "N"),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_LICENSE_KEY", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS")
          );
  $obLicense = $resLicense->getNext();
  if(!$obLicense)
    return false;
  if($obLicense["PROPERTY_LICSERVER_ID_VALUE"] || $obLicense["PROPERTY_LICENSE_KEY_VALUE"])
    return false;
  $userID = getUserApiID($obLicense["PROPERTY_USER_ID_VALUE"]);
  $years = $obLicense["PROPERTY_SUPPORT_YEARS_VALUE"];
  $goodData = getLicenseApiData($obLicense["PROPERTY_GOOD_INFO_VALUE"]);
  if(!$goodData)
    return false;
  $type = $goodData["PROPERTY_UNIQUE_CODE_VALUE"];
  $productName = $goodData["PROPERTY_SERVER_LIC_TYPE_CODE_VALUE"];
  $modules = false;
  if(is_array($obLicense["PROPERTY_MODULES_INFO_VALUE"]) && count($obLicense["PROPERTY_MODULES_INFO_VALUE"]) > 0 )
    $modules = getModulesApiInfo($obLicense["PROPERTY_MODULES_INFO_VALUE"]);
  /*print_r();
  print_R("<br>");
  print_R("UserID - " . $userID);
  print_R("<br>");
  print_R("Years - " . $years);
  print_R("<br>");
  print_R("Type - " . $type);
  print_R("<br>");
  print_R("ProductName - " . $productName);
  print_R("<br>");
  print_R("Modules - ");print_R($modules);
  print_R("<br>");*/
  //return;
  if(!$userID || !$type || !$productName || !$years)
    return false;
  if($obLicense["PROPERTY_LICSERVER_ID_VALUE"] || $obLicense["PROPERTY_LICENSE_KEY_VALUE"])
    return false;
  
  $license = false;
  $days = customCatalog::convertYearsToDays($years);
  if($days)
     $years = false;
  if($productName == "pacs_server"){
    //print_r("addLicServer");
    $deviceKey = $obLicense["PROPERTY_COMP_CODE_VALUE"];
    $connections = $obLicense["PROPERTY_SERVER_CONNECTIONS_VALUE"];
    if(!$deviceKey || !$connections)
      return false;
    /*addToLog("addLicServer2");
    addToLog(print_r(array( $userID,
                            $type,
                            $productName,
                            $deviceKey,  
                            $years,
                            $connections,
                            false,  
                            $days), 
                      true)
            );*/
   
    $rez = $inobitecLicense->createNewOfflinetLicense(
          $userID,
          $type,
          $productName,
          $deviceKey,  
          $years,
          $connections,
          false,  
          $days  
        );
     $license = $rez;
  }else{
    /*addToLog("addLicViewer");
    addToLog(print_r(array($userID,
                            $type,
                            $productName,
                            $modules,
                            $years,
                            $days), 
                      true)
            );*/
    $rez = $inobitecLicense->createNewPermanentLicense(
          $userID,
          $type,
          $productName,
          $modules,
          $years,
          $days  
        );
    //addToLog(print_r($rez, true));
    $license = array_shift($rez);
    
  }
  /*addToLog("Show me license data");
  addToLog(print_r($license, true));*/
  if(!$license)
    return false;
  if(isset($license["id"])){
    $varGenTime = $varActTime = $varSupportEndTime = false;
    if($license["generationDateTime"])
      $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDateTime"])->getTimestamp();
    if($license["generationDate"])
      $varGenTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["generationDate"])->getTimestamp();
    if($license["activationDateTime"])
      $varActTime = DateTime::createFromFormat('Y-n-j H:i:s',$license["activationDateTime"])->getTimestamp();
    if($license["activationDate"])
      $varActTime = DateTime::createFromFormat('Y-n-j',$license["activationDate"])->getTimestamp();
    if($license["supportExpirationDate"])
      $varSupportEndTime = DateTime::createFromFormat('Y-n-j',$license["supportExpirationDate"])->getTimestamp();
      $arrValues = array(
            "LICENSE_KEY" => $license["key"],
            "LICSERVER_ID"  => $license["id"],
            "API_PRODUCT_NAME"  => $license["productName"],
            "SUPPORTED_PO"  => (isset($license['type']['deprecated']) && !$license['type']['deprecated']) ? 1 : 0,
         );
      if($varGenTime)
        $arrValues["GENERATION_TIME"] = ConvertTimeStamp($varGenTime, "FULL");
      if($varActTime)
        $arrValues["ACTIVATION_TIME"] = ConvertTimeStamp($varActTime, "FULL");
      if($varSupportEndTime)
        $arrValues["SUPPORT_END_TIME"] = ConvertTimeStamp($varSupportEndTime, "FULL");
      /*addToLog("CheckSendData");
      addToLog(print_r($arrValues, true));*/
      //print_r("<br>CheckSendData<br>");
      //print_r($arrValues);
      CIBlockElement::SetPropertyValuesEx($obLicense["ID"], $obLicense["IBLOCK_ID"],$arrValues);
      $el = new CIBlockElement;
      $arLoadProductArray = Array(
          "ACTIVE" => "Y"
      );
      $s_res = $el->Update($obLicense["ID"], $arLoadProductArray);
  }
}

function getUserApiID($userId){
  $rsUser = CUser::GetByID($userId);
  $arUser = $rsUser->Fetch();
  return $arUser["UF_LIC_SERV_ID"];
}

function getLicenseApiData($productId){
  if(!$productId)
    return false;
  $resProducts = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'goods_s1', 'ID' => $productId),
              false,
              false,
              array('ID', "NAME", "PROPERTY_UNIQUE_CODE", "PROPERTY_SERVER_LIC_TYPE_CODE")
          );
  return $resProducts->getNext();
}

function getModulesApiInfo($arrModulesId){
  $arrModulesApiIDs = array();
  $resModules = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'modules_s1', 'ID' => $arrModulesId),
              false,
              false,
              array('ID', "NAME", "PROPERTY_CODE_FROM_SERVER")
          );
  while($obModule = $resModules->getNext()){
    $arrModulesApiIDs[] = $obModule["PROPERTY_CODE_FROM_SERVER_VALUE"];
  }
  return $arrModulesApiIDs;
}

function getLicenseUniqueTypes(){
  $resProducts = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'goods_s1'),
              false,
              false,
              array('ID', "NAME", "PROPERTY_UNIQUE_CODE")
          );
  $arrUniqueTypes = array();
  while($obProducts = $resProducts->getNext()){
    $arrUniqueTypes[$obProducts["ID"]] = $obProducts["PROPERTY_UNIQUE_CODE_VALUE"];
  }
  return $arrUniqueTypes;
}

function getLicenseOrderItemsExisted($orderId){
  $existedLicenses = array();
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_ORDER_ID" => $orderId),
              false,
              false,
              array('ID', "NAME", "PROPERTY_BASKETITEM_ID")
          );
  while($obLicense = $resLicense->getNext()){
    $existedLicenses[] = $obLicense["PROPERTY_BASKETITEM_ID_VALUE"];
  }
  
  $resUpdateLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "PROPERTY_ORDER_ID" => $orderId),
              false,
              false,
              array('ID', "NAME", "PROPERTY_BASKETITEM_ID")
          );
  while($obUpdateLicense = $resUpdateLicense->getNext()){
    $existedLicenses[] = $obUpdateLicense["PROPERTY_BASKETITEM_ID_VALUE"];
  }
  return $existedLicenses;
  
}

function orderToLicense($orderID){
  $arResult = array();
  $arOrder = CSaleOrder::GetByID($orderID);
  if(!$arOrder)
    return;
  $arrUniqueTypes = getLicenseUniqueTypes();
  $existedLics = getLicenseOrderItemsExisted($orderID);
  $rsBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $orderID));
  while ($arBasketItem = $rsBasketItems->Fetch()) {
      $arBasketItem["PROPS"] = Array();
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arBasketItem["ID"]));
      while($arProp = $dbProp -> GetNext()){
       $arBasketItem["PROPS"][] = $arProp;
      }
      $arResult["ITEMS"][] = $arBasketItem;
      //$arResult["PRODUCT_IDS"][] = $arItem["PRODUCT_ID"];
  }
  $arResult["ITEMS"] = devideOrder($arResult["ITEMS"]);
  
  $viewerYears = 1;
  
  $serverYears = 1;
  
 
  $el = new CIBlockElement;
  foreach($arResult["ITEMS"]["lite"] as $newLite){
    $liteYears = $viewerYears;
    if(in_array($newLite["ID"], $existedLics))
      continue;
    if(isset($newLite["license"])){
      $viewerLic = reset($newLite["license"]);
      $liteYears += getOrderItemPropByCode($viewerLic["PROPS"], "YEARS");
    }
    $PROP = array();
    $PROP["USER_ID"] = $arOrder["USER_ID"];
    $PROP["ORDER_ID"] = $orderID;
    $PROP["BASKETITEM_ID"] = $newLite["ID"];
    $PROP["GOOD_INFO"] = $newLite["PRODUCT_ID"];
    $PROP["LICENSE_CODE"] = $arrUniqueTypes[$newLite["PRODUCT_ID"]];
    $PROP["SUPPORT_YEARS"] = $liteYears;
    $PROP["API_PRODUCT_TYPE"] = 11;

    $arLoadLicenseArray = Array(
      "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
      "IBLOCK_ID"      => 35,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => "Лицензия ".$arrUniqueTypes[$newLite["PRODUCT_ID"]]." пользователю " . $arOrder["USER_ID"] . " заказа №".$orderID,
      "ACTIVE"         => "N"
      );
    for($i = 1; $i <= $newLite["QUANTITY"]; $i++){
      $el->Add($arLoadLicenseArray);
    }
  }
  
  foreach($arResult["ITEMS"]["pro"] as $newPro){
    $proYears = $viewerYears;
    if(in_array($newPro["ID"], $existedLics))
      continue;
    $modules = array();
    if(isset($newPro["license"])){
      $viewerLic = reset($newPro["license"]);
      $proYears += getOrderItemPropByCode($viewerLic["PROPS"], "YEARS");
    }
    if(isset($newPro["modules"]))
      foreach($newPro["modules"] as $module)
        $modules[] = $module["PRODUCT_ID"];
    $PROP = array();
    $PROP["USER_ID"] = $arOrder["USER_ID"];
    $PROP["ORDER_ID"] = $orderID;
    $PROP["BASKETITEM_ID"] = $newPro["ID"];
    $PROP["GOOD_INFO"] = $newPro["PRODUCT_ID"];
    $PROP["MODULES_INFO"] = $modules;
    $PROP["LICENSE_CODE"] = $arrUniqueTypes[$newPro["PRODUCT_ID"]];
    $PROP["SUPPORT_YEARS"] = $proYears;
    $PROP["API_PRODUCT_TYPE"] = 11;

    $arLoadLicenseArray = Array(
      "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
      "IBLOCK_ID"      => 35,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => "Лицензия ".$arrUniqueTypes[$newPro["PRODUCT_ID"]]." пользователю " . $arOrder["USER_ID"] . " заказа №".$orderID,
      "ACTIVE"         => "N"
      );
    for($i = 1; $i <= $newPro["QUANTITY"]; $i++){
      $el->Add($arLoadLicenseArray);
    }
  }

  
  foreach($arResult["ITEMS"]["server"] as $newServ){
    $serverYearsRez =  $serverYears;
    if(isset($newServ["license"])){
      $serverLic = reset($newServ["license"]);
      $serverYearsRez += getOrderItemPropByCode($serverLic["PROPS"], "YEARS");
    }
    
    if(in_array($newServ["ID"], $existedLics))
      continue;
    
    $resConnections = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'servers_s1', 'ID' => $newServ["PRODUCT_ID"]),
              false,
              false,
              array('ID', "NAME", "PROPERTY_CONNECTIONS")
          );
  
    $obConnections = $resConnections->getNext();

    $serverID = CCatalogSku::GetProductInfo($newServ["PRODUCT_ID"]);
    $PROP = array();
    $PROP["USER_ID"] = $arOrder["USER_ID"];
    $PROP["GOOD_INFO"] = $serverID["ID"];
    $PROP["ORDER_ID"] = $orderID;
    $PROP["BASKETITEM_ID"] = $newServ["ID"];
    $PROP["LICENSE_CODE"] = $arrUniqueTypes[$serverID["ID"]];
    $PROP["SUPPORT_YEARS"] = $serverYearsRez;
    $PROP["COMP_CODE"] = getOrderItemPropByCode($newServ["PROPS"], "PRODUCT_CODE");
    $PROP["SERVER_CONNECTIONS"] = $obConnections["PROPERTY_CONNECTIONS_VALUE"];
    $PROP["API_PRODUCT_TYPE"] = 12;
    
    

    $arLoadLicenseArray = Array(
      "IBLOCK_SECTION_ID"   => false,          // элемент лежит в корне раздела
      "IBLOCK_ID"           => 35,
      "PROPERTY_VALUES"     => $PROP,
      "NAME"                => "Лицензия ".$arrUniqueTypes[$serverID["ID"]]." пользователю " . $arOrder["USER_ID"] . " заказа №".$orderID,
      "ACTIVE"              => "N"
      );
    $el->Add($arLoadLicenseArray);
  }
  
  foreach($arResult["ITEMS"]["updateArr"] as $newUpdate){
    if(in_array($newUpdate["ID"], $existedLics))
      continue;
    $newYears = getOrderItemPropByCode($newUpdate["PROPS"], "YEARS");
    $oldLicID = getOrderItemPropByCode($newUpdate["PROPS"], "LICENSE_ID");
    $newConnections = getOrderItemPropByCode($newUpdate["PROPS"], "CONNECTIONS");
    $PROP = array();
    $PROP["USER_ID"] = $arOrder["USER_ID"];
    $PROP["ORDER_ID"] = $orderID;
    $PROP["BASKETITEM_ID"] = $newUpdate["ID"];
    $PROP["LICENSE_ID"] = $oldLicID;
    
    if($newYears){
      $PROP["YEARS"] = $newYears;
      $PROP["IF_LIC_UPDATE"] = 1;
      $PROP["IF_ADD_SERV_CONN"] = 0;
      $name = "Продление лицензии № ".$oldLicID." пользователю " . $arOrder["USER_ID"] . " заказа №".$orderID;
    }
    if($newConnections){
      $PROP["SERVER_CONNECTIONS"] = $newConnections;
      $PROP["IF_LIC_UPDATE"] = 0;
      $PROP["IF_ADD_SERV_CONN"] = 1;
      $name = "Дополнительные подключения для лицензии № ".$oldLicID." пользователю " . $arOrder["USER_ID"] . " заказа №".$orderID;
    }
    
    $arLoadUpdateLicenseArray = Array(
      "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
      "IBLOCK_ID"      => 39,
      "PROPERTY_VALUES"=> $PROP,
      "NAME"           => $name,
      "ACTIVE"         => "Y"
      );
    $el->Add($arLoadUpdateLicenseArray);
  }
}

function str_replace_first($from, $to, $content)
{
   $from = '/'.preg_quote($from, '/').'/';
   return preg_replace($from, $to, $content, 1);
}

function prepareOrderItemsForMail($orderID)
{
  $arResult = array();
  $rsBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $orderID));
  while ($arBasketItem = $rsBasketItems->Fetch()) {
      $arBasketItem["PROPS"] = Array();
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arBasketItem["ID"]));
      while($arProp = $dbProp -> GetNext()){
        if($arProp["CODE"] == "NAME_EN")
          $arBasketItem["NAME_EN_CUSTOM"] = $arProp["VALUE"];
       $arBasketItem["PROPS"][] = $arProp;
      }
      $arResult["ITEMS"][] = $arBasketItem;
  }
  $str = '';
  $arResult["ITEMS"] = sortOrder($arResult["ITEMS"]);
  foreach($arResult["ITEMS"] as $item){
    $str .= $item["NAME"] ;
    foreach($item["PROPS"] as $arrProp){
      if($arrProp["CODE"] == "YEARS"){
        $str .=  ". ";
        $str .= (SITE_ID == 's2') ? "Subscription extension (number of years)" : $arrProp["NAME"];
        $str .= ": " . $arrProp["VALUE"];
      }
    }
    $str .= " - " . $item["QUANTITY"];
    $str .= (SITE_ID == 's2') ? " pcs. x " : " шт. x ";
    $str .= (SITE_ID == 's1') ? number_format($item["PRICE"],0,""," ") . " &#8381;<br>" : "$ " . number_format($item["PRICE"],0,"","") . "<br>";
    $str = str_replace_first("#br#", ": ", $str);
    $str = str_replace("#br#", "; ", $str);
    
  }
  return $str;
}

function removeUpdatesFromBasket(){
  global $USER;
  $arBasketItems = array();
  $dbBasketItems = CSaleBasket::GetList(
    array(),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "ORDER_ID" => "NULL",
        //"LID" => SITE_ID
      ),
    false,
    false,
    array()
  );
  $updateLicToItems = array();
  $updateLicArr = array();
  while ($arItem = $dbBasketItems->GetNext()) {
    $arItem["PROPS"] = Array();
    $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItem["ID"], "CODE" => array("UNIQUE_CODE", "LICENSE_ID", "CONNECTIONS")));
    while($arProp = $dbProp -> GetNext()){
      if($arProp["CODE"] == "LICENSE_ID"){
        $updateLicToItems[$arProp["VALUE"]] = $arItem["ID"];
        $updateLicArr[] = $arProp["VALUE"];
      }
    }
  }
  if(count($updateLicArr) < 1)
    return false;
  $resLicense = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "ID" => $updateLicArr),
              false,
              false,
              array('ID', 'IBLOCK_ID', "NAME", "PROPERTY_USER_ID", "PROPERTY_GOOD_INFO", "PROPERTY_MODULES_INFO", "PROPERTY_SUPPORT_YEARS", "PROPERTY_LICSERVER_ID", "PROPERTY_LICENSE_KEY", "PROPERTY_COMP_CODE", "PROPERTY_SERVER_CONNECTIONS", "PROPERTY_LICENSE_CODE", "PROPERTY_SUPPORT_END_TIME", "PROPERTY_ACTIVATION_TIME")
          );
  while($obLicense = $resLicense->getNext()){
    //print_r($obLicense);
    if($obLicense["PROPERTY_USER_ID_VALUE"] != $USER->GetID()){
      CSaleBasket::Delete($updateLicToItems[$obLicense["ID"]]);

    }
  }
}

///////////////////  EVENT BLOCK //////////
AddEventHandler("main", "OnAfterUserAdd", Array("MyClass", "OnAfterUserAddHandler"));
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("MyClass", "OnBeforeIBlockElementAddHandler"));

AddEventHandler("sale", "OnBasketAdd", Array("MyClass", "CheckIfNeedRecalculate"));
AddEventHandler("sale", "OnBasketDelete", Array("MyClass", "CheckIfNeedRecalculate"));
AddEventHandler("sale", "OnBeforeOrderAdd", "OnBeforeOrderAddHandler");
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", Array("MyClass", "addLicensesForOrder"));
AddEventHandler("sale", "OnOrderPaySendEmail", "bxModifySaleMails");
AddEventHandler("sale", "OnSalePayOrder", "MyCreateAPILic");
AddEventHandler("sale", "OnOrderStatusSendEmail", "bxModifyStatusMails");
AddEventHandler("sale", "OnOrderCancelSendEmail", "bxModifyStatusMails");
AddEventHandler("main", "OnSendUserInfo", "MyOnSendUserInfoHandler");
AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMailsNewOrder");

AddEventHandler("sale", "OnSaleBeforeCancelOrder", "StopCancelOrder");
AddEventHandler("sale", "OnSaleCancelOrder", "deactivateAllLicenses");

function OnBeforeOrderAddHandler(&$arFields) {
  removeUpdatesFromBasket();
}

function StopCancelOrder($orderID, $status){
  $order = CSaleOrder::GetByID($orderID);
  if($order['PAYED']=='Y'){
    return false;
  }
}

function deactivateAllLicenses($orderID, $status, $info){
  
  $el = new CIBlockElement;
  $resUpdateLicensees = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license_update', "PROPERTY_ORDER_ID" => $orderID, 'ACTIVE' => 'Y'),
              false,
              false,
              array('ID')
          );
  while($obUpdateLic = $resUpdateLicensees->getNext()){
    $arUpdateLicArray = Array(
      "ACTIVE" => "N"
    );
    $s_res = $el->Update($obUpdateLic["ID"], $arUpdateLicArray);
  }
  
  
  $resLicensees = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_CODE' => 'license', "PROPERTY_ORDER_ID" => $orderID, 'ACTIVE' => 'Y'),
              false,
              false,
              array('ID')
          );
  while($obLic = $resLicensees->getNext()){
    $arLicArray = Array(
      "ACTIVE" => "N"
    );
    $s_res = $el->Update($obLic["ID"], $arLicArray);
  }
  
}

//-- Собственно обработчик события
function bxModifySaleMailsNewOrder($orderID, &$eventName, &$arFields)
{
  addToLog(print_r($arFields, true));
  $orderList = prepareOrderItemsForMail($orderID);
  //$arFields["ORDER_LIST"] = $orderList;
  $arFields["ORDER_CUSTOM_ITEMS"] = $orderList;
  addToLog(print_r($orderList, true));
}

function MyOnSendUserInfoHandler(&$arParams)
{
  $rsUser = CUser::GetByID($arParams['USER_ID']);
  $arUser = $rsUser->Fetch();
  if($arUser["UF_USER_TYPE"] == 2){
    $arParams['USER_ID'] = $arUser["WORK_COMPANY"];
  };
  $arParams['FIELDS']['SALE_EMAIL'] = COption::GetOptionString("sale","order_email");
  
  // теперь в шаблоне USER_INFO можно использовать макрос #CUSTOM_NAME#
}

function bxModifyStatusMails($orderID, &$eventName, &$arFields, $val){
  $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
  $Name = "";
  while ($arProps = $order_props->Fetch()){
   if($arProps["IS_PAYER"] == "Y")
    $Name = $arProps["VALUE"];
  }
  $arFields["ORDER_USER"] = $Name;
}

function bxModifySaleMails($orderID, &$eventName, &$arFields){
  $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
  $Name = "";
  while ($arProps = $order_props->Fetch()){
   if($arProps["IS_PAYER"] == "Y")
    $Name = $arProps["VALUE"];
  }
  $arFields["ORDER_USER"] = $Name;
}

function MyCreateAPILic($order_id, $status){
  if($status == "Y"){
    CSaleOrder::StatusOrder($order_id,'F');
    addOrderLicToApi($order_id);
    prepareLicenseForUpdateToAPI($order_id);
  }
}

class MyClass
{

    // создаем обработчик события "OnBeforeIBlockElementAdd"
    function OnBeforeIBlockElementAddHandler(&$arFields)
    {
      if($arFields['IBLOCK_ID'] == 35){
        $resLICID = CIBlockProperty::GetByID("LICSERVER_ID", false, "license");
        $arLICID = $resLICID->GetNext();
        $LICID = false;
        //если сохранение через админку - значения войств привязаны к ИД
        foreach($arFields['PROPERTY_VALUES'][$arLICID['ID']] as $valLICID){
          $LICID = $valLICID["VALUE"];
        }
        //если через АПИ - значения привязаны через Коды
        if(!$LICID){
          if(isset($arFields["PROPERTY_VALUES"]["LICSERVER_ID"]))
            $LICID = $arFields["PROPERTY_VALUES"]["LICSERVER_ID"];
        }
        $resLICKEY = CIBlockProperty::GetByID("LICENSE_KEY", false, "license");
        $arLICKEY = $resLICKEY->GetNext();
        $LICKEY = false;
        foreach($arFields['PROPERTY_VALUES'][$arLICKEY['ID']] as $valLICKEY){
          $LICKEY = $valLICKEY["VALUE"];
        }
        if(!$LICKEY){
          if(isset($arFields["PROPERTY_VALUES"]["LICENSE_KEY"]))
            $LICKEY = $arFields["PROPERTY_VALUES"]["LICENSE_KEY"];
        }
        if($LICID && $LICKEY){
          $arSelect = Array("ID");
          $arFilter = Array("IBLOCK_ID"=>IntVal($arFields['IBLOCK_ID']), "PROPERTY_LICSERVER_ID"=>$LICID, "PROPERTY_LICENSE_KEY"=>$LICKEY);
          $resCopy = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
          if($arrCopy = $resCopy->getNext()){
            global $APPLICATION;
            $APPLICATION->throwException("Лицензия с таким ключом и ИД кодом уже существует");
            return false;
          }
          return;
        }
      }
      return;
    }
    
    // записывает все что передадут в /bitrix/log.txt 
    function log_array() { 
       $arArgs = func_get_args(); 
       $sResult = ''; 
       foreach($arArgs as $arArg) { 
          $sResult .= "\n\n".print_r($arArg, true); 
       } 

       if(!defined('LOG_FILENAME')) { 
          define('LOG_FILENAME', $_SERVER['DOCUMENT_ROOT'].'/bitrix/log.txt'); 
       } 
       AddMessage2Log($sResult, 'log_array -> '); 
    } 

    // создаем обработчик события "OnAfterUserAdd"
    function OnAfterUserAddHandler(&$arFields)
    {
        if($arFields["ID"]>0){
          //Делаем запрос на создание пользователя на сервере лицензий
            AddMessage2Log("Запись с кодом ".$arFields["ID"]." добавлена.");
        }
        
    }
    
    function addLicensesForOrder($ID,&$arOrder, $arParam){
      orderToLicense($ID);
    }
    
    
    function CheckIfNeedRecalculate($arFields)
    {
      return false;
        if(!CModule::IncludeModule("iblock"))
          return false;
        if (CModule::IncludeModule("sale"))
        {
          $obProductInfo = CIBlockElement::GetByID($arFields["PRODUCT_ID"]);
          $productInfo = $obProductInfo->getNext();
          $resGoods = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_ID' => $productInfo["IBLOCK_ID"], "ID" => $productInfo["ID"]),
              false,
              false,
              array('ID', "NAME", "PROPERTY_LIC_TYPE")
          );
          $obGood = $resGoods->getNext();
          if(checkIfLicense($obGood)){
            recalculateLicensePrice();
          }
        }
    }
}
function checkGoogleRecaptcha(){
  $recaptcha_key = '6LdNy7wUAAAAAPFfMZ8dFc-UF9d2fcnH5iGjcKY3';
  $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $recaptcha_params = [
    'secret' => $recaptcha_key,
    'response' => $_POST['recaptcha_response'],
    'remoteip' => $_SERVER['REMOTE_ADDR'],
  ];
  $ch = curl_init($recaptcha_url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $recaptcha_params);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  if (!empty($response)) {
    $decoded_response = json_decode($response);
  }
 
  $recaptcha_success = false;
 
  if ($decoded_response && $decoded_response->score > 0) {
    $recaptcha_success = $decoded_response->score;
    if($recaptcha_success > 0.5)
      $recaptcha_success = true;
  } else {
    
  }
 
  return $recaptcha_success;
}
if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y")
{
   function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
   {
      if(CModule::IncludeModule("webprostor.smtp"))
      {
         $smtp = new CWebprostorSmtp("s1");
         $result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);
		  if($result){
            return true;
		  }else{
            return false;
		  }
      }
   }
}
?>
