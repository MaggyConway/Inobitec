<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if(!CModule::IncludeModule("iblock"))
  return;
if(!CModule::IncludeModule("sale"))
  return;
addOrderLicToApi(470);
exit();
