<?php
use Bitrix\Sale;
use Bitrix\Iblock\InheritedProperty;

class customCatalog
{

  
  public function updateBasketCurrency($siteId = "s1")
  {
    $defCurrency = ($siteId == 's1')?'RUB':'USD';
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

    
    while ($arItem = $dbBasketItems->GetNext()) {
      if($arItem['CURRENCY'] != $defCurrency){
        $serverPrice = false;
        if(checkIfServer($arItem["PRODUCT_ID"])){
			$arrParentID = CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
			$resGoods = CIBlockElement::GetList(
				array('sort' => 'asc'),
				array('IBLOCK_ID' => $arrParentID["IBLOCK_ID"], "ID" => $arrParentID["ID"]),
				false,
				false,
				array('ID', 'NAME', 'DETAIL_TEXT', 'CATALOG_GROUP_1', "PROPERTY_LIC_TYPE", "PROPERTY_SHORT_NAME", "DETAIL_PICTURE", "PROPERTY_SHORT_NAME_EN", "PROPERTY_NAME_EN", "PROPERTY_PREVIEW_TEXT_EN", "PROPERTY_IMAGE_EN", "PROPERTY_IMAGE_RU")
			);
			$obGoods = $resGoods->getNext();
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
				if($obOffer["PROPERTY_CONNECTIONS_VALUE"] == 1){
				  $priceCheck = (SITE_ID_FOR_BASKET == 's1')  ? $obOffer["CATALOG_PRICE_1"] : CCurrencyRates::ConvertCurrency($obOffer["CATALOG_PRICE_1"], "RUB", "USD");
				  $priceCheck = number_format($priceCheck, 0, '', '');
				}
			  }
			  $prop=CIBlockElement::GetByID($arItem["PRODUCT_ID"])->GetNextElement()->GetProperties();
			  $connects = $prop['CONNECTIONS']['VALUE'];
			  $serverPrice = $connects * $priceCheck;
        }
        
        $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItem["ID"], "CODE" => "ADDITIONALCONSPRICE"));
        if($arProp = $dbProp -> GetNext()){
          $pricePerConn = $arProp["VALUE"];
          $conNums = false;
          $dbPropConNum = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arItem["ID"], "CODE" => "CONNECTIONS"));
          while($arPropConnNum = $dbPropConNum -> GetNext()){
            $conNums = $arPropConnNum["VALUE"];
          }
          $priceCheck = (SITE_ID_FOR_BASKET == 's1' || $arItems["CURRENCY"] == 'USD')  ? $pricePerConn : CCurrencyRates::ConvertCurrency($pricePerConn, "RUB", "USD");
          $serverPrice = (SITE_ID_FOR_BASKET == 's1')  ? $pricePerConn * $conNums  : number_format(floor($priceCheck), 0, '', '') * $conNums;
        }
        
        if($defCurrency == 'USD'){
          if($serverPrice){
            $newPrice = $serverPrice;
          }else{
            $newPrice = CCurrencyRates::ConvertCurrency($arItem["PRICE"], "RUB", "USD");
            $newPrice = number_format(floor($newPrice), 0, '', '');
          }
        }
        $arFields = array(
          "CURRENCY" => $defCurrency,
          "PRICE" => $newPrice,
          "CUSTOM_PRICE" => "Y"  
        );
        CSaleBasket::Update($arItem['ID'], $arFields);
      }
    }
    
  }
  
  public function convertYearsToDays($years, $olddays = 0)
  {
    $nextyear  = mktime(0, 0, 0, date("m"),   date("d") + $olddays,   date("Y")+$years);
    $datenextyear = new DateTime();
    $datenextyear->setTimestamp($nextyear);
    $time = mktime(0, 0, 0, date("m"),   date("d"),   date("Y"));
    $datetime = new DateTime();
    $datetime->setTimestamp($time);
    $interval = $datetime->diff($datenextyear);
    return $interval->days;
  }
  
  protected function addToLog($msg)
  {

   file_put_contents($_SERVER['DOCUMENT_ROOT']."/eventLogs.log",
                     date("Y-m-d H:i:s")
                     . "  " . $msg . "\r\n",
                     FILE_APPEND);
  } 
  
  
  
}

