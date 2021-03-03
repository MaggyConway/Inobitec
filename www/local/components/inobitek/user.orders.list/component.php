<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();
CModule::includeModule("iblock");
CModule::includeModule("catalog");
CModule::includeModule("sale");

$arrModules = array();
$arSelectMod = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_NAME_EN", 'PROPERTY_NAME_FR', 'PROPERTY_NAME_DE', 'PROPERTY_NAME_ES');//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
$arFilterMod = Array("IBLOCK_ID"=>IntVal(32));
$resMod = CIBlockElement::GetList(Array(), $arFilterMod, false, false, $arSelectMod);
while($obMod = $resMod->GetNext()){
  $arrModules[$obMod["ID"]] = $obMod;
}

$db_ptype = CSalePaySystem::GetList(array(), Array(), false, false, array('*'));
while ($ptype = $db_ptype->Fetch())
{
	$arResult["PAYSYSTEMS"][$ptype["ID"]] = $ptype;
}

$rsOrders = CSaleOrder::GetList(array("DATE_INSERT" => "DESC"), array("USER_ID" => $USER->GetID()));

while ($arOrder = $rsOrders->Fetch()) {
	$arResult["ORDERS_LIST"][$arOrder["ID"]] = $arOrder;
	$arResult["ORDERS_IDS"][] = $arOrder["ID"];
    ////// ПОЛУЧАЕМ ФАЙЛЫ ИЗ СВОЙСТВ ЗАКАЗА -- START  //////
    $dbOrderProps = CSaleOrderPropsValue::GetList(
        array("SORT" => "ASC"),
        array("ORDER_ID" => $arOrder["ID"], "CODE" => array("OFERTA_FILES", "DATA_FILES"))
    );
    if($arOrder["CANCELED"] == "N"){
      $arResult["FILES"][$arOrder["ID"]] = array();
      while($arOrderProps = $dbOrderProps->Fetch()) {
        foreach($arOrderProps["VALUE_ORIG"] as $orderFileID){

          $fileData = CFile::GetFileArray($orderFileID);
          $arResult["FILES"][$arOrder["ID"]][] = array(
              "NAME" => $fileData["ORIGINAL_NAME"],
              "FILE" => $fileData
          );
        }
      }
    }
    ////// ПОЛУЧАЕМ ФАЙЛЫ ИЗ СВОЙСТВ ЗАКАЗА -- END  //////
}
if (count($arResult["ORDERS_IDS"]) >= 1)
{
	$rs_elements = CIBlockElement::GetList(
		array("PROPERTY_ORDER_ID" => "ASC", "SORT" => "ASC"), 
		array("IBLOCK_CODE" => "orders_files", "PROPERTY_ORDER_ID" => $arResult["ORDERS_IDS"]),
		false,
		false,
		array("ID", "NAME", "PROPERTY_FILE", "PROPERTY_ORDER_ID")
	);
	while ($ar_element = $rs_elements->Fetch()) {
		$arResult["FILES"][$ar_element["PROPERTY_ORDER_ID_VALUE"]][] = array(
			"NAME" => $ar_element["NAME"],
			"FILE" => CFile::GetFileArray($ar_element["PROPERTY_FILE_VALUE"])
		);
	}
}
//Ид лицензий продленных с сайта
$db_licupdte_elemens = CIBlockElement::GetList(
	array(), array("IBLOCK_CODE" => "license_update", "PROPERTY_USER_ID" => $USER->GetID()), false, false,
	array('ID', 'PROPERTY_LICENSE_ID')
);
$userRenewalLicID = array();
while ($obLicupdteElement = $db_licupdte_elemens->getNext()) {
  $userRenewalLicID[] = $obGood["PROPERTY_LICENSE_ID_VALUE"];
}

