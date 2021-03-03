<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
define("MODULES_IBLOCK_ID", 32);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/LicServerClass.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";
$APPLICATION->ShowIncludeStat = false;

if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;

prepareOrderItemsForMail(44);
exit;
