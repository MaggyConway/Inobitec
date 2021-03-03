<?php

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/LicServerClass.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";
$APPLICATION->ShowIncludeStat = false;

//exit;
$inobitecLicense = new inobitecLicense();

$rez = $inobitecLicense->getClients();
$mass = array();
foreach($rez as $client){
  $mass[html_entity_decode($client["id"])] = $client;
}

print_r($mass);
//exit;


$filter = Array("GROUPS_ID" => Array(5));
$rsUsers = CUser::GetList(($by = "UF_LIC_SERV_ID"), ($order = "asc"), $filter, array("SELECT"=>array("UF_*")) );
while ($arUser = $rsUsers->Fetch()) {

  if(isset($mass[$arUser["UF_LIC_SERV_ID"]])){
    if(!checkChangesInUser($mass[$arUser["UF_LIC_SERV_ID"]], $arUser)){
      updateUserData($mass[$arUser["UF_LIC_SERV_ID"]], $arUser["ID"]);
    }
    unset($mass[$arUser["UF_LIC_SERV_ID"]]);
  }else{
    hideUser($arUser["ID"]);
  }

}
foreach($mass as $key => $client){
  addNewUser($client);
}

function getUserTypeByLegalForm($legalForm){
  switch($legalForm){
    case "ООО":
      return 2;
      break;
    case "Частное лицо":
      return 4;
      break;
    case "ИП":
      return 3;
      break;
    default:
      return 2;
      break;
  }
}

function hideUser($userId){
  $user = new CUser;
  $arFields = Array(
    "ACTIVE"            => "N"
  );
  $user->Update($userId, $arFields);
  
}

function checkChangesInUser($licServData, $dbData){

  if($dbData["NAME"] != html_entity_decode($licServData["contactPerson"]))
    return false;

  if($dbData["EMAIL"] != html_entity_decode($licServData["e_mail"]))
    return false;

  if($dbData["LOGIN"] != html_entity_decode($licServData["e_mail"]))
    return false;

  if($dbData["PERSONAL_PHONE"] != html_entity_decode($licServData["phone"]))
    return false;

  if($dbData["WORK_COMPANY"] != html_entity_decode($licServData["name"]))
    return false;

  if($dbData["UF_TAX_ID"] != html_entity_decode($licServData["taxId"]))
    return false;

  if($dbData["UF_USER_TYPE"] != getUserTypeByLegalForm($licServData["legalForm"]))
    return false;
  
  if($dbData["UF_LEGAL_ADDRESS"] != html_entity_decode($licServData["legal_address"]))
    return false;
  

  return true;
}

function updateUserData($licServData, $userId){
  //print_r("need to update ". $userId . "<br/>");

  $user = new CUser;
  $arFields = Array(
    "NAME"              => html_entity_decode($licServData["contactPerson"]),
    "EMAIL"             => html_entity_decode($licServData["e_mail"]),
    "LOGIN"             => html_entity_decode($licServData["e_mail"]),
    "PERSONAL_PHONE"    => html_entity_decode($licServData["phone"]),
    "UF_COMPANY_NAME"      => html_entity_decode($licServData["name"]),
    "UF_TAX_ID"         => html_entity_decode($licServData["taxId"]),  
    "UF_LEGAL_ADDRESS"  => html_entity_decode($licServData["legal_address"]),
    "UF_USER_TYPE"      => getUserTypeByLegalForm($licServData["legalForm"]),  
    "ACTIVE"            => "Y",

  );

  $user->Update($userId, $arFields);
}

function addNewUser($licServData){
  $user = new CUser;
  $arFields = Array(
    "NAME"              => html_entity_decode($licServData["contactPerson"]),
    "EMAIL"             => html_entity_decode($licServData["e_mail"]),
    "LOGIN"             => html_entity_decode($licServData["e_mail"]),
    "LID"               => "s1",
    "ACTIVE"            => "Y",
    "GROUP_ID"          => array(3,4,5),
    "PERSONAL_PHONE"    => html_entity_decode($licServData["phone"]),
    "UF_COMPANY_NAME"      => html_entity_decode($licServData["name"]),
    "UF_TAX_ID"         => html_entity_decode($licServData["taxId"]),  
    "UF_LIC_SERV_ID"    => html_entity_decode($licServData["id"]), 
    "UF_LEGAL_ADDRESS"  => html_entity_decode($licServData["legal_address"]),
    "UF_USER_TYPE"      => getUserTypeByLegalForm($licServData["legalForm"]),   
    "PASSWORD"          => substr(md5(html_entity_decode($licServData["e_mail"])), 0, 8),
    "CONFIRM_PASSWORD"  => substr(md5(html_entity_decode($licServData["e_mail"])), 0, 8),
    "UF_SEND_NOTICE"    => "1"  
  );

  $ID = $user->Add($arFields);
  if (intval($ID) > 0)
      echo "Пользователь успешно добавлен.<br/>";
  else
      echo $user->LAST_ERROR;
  print_r("<br/>");
  print_r("<br/>");
}

exit;
/*
$inobitecLicense = new inobitecLicense();
print_r("<br/>");
$rez = $inobitecLicense->getClients();
foreach($rez as $client){
  
  
 
  
  print_r("<br/>");
  
  
  
}
*/