//Получение лицензий
$db_elemens = CIBlockElement::GetList(
	array(), array("IBLOCK_CODE" => "license", "PROPERTY_USER_ID" => $USER->GetID()), false, false,
	array('ID', 'PROPERTY_*')
);
$productsIds = $modulesIds = array();
$renewalLicenses = array();
while ($obElement = $db_elemens->GetNextElement()) {
	$license = array();
	$fields = $obElement->GetFields();
	$props = $obElement->GetProperties();
    //print_r($props);
    //print_r("<br>");
	$productId = $props["GOOD_INFO"]["VALUE"];
	$modules = $props["MODULES_INFO"]["VALUE"];

	if ($productId) 
		$productsIds[] = $productId;
	
	if (!empty($modules)) 
		$modulesIds = array_merge($modulesIds, $modules);

	$modulesIblockId = $props["MODULES_INFO"]["LINK_IBLOCK_ID"];
	$productsIblockId = $props["GOOD_INFO"]["LINK_IBLOCK_ID"];
    $license["ID"] = $fields["ID"];
	foreach ($props as $K => $prop) {
      if($K == "SUPPORT_END_TIME" && $prop["VALUE"]){
        $dateArr = explode(" ",$prop["VALUE"]);
        $license[$K] = $dateArr[0];
      }elseif($K == "SUPPORTED_PO"){
        $license["SUPPORTED_PO"] = $prop["VALUE"];
      }else{
        $license[$K] = $prop["VALUE"];
      }
		
	}
    
	if ($order_id = $props["ORDER_ID"]["VALUE"]) {
		$basket_id = $props["BASKETITEM_ID"]["VALUE"];

		$lisenses_with_orders[$order_id][$basket_id][] = $license;
		
	} else {

        foreach($license["MODULES_INFO"] as $moduleId){
          $license["API_SERVER_MODULES"][$moduleId] = $arrModules[$moduleId];
        }
        
        $lisenses_without_orders[$fields['ID']] = $license;
		$arResult['PRODUCT_IDS'][] = $fields['GOOD_INFO'];
	}
}

//Получение товаров
//Получение модулей
/*if (count($modulesIds)) 
{
	$rsItem = CIBlockElement::GetList([], ['IBLOCK_ID' => $modulesIblockId, 'ID' => $modulesIds], false, false, ['ID', 'NAME', ]);
	while ($arItem = $rsItem->Fetch()) {
		$modulesNames[$arItem['ID']] = $arItem['NAME'];
		
	}
}*/
//Получение корзины
if (count($arResult["ORDERS_IDS"])) {
	$rsBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $arResult["ORDERS_IDS"]));
	while ($arBasketItem = $rsBasketItems->Fetch()) {
		$arBasketItem["PROPS"] = Array();
	   	$dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arBasketItem["ID"]));
	   	while($arProp = $dbProp -> GetNext()){
	     $arBasketItem["PROPS"][] = $arProp;
	   	}
		$arResult["BASKET_IDS"][] = $arBasketItem["ID"];
		$arBasketItem['SHORT_NAME'] = $shortNames[$arBasketItem['PRODUCT_ID']];
	    
		if (count($lisenses_with_orders[$arBasketItem["ORDER_ID"]][$arBasketItem["ID"]])) 
			$arBasketItem["LICENSES_API"] = $lisenses_with_orders[$arBasketItem["ORDER_ID"]][$arBasketItem["ID"]];
		$arResult["ORDERS_LIST"][$arBasketItem["ORDER_ID"]]["ITEMS"][] = $arBasketItem;
		$arResult["PRODUCT_IDS"][] = $arItem["PRODUCT_ID"];
	}
}
//Получение товаров
if (count($arResult['PRODUCT_IDS'])) {
	$rs_section = CIBlockSection::GetList(["SORT"=>"ASC"], ['IBLOCK_ID' => 30], false, ['ID', 'UF_TYPE']);
	while ($ar_section = $rs_section->Fetch()) {
		$product_types[$ar_section['ID']] = $ar_section['UF_TYPE'];
	}

	$rsItem = CIBlockElement::GetList([], ['IBLOCK_ID' => 30, 'ID' => $arResult['PRODUCT_IDS']], false, false, ['ID', 'NAME', 'PROPERTY_SHORT_NAME', 'IBLOCK_SECTION_ID', 'PROPERTY_NAME_EN', 'PROPERTY_SHORT_NAME_EN', 'PROPERTY_LIC_TYPE', 'PROPERTY_NAME_DE', 'PROPERTY_SHORT_NAME_DE', 'PROPERTY_NAME_FR', 'PROPERTY_SHORT_NAME_FR', 'PROPERTY_NAME_ES', 'PROPERTY_SHORT_NAME_ES']);
	while ($arItem = $rsItem->Fetch()) {
		$productsNames[$arItem['ID']] = $arItem['NAME'];
		$arResult['PRODUCTS_NAME_DATA'][$arItem['ID']] = array(
			'type' => $product_types[$arItem['IBLOCK_SECTION_ID']],
			'short_name' => $arItem['PROPERTY_SHORT_NAME_VALUE'],
			'english_name' => $arItem['PROPERTY_NAME_EN_VALUE'],
			'short_english_name' => $arItem['PROPERTY_SHORT_NAME_EN_VALUE'],
			'russian_name' => $arItem['NAME'],
            'superShortName' => $arItem['PROPERTY_LIC_TYPE_VALUE'],
            'french_name' => $arItem['PROPERTY_NAME_FR_VALUE'],
            'short_french_name' => $arItem['PROPERTY_SHORT_NAME_FR_VALUE'],
            'germany_name' => $arItem['PROPERTY_NAME_DE_VALUE'],
            'short_germany_name' => $arItem['PROPERTY_SHORT_NAME_DE_VALUE'],
            'spanish_name' => $arItem['PROPERTY_NAME_ES_VALUE'],
            'short_spanish_name' => $arItem['PROPERTY_SHORT_NAME_ES_VALUE'],
		);
	}
	if (LANGUAGE_ID == 'en') 
	{
		//Выберем английские названия для соединений сервера
		$rsItem1 = CIBlockElement::GetList(
			[], ['IBLOCK_ID' => 34, 'ID' => $arResult['PRODUCT_IDS']], false, false, ['ID', 'NAME', 'PROPERTY_NAME_EN']
		);
		while ($arItem1 = $rsItem1->Fetch()) {
			$arResult['PRODUCTS_NAME_DATA'][$arItem1['ID']]['english_name'] = $arItem1['PROPERTY_NAME_EN_VALUE'];
		}
	}
	//$rsItem = CIBlockElement::GetList()
}

