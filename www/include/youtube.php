<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('NO_AGENT_CHECK', true);

/*ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/
$url = 'https://www.youtube.com/feeds/videos.xml?channel_id=UCskaF1EhpIFh1jKVSCl51Ew';

$curl = curl_init();
curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt( $curl, CURLOPT_URL, $url );

$xml = curl_exec( $curl );
curl_close( $curl );

$document = new DOMDocument;
$document->loadXML( $xml );

$entry = $document->getElementsByTagName('entry');

$thumbs = array();

foreach ($entry  as $key => $item) {

  $href = $thumbnail = $title = "";
  //$linkArr = $item->getElementsByTagName('link');
  $id = $item->getElementsByTagName('id')->item(0)->nodeValue;
  $published = $item->getElementsByTagName('published')->item(0)->nodeValue;
  $link = $item->getElementsByTagName('link')->item(0);
  if($link->hasAttribute('href'))
    $href = $link->getAttribute('href');
  $thumb = $item->getElementsByTagName('thumbnail')->item(0);
  if($thumb->hasAttribute('url'))
    $thumbnail = $thumb->getAttribute('url');
  
  $title = $item->getElementsByTagName('title')->item(0)->nodeValue;
  $thumbs[$id]['thumb'] = $thumbnail;
  $thumbs[$id]['videoUrl'] = $href;
  $thumbs[$id]['title'] = $title;
  $thumbs[$id]['videoid'] = $id;
  $thumbs[$id]['published'] = $published;
  
}
CModule::IncludeModule("iblock");
//CModule::IncludeModule("catalog");

$items = CIBlockElement::GetList(
    array('sort' => 'asc'),
    array('IBLOCK_CODE' =>'youtube_video_list', "ACTIVE" => "Y"),
    false,
    false,
    array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_VIDEOURL', 'PROPERTY_THUMBNAIL', 'PROPERTY_VIDEOID', 'PROPERTY_PUBLISHED')
);

$res = CIBlock::GetList(
    Array(), 
    Array(
        'ACTIVE'  =>  'Y', 
        'CODE'    =>  'youtube_video_list'
    ), true
);
$block = $res->getNext();


$arChange = array();

while($arItem = $items -> GetNext()){

    if($arItem['PROPERTY_VIDEOID_VALUE']){
      //Есть ли такое в спарсенных
      if(isset($thumbs[$arItem['PROPERTY_VIDEOID_VALUE']])){
        //Проверяем все ли данные такие же
        $actualId = $arItem['PROPERTY_VIDEOID_VALUE'];
        if($arItem['PROPERTY_THUMBNAIL_VALUE'] != $thumbs[$actualId]['thumb'] || $arItem['PROPERTY_VIDEOURL_VALUE'] != $thumbs[$actualId]['videoUrl'] || $arItem["NAME"] != $thumbs[$actualId]['title'] || $arItem['PROPERTY_PUBLISHED_VALUE'] != $thumbs[$actualId]['published']){
          //обновляем если что-то изменилось
          $arChange[$arItem['PROPERTY_VIDEOID_VALUE']] = $thumbs[$arItem['PROPERTY_VIDEOID_VALUE']];
          $arChange[$arItem['PROPERTY_VIDEOID_VALUE']]['bitrixId'] = $arItem['id'];
          $arrValues = array(
              "NAME"      => $thumbs[$actualId]['title'],
              "VIDEOURL"  => $thumbs[$actualId]['videoUrl'],
              "THUMBNAIL" => $thumbs[$actualId]['thumb'],
              "PUBLISHED" => $thumbs[$actualId]['published'],
          );
          CIBlockElement::SetPropertyValuesEx($arItem['ID'], $arItem['IBLOCK_ID'],$arrValues);
        }
        unset($thumbs[$actualId]);
      }else{
        CIBlockElement::Delete($arItem['ID']);
        //удаляем видео из инфоблока
      }
    }else{
      CIBlockElement::Delete($arItem['ID']);
      //удаляем видео из инфоблока
    }
}

$thumbs = array_reverse($thumbs);
foreach($thumbs as $key => $item){
  $el = new CIBlockElement;
  $propVal = array(
          "VIDEOURL"  =>  $item['videoUrl'], 
          "THUMBNAIL" =>  $item['thumb'],
          "PUBLISHED" =>  $item['published'],
          "VIDEOID"   =>  $key, 
  );
  
  $arLoadProductArray = Array(
          "IBLOCK_ID"         =>  $block["ID"],
          "NAME"              =>  $item["title"],
          "ACTIVE"            =>  "Y",            // активен
          "PROPERTY_VALUES"   =>  $propVal,
          );
  //print_r("<br/>");
  //print_r($arLoadProductArray);
  print_r("<br/>");
  if($PRODUCT_ID = $el->Add($arLoadProductArray))
    echo "New ID: ".$PRODUCT_ID;
  else
    echo "Error: ".$el->LAST_ERROR;
  print_r("<br/>");
}

?>




