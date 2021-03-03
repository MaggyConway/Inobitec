<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION, $USER;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$APPLICATION->RestartBuffer();

if(!$_GET["LIC_ID"]){
  print_r("NO_LIC_ID");
  exit();
  LocalRedirect("/personal/");
}


$resLics = CIBlockElement::GetList(
              array('sort' => 'asc'),
              array('IBLOCK_ID' => 35, "ID" => (int)$_GET["LIC_ID"]),
              false,
              false,
              array('ID', "NAME", "PROPERTY_SUPPORTED_PO", "PROPERTY_LICSERVER_ID", "PROPERTY_USER_ID", "PROPERTY_API_PRODUCT_TYPE", "PROPERTY_LICENSE_KEY", "PROPERTY_SERVER_CONNECTIONS")
          );

$obLics = $resLics->getNext();
if(!$obLics){
  print_r("NO_SUCH_LIC");
  exit();
}
if($obLics['PROPERTY_API_PRODUCT_TYPE_VALUE'] != 'offline' || $obLics['PROPERTY_SUPPORTED_PO_VALUE'] != 1){
  print_r("UNCORRECT_LIC_TYPE");
  exit();
}
if($USER->GetID() != $obLics['PROPERTY_USER_ID_VALUE']){
  print_r("UNCORRECT_USER");
  exit();
}

$inobitecLicense = new inobitecLicense();

$file = $inobitecLicense->getOfflineLicenseFile($obLics['PROPERTY_LICSERVER_ID_VALUE']);
$getName = explode("filename", $file['headers']['content-disposition'][0]);


header('content-type: application/octet-stream');
header('content-Disposition: attachment; filename='.$obLics['PROPERTY_LICENSE_KEY_VALUE'].'_'.$obLics['PROPERTY_SERVER_CONNECTIONS_VALUE'].'-AETitles.lic');
header('Pragma: no-cache');
header('Expires: 0');

/*
header('Content-Description: File Transfer');
header('Content-Type: application/lic');
header('Content-Disposition: attachment; filename'.$getName[1]);
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');*/
echo $file['binary-data'];
exit();
