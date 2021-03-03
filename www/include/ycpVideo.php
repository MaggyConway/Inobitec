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
if(!isset($_GET['id']))
  return false;
$id = ($_GET['id']);

$file = $_SERVER['DOCUMENT_ROOT'] . '/include/ycpcache/videoInfo/video_'.$id.'.txt';


$url = "https://www.googleapis.com/youtube/v3/videos?id=$id&key=$apiKey&part=contentDetails,snippet,statistics";



$lastUpdate = file_exists($file) ? filectime ( $file ) : false;
$actualDate = time();
if(!$lastUpdate){
  //print_R("add <br>");
  echo getNewDataYCP($url, $file);
  exit();
}elseif( ($actualDate - $lastUpdate) > 60*60*24){
 // print_R("24 <br>");
  echo getNewDataYCP($url, $file);
  exit();
}elseif(file_exists($file)){
//  print_R("old <br>");
  echo getOldDataYCP($file);
  exit();
}else{
//  print_R("error <br>");
  echo getNewDataYCP($url, $file);
  exit();
}


function getNewDataYCP($url, $file){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_REFERER, 'http://'.$_SERVER['SERVER_NAME']);
  $result=curl_exec($ch);
  $arrRez = json_decode($result,true);
  
  curl_close($ch);
  if(isset($arrRez['error'])){
    if(file_exists($file))
      return getOldDataYCP($file);
    return $result;
  }
  file_put_contents($file, $result);
  return $result;
}

function getOldDataYCP($file){
  return file_get_contents($file);
}