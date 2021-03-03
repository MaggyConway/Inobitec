<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;
if(!CModule::IncludeModule("askaron.settings"))
  return false;

$channelId = ($_GET['id']) ? $_GET['id'] : "UCskaF1EhpIFh1jKVSCl51Ew";
$apiKey = ($_GET['key']) ? $_GET['key'] : "AIzaSyCTqXPv1-fjpzVHWl-_rR3z1BsCV5MmVO8";
$file = $_SERVER['DOCUMENT_ROOT'] . '/include/ycpcache/channelinfo.txt';

$url  = "https://www.googleapis.com/youtube/v3/channels?part=contentDetails&id=$channelId&key=$apiKey";

/*
print_r($url);
print_r("<br>");
print_r($url2);
print_r("<br>");
print_r($_SERVER['SERVER_NAME']);
print_r("<br>");*/


$lastUpdate = \COption::GetOptionString( "askaron.settings", "UF_YCP_LASTUPDATE" );
$lastRez = \COption::GetOptionString( "askaron.settings", "UF_YCP_LASTRESULT" );
$actualDate = time();

if(!$lastUpdate){
  echo getNewDataYCP($url);
  $arUpdateFields = array(
     "UF_YCP_LASTUPDATE" => time(),
   );
  $obSettings = new CAskaronSettings;
  $res = $obSettings->Update( $arUpdateFields );
  exit();
}elseif( ($actualDate - $lastUpdate) > 60*60*24){
  echo getNewDataYCP($url);
  $arUpdateFields = array(
     "UF_YCP_LASTUPDATE" => time(),
   );
  $obSettings = new CAskaronSettings;
  $res = $obSettings->Update( $arUpdateFields );
  
  exit();
}elseif( (($actualDate - $lastUpdate) > 60*60) && $lastRez != 1){
  echo getNewDataYCP($url);
  $arUpdateFields = array(
     "UF_YCP_LASTUPDATE" => time(),
   );
  $obSettings = new CAskaronSettings;
  $res = $obSettings->Update( $arUpdateFields );
  exit();
}elseif(file_exists($file)){
  echo getOldDataYCP();
  exit();
}else{
  echo getNewDataYCP($url);
  exit();
}


function getNewDataYCP($url){
  $file = $_SERVER['DOCUMENT_ROOT'] . '/include/ycpcache/channelinfo.txt';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_REFERER, 'http://'.$_SERVER['SERVER_NAME']);
  $result=curl_exec($ch);
  $arrRez = json_decode($result,true);
  if(isset($arrRez['error']))
    $arUpdateFields = array(
     "UF_YCP_LASTRESULT" => 0,
   );
    
  else
    $arUpdateFields = array(
     "UF_YCP_LASTRESULT" => 1,
   );
  $obSettings = new CAskaronSettings;
  $res = $obSettings->Update( $arUpdateFields );
  curl_close($ch);
  if(isset($arrRez['error'])){
    if(file_exists($file))
      return getOldDataYCP();
    return $result;
  }
  file_put_contents($file, $result);
  return $result;
}

function getOldDataYCP(){
  $file = $_SERVER['DOCUMENT_ROOT'] . '/include/ycpcache/channelinfo.txt';
  return file_get_contents($file);
}