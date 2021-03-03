<?php
//exit;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("iblock"))
  return; 
if(!CModule::IncludeModule("sale"))
  return; 
if(!CModule::IncludeModule("catalog"))
  return false;

global $USER;
print_r("TEST!");
$id = CSaleBasket::GetBasketUserID();
print_r($id);
print_r($USER->id);
$dbBasketItems = CSaleBasket::GetList(
array(
		"NAME" => "ASC",
		"ID" => "ASC"
		),
array(
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL"
		),
false,
false,
array()
);
while ($arItems = $dbBasketItems->GetNext()) {
	print_r($arItems);
	print_r("<br><br>");
}
//$rez = basketInfo();
//print_r($rez);



