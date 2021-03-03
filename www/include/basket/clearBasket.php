<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;

CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
$rez = array("success" => 1);
header('Content-Type: application/json');
echo json_encode($rez, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;