foreach ($arResult["ORDERS_LIST"] as &$order) {
	$devidedOrder = devideOrder($order["ITEMS"], true);
	$devidedPro = $devidedOrder["pro"];
    
    //Пофикшены случаи дубликатов для списка товаров Pro редакции - првоерка ниже неактуальна
	if (count($devidedPro) >= 1) 
	{
		/*$modules = array();

		foreach ($devidedPro as $list_id => $arr) 
		{
			if (array_key_exists("modules", $arr)) 
			{
				$modules[$list_id] = $devidedPro[$list_id]["modules"];
				//unset($devidedPro[$list_id]);
	
			}
		}
		if (count($modules)) 
		{
			foreach ($devidedPro as &$elem) 
			{
				if ($modules[$elem['ID']]) 
				{
					$elem['modules'] = $modules[$elem['ID']];
				}
			}
		}*/
		$devidedOrder["pro"] = $devidedPro;
	}
	if (count($devidedOrder["viewerLic"])) 
	{
		if (count($devidedOrder["lite"]) >= 1 && count($devidedOrder["pro"]) >= 1) {
			$order["VIEWERLIC_TYPE"] = "lite_pro";
		} elseif (count($devidedOrder["lite"]) >= 1) {
			$order["VIEWERLIC_TYPE"] = "lite";
		} elseif (count($devidedOrder["pro"]) >= 1) {
			$order["VIEWERLIC_TYPE"] = "pro";
		}
	}
		
	$order["DEVIDED"] = $devidedOrder;
}

foreach($lisenses_without_orders as $key => $license){
  if(isset($license["PRIVIOUS_LICENSE_ID"]) && $license["PRIVIOUS_LICENSE_ID"] > 0  && in_array($license['ID'], $userRenewalLicID)){
    $renewalLicenses[$license["PRIVIOUS_LICENSE_ID"]] = $license;
    unset($lisenses_without_orders[$key]);
  }
}

//print_R($lisenses_without_orders);
$arResult['LICENSES_WITHOUT_ORDERS'] = $lisenses_without_orders;
$arResult['RENEWAL_LICENSES'] = $renewalLicenses;
$this->IncludeComponentTemplate();
?>