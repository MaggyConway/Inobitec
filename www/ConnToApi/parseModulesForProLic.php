<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
define("MODULES_IBLOCK_ID", 32);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"]."/LicServerClass.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";
$APPLICATION->ShowIncludeStat = false;
//print_r("test");

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$cyr = [
        'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
        'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
        'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
        'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'
      ];
$lat = [
          'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
          'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
          'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
          'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'
      ];

$inobitecLicense = new inobitecLicense();

$res = $inobitecLicense->getProductFeatures("viewer_pro");
print_r($res);
exit();
$arrNewModules = array();
foreach($res as $item){
  $arrNewModules[$item["id"]] = $item;
}

$arSelectModules = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_CODE_FROM_SERVER","PROPERTY_CODE_FROM_SERVER");//IBLOCK_ID и ID обязательно должны быть указаны, см. описание arSelectFields выше
$arFilterModules = Array("IBLOCK_ID"=>IntVal(MODULES_IBLOCK_ID));
$resModules = CIBlockElement::GetList(Array(), $arFilterModules, false, false, $arSelectModules);

while($obModule = $resModules->GetNext()){ 

  if(isset($arrNewModules[$obModule["PROPERTY_CODE_FROM_SERVER_VALUE"]])){
    unset($arrNewModules[$obModule["PROPERTY_CODE_FROM_SERVER_VALUE"]]);
  }else{
    hideModule($obModule["ID"]);
  }
}

foreach($arrNewModules as $newModule){
  addNewModule($newModule);
}

function hideModule($id){
  $el = new CIBlockElement;
  $el->Update($id,array('ACTIVE' => 'N'));
}

function addNewModule($data){
  $el = new CIBlockElement;
  $propVal = array(
    "CODE_FROM_SERVER" => $data["id"],
    "NAME_FROM_SERVER" => $data["name"]
  );
  $arLoadProductArray = Array(
    "IBLOCK_SECTION_ID" => false,
    "IBLOCK_ID"         => MODULES_IBLOCK_ID,
    "CODE" => mb_strtolower(str_replace($cyr, $lat, $data["name"])),
    "NAME"              => $data["name"],
    "ACTIVE"            => "N",            // активен
    "PROPERTY_VALUES"   =>  $propVal,
    );
  $PRODUCT_ID = $el->Add($arLoadProductArray);
  /*
  if()
    echo "New ID: ".$PRODUCT_ID . "<br/>";
  else
    echo "Error: ".$el->LAST_ERROR . "<br/>";
   * */
   
}

exit;