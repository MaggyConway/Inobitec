<?php
require($_SERVER["DOCUMENT_ROOT"]."/includes/cron/simple_html_dom.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$fileStr = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/includes/cron/ru_docs/Viewer_ru.icnt");
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

define('RU_DOCS_IBLOCK_ID', 37);
define('DEFAULT_IMG_PATTH', "/includes/cron/ru_docs");

CModule::IncludeModule("iblock");

$doc = new DOMDocument('1.0', 'UTF-8');
@$doc->loadHTML("\xEF\xBB\xBF" . $fileStr, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
$arrItems = array();
$oldSections = getDBSections();
$oldItems = getDBItems();
$arrLinksToId = array();
//print_r($oldItems);
$uniqueIDs = array();



foreach ($doc->childNodes as $main) {
  foreach($main->childNodes as $node){
    if ($node->nodeType == XML_ELEMENT_NODE){
      $class = $node->getAttribute('class');

      if($class == 'Node IsRoot ExpandClosed'){
        
        foreach($node->childNodes as $nodeContent){
          if ($nodeContent->nodeType == XML_ELEMENT_NODE){
            if($nodeContent->getAttribute('class')  == 'Content'){
              if(!file_exists(__DIR__ . "/ru_docs/" . $nodeContent->firstChild->getAttribute('id'))){
                print_r('<p style="color: red;">Файл ' . $nodeContent->firstChild->getAttribute('id') . ' не найден</p><br>');
                continue;
              }
              if(!$nodeContent->firstChild->getAttribute('data-site-id')){
                print_r('<p style="color: red;">Не указан data-site-id для ' . $nodeContent->firstChild->nodeValue . ', ссылающийся на файл' . $nodeContent->firstChild->getAttribute('id') . '</p><br>');
                continue;
              }
              if(in_array($nodeContent->firstChild->getAttribute('data-site-id') , $uniqueIDs)){
                print_r('<p style="color: red;">Дубликат data-site-id для ' . $nodeContent->firstChild->nodeValue . ', ссылающийся на файл' . $nodeContent->firstChild->getAttribute('id') . '. data-site-id: ' . $nodeContent->firstChild->getAttribute('data-site-id') . '</p><br>');
                continue;
              }
              $arrItems[] = array(
                              'siteId' => $nodeContent->firstChild->getAttribute('data-site-id'),
                              'docUrl' => $nodeContent->firstChild->getAttribute('id'),
                              'name' => $nodeContent->firstChild->nodeValue
                              );
              $uniqueIDs[] = $nodeContent->firstChild->getAttribute('data-site-id');
              $arrLinksToId[$nodeContent->firstChild->getAttribute('id')] = $nodeContent->firstChild->getAttribute('data-site-id');
            }
            if($nodeContent->getAttribute('class')  == 'Container'){
              end($arrItems);
              $key = key($arrItems);
              $arrItems[$key]['nodes'] = parseContainer($nodeContent, $arrLinksToId, $uniqueIDs);
            }
          }
        }
      }
      
    }
  }
  
    //... some code
}

//print_r($arrLinksToId);
//exit();

// Пишем в БД

if(count($arrItems) < 1){
  print_r('<p style="color: red;">Не найдено ни одного раздела, возможно .inct файл был некорректно загружен</p><br>');
  exit();
}

$num = 10;
foreach($arrItems as $item){
  $preName = '';
  if(isset($oldSections[$item["siteId"]])){
    print_r("UpdateSection -- " . $item["name"] . " -- data-site-id: " . $item["siteId"] . " <br>");
    updateSection($item, $num, $oldSections[$item["siteId"]]["ID"]);
    showNodes($item["nodes"], $oldSections[$item["siteId"]]["ID"], $oldItems);
    unset($oldSections[$item["siteId"]]);
  }else{
    print_r("CreateNewSection -- " . $item["name"] . " -- data-site-id: " . $item["siteId"] . " <br>");
    $newSecID = addSection($item, $num);
    showNodes($item["nodes"], $newSecID, $oldItems);
  }
  
  $num += 10;
}




disableOld($oldItems, $oldSections);

$newItems = getDBItems();
$newSections = getDBSections();

foreach($newSections as $section){
  $docText = $section["DESCRIPTION"];
  
  $dom = new DOMDocument('1.0', 'UTF-8');  
  libxml_use_internal_errors(true);
  $dom->loadHTML("\xEF\xBB\xBF" . "<html>" . $docText . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  $doc = $dom->getElementsByTagName("html")->item(0);
  $xpath = new DOMXPath( $dom );
  libxml_clear_errors();

  $src = $xpath->query(".//@href");

  foreach ( $src as $s ) {
    $href = $s->nodeValue;
    $arrHref = explode("#", $href);
    
    if(isset($arrLinksToId[$arrHref[0]])){
      $firstPart = getUrlByCodeID($arrLinksToId[$arrHref[0]], $newItems, $newSections);
      unset($arrHref[0]);
      if(count($arrHref > 0)){
        $firstPart .= "#" . implode('#',$arrHref);
      }
      //print_r($href . "---|||---" . $arrHref[0] . "--" . $arrLinksToId[$arrHref[0]] . "--" . getUrlByCodeID($arrLinksToId[$arrHref[0]], $newItems, $newSections) . "--|||--" . $firstPart . "<br/>");
      $s->nodeValue = $firstPart;
    }
    //print_r($href . "<br>");
  }
  $output = $dom->saveXML( $doc );
  
  $bs = new CIBlockSection;
  $arFields = Array(
    "IBLOCK_ID" => RU_DOCS_IBLOCK_ID,
    "DESCRIPTION" => $output,
    "DESCRIPTION_TYPE" => 'html',
  );
  $bs->Update($section["ID"], $arFields);
  
  print_r("<br><br>");
}

foreach($newItems as $item){
  $docText = $item["DETAIL_TEXT"];
  
  $dom = new DOMDocument('1.0', 'UTF-8');  
  libxml_use_internal_errors(true);
  $dom->loadHTML("\xEF\xBB\xBF" . "<html>" . $docText . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  $doc = $dom->getElementsByTagName("html")->item(0);
  $xpath = new DOMXPath( $dom );
  libxml_clear_errors();

  $src = $xpath->query(".//@href");

  foreach ( $src as $s ) {
    $href = $s->nodeValue;
    $arrHref = explode("#", $href);
    
    if(isset($arrLinksToId[$arrHref[0]])){
      $firstPart = getUrlByCodeID($arrLinksToId[$arrHref[0]], $newItems, $newSections);
      unset($arrHref[0]);
      if(count($arrHref > 0)){
        $firstPart .= "#" . implode('#',$arrHref);
      }
      //print_r($href . "---|||---" . $arrHref[0] . "--" . $arrLinksToId[$arrHref[0]] . "--" . getUrlByCodeID($arrLinksToId[$arrHref[0]], $newItems, $newSections) . "--|||--" . $firstPart . "<br/>");
      $s->nodeValue = $firstPart;
    }
    //print_r($href . "<br>");
  }
  $output = $dom->saveXML( $doc );
  
  $el = new CIBlockElement;
  $arLoadProductArray = Array(
    "IBLOCK_ID"         => RU_DOCS_IBLOCK_ID,
    "DETAIL_TEXT" => $output,
    "DETAIL_TEXT_TYPE" => 'html',
    );
  $res = $el->Update($item["ID"], $arLoadProductArray);
  
  print_r("<br><br>");
}





function getUrlByCodeID($codeId, $arrItems, $arrSections){
  if(isset($arrItems[$codeId])){
    $res = CIBlockElement::GetByID($arrItems[$codeId]["ID"]);
    if($ar_res = $res->GetNext())
      return $ar_res['DETAIL_PAGE_URL'];
    return 0;
  }
  if(isset($arrSections[$codeId])){
    $res = CIBlockSection::GetByID($arrSections[$codeId]["ID"]);
    if($ar_res = $res->GetNext())
      return $ar_res['SECTION_PAGE_URL'];
    return 0;
  }
  return 0;
}


function disableOld($oldItems, $oldSections){
  foreach($oldItems as $item){
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
      "IBLOCK_ID"         => RU_DOCS_IBLOCK_ID,
      "ACTIVE"            => "N",            // активен
      );
    $res = $el->Update($item["ID"], $arLoadProductArray);
  }
  
  foreach($oldSections as $sections){
    $bs = new CIBlockSection;
    $arFields = Array(
      "ACTIVE" => "N",
      "IBLOCK_ID" => RU_DOCS_IBLOCK_ID,
    );
    $bs->Update($sections["ID"], $arFields);
  }
  
}

function getFileData($filename){
  $docText = '';
  
  $fileStr = file_get_contents(__DIR__ . "/ru_docs/" . $filename);
  
  $innerDoc = new DOMDocument('1.0', 'UTF-8');
  @$innerDoc->loadHTML("\xEF\xBB\xBF" . $fileStr, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  foreach ($innerDoc->childNodes as $node) {
    foreach($node->childNodes as $node2 ){
      if ($node2->nodeType == XML_ELEMENT_NODE && $node2->tagName == "body"){
        foreach($node2->childNodes as $item){
          if($item->nodeType == XML_ELEMENT_NODE){
            if($item->getAttribute('class')  == 'crosslinks')
              continue;
            
            if($item->tagName  == 'h1')
              continue;
            if($item->tagName  == 'h2' && $item->getAttribute('class')  == 'sectionHead')
              continue;
            if($item->tagName  == 'h2' && $item->getAttribute('class')  == 'likesectionHead')
              continue;
            $docText .= $innerDoc->saveHTML($item);
          }
        }
      }
    }
  }
  $dom = new DOMDocument('1.0', 'UTF-8');  
  libxml_use_internal_errors(true);
  $dom->loadHTML("\xEF\xBB\xBF" . "<html>" . $docText . "</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
  
  $finder = new DOMXPath( $dom );
  $classname="crosslinks";
  $crosslinks = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
  foreach($crosslinks as $item){
    $item->parentNode->removeChild($item);
  }
  
  $doc = $dom->getElementsByTagName("html")->item(0);
  $xpath = new DOMXPath( $dom );
  libxml_clear_errors();

  $src = $xpath->query(".//@src");

  foreach ( $src as $s ) {
    $firstCharacter = substr($s->nodeValue, 0, 1);
    if($firstCharacter == '.'){
      $out = ltrim($s->nodeValue, ".");
      $s->nodeValue = DEFAULT_IMG_PATTH . $out;
    }
  }

  $output = $dom->saveXML( $doc );
  return $output;
}

function getDBSections(){
  $oldSections = array();
  $arFilter = array('IBLOCK_ID' => RU_DOCS_IBLOCK_ID);
  $rsSections = CIBlockSection::GetList(array(), $arFilter, false, array("ID","IBLOCK_ID","IBLOCK_SECTION_ID","NAME", "DESCRIPTION","UF_*"));
  while ($arSection = $rsSections->Fetch())
  {
      $oldSections[$arSection["UF_DOCS_SECTION_URL"]] = $arSection;
  }
  return $oldSections;
}

function getDBItems(){
  $oldItems = array();
  
  
  $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_PAGE_ID", "DETAIL_TEXT");
  $arFilter = Array("IBLOCK_ID"=>IntVal(RU_DOCS_IBLOCK_ID));
  $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
  while($ob = $res->GetNext()){
    $oldItems[$ob["PROPERTY_PAGE_ID_VALUE"]] = $ob;
  }
   return $oldItems;
}

function updateSection($item, $num, $ID){
  $bs = new CIBlockSection;
  $preName = strstr($item["docUrl"],'Viewer_ruch') ? "Глава " : '';
  //$text = getFileData($item["docUrl"]);
 // print_r($text);
  $arFields = Array(
    "ACTIVE" => "Y",
    "IBLOCK_ID" => RU_DOCS_IBLOCK_ID,
    "NAME" => $preName.$item["name"],
    "SORT" => $num,
    "DESCRIPTION" => getFileData($item["docUrl"]),
    "DESCRIPTION_TYPE" => 'html',
    "UF_DOCS_SECTION_URL" =>   $item["siteId"],
  );
  $bs->Update($ID, $arFields);
}

function addSection($item, $num){
  $bs = new CIBlockSection;
  $preName = strstr($item["docUrl"],'Viewer_ruch') ? "Глава " : '';
  $arFields = Array(
    "ACTIVE" => "Y",
    "IBLOCK_ID" => RU_DOCS_IBLOCK_ID,
    "NAME" => $preName.$item["name"],
    "SORT" => $num,
    "DESCRIPTION" => getFileData($item["docUrl"]),
    "DESCRIPTION_TYPE" => 'html',
    "CODE"  => $item["siteId"],
    "UF_DOCS_SECTION_URL" =>   $item["siteId"],
  );
  $ID = $bs->Add($arFields);
  return $ID;
}


function updatePage($item, $num, $pageID){
  $el = new CIBlockElement;
  $propVal = array(
    "PAGE_ID" => $item["siteId"],
  );
  $arLoadProductArray = Array(
    "IBLOCK_ID"         => RU_DOCS_IBLOCK_ID,
    "CODE"  => $item["siteId"],
    "NAME" => $item["name"],
    "ACTIVE"            => "Y",            // активен
    "PROPERTY_VALUES"   =>  $propVal,
    "DETAIL_TEXT" => getFileData($item["docUrl"]),
    "DETAIL_TEXT_TYPE" => 'html',
    "SORT" => $num,
    );
  $res = $el->Update($pageID, $arLoadProductArray);
}

function addPage($item, $num, $sectionId){
  
  $el = new CIBlockElement;
  $propVal = array(
    "PAGE_ID" => $item["siteId"],
  );
  $arLoadProductArray = Array(
    "IBLOCK_SECTION_ID" => $sectionId,
    "IBLOCK_ID"         => RU_DOCS_IBLOCK_ID,
    "CODE"  => $item["siteId"],
    "NAME" => $item["name"],
    "ACTIVE"            => "Y",            // активен
    "PROPERTY_VALUES"   =>  $propVal,
    "DETAIL_TEXT" => getFileData($item["docUrl"]),
    "DETAIL_TEXT_TYPE" => 'html',
    "SORT" => $num,
    );
  
  if($PRODUCT_ID = $el->Add($arLoadProductArray))
    echo "New ID: ".$PRODUCT_ID;
  else
    echo "Error: ".$el->LAST_ERROR;
  return $PRODUCT_ID;
  
}

function showNodes($nodes, $parentId = false, &$oldItems){
  $order = 10;
  foreach($nodes as $page){
    if(isset($oldItems[$page["siteId"]])){
      print_r("UpdateItem -- " . $page["name"] . " -- data-site-id: " . $page["siteId"] . " <br>");
      updatePage($page, $order, $oldItems[$page["siteId"]]["ID"]);
      unset($oldItems[$page["siteId"]]);
    }else{
      print_r("CreateItem -- " . $page["name"] . " -- data-site-id: " . $page["siteId"] . " <br>");
      $newPageID = addPage($page, $order, $parentId);
      //$oldItems[$page["siteId"]]["ID"] = $newPageID;
    }

    $order += 10;

  }
}


function parseContainer($data, &$arrLinksToId, &$uniqueIDs){
  $insertNodes = array();
  foreach($data->childNodes as $node){
    if($node->nodeType == XML_ELEMENT_NODE){
      $class = $node->getAttribute('class');
      if($class == 'Node ExpandLeaf IsLast'){
        foreach($node->childNodes as $nodeContent){
          if ($nodeContent->nodeType == XML_ELEMENT_NODE){
             if($nodeContent->getAttribute('class')  == 'Content'){
               if(!file_exists(__DIR__ . "/ru_docs/" . $nodeContent->firstChild->getAttribute('id'))){
                print_r('<p style="color: red;">Файл ' . $nodeContent->firstChild->getAttribute('id') . ' не найден</p><br>');
                continue;
              }
              if(!$nodeContent->firstChild->getAttribute('data-site-id')){
                print_r('<p style="color: red;">Не указан data-site-id для ' . $nodeContent->firstChild->nodeValue . ', ссылающийся на ' . $nodeContent->firstChild->getAttribute('id') . '</p><br>');
                continue;
              }
              if(in_array($nodeContent->firstChild->getAttribute('data-site-id') , $uniqueIDs)){
                print_r('<p style="color: red;">Дубликат data-site-id для ' . $nodeContent->firstChild->nodeValue . ', ссылающийся на файл' . $nodeContent->firstChild->getAttribute('id') . '. data-site-id: ' . $nodeContent->firstChild->getAttribute('data-site-id') . '</p><br>');
                continue;
              }
              $insertNodes[] = array(
                              'siteId' => $nodeContent->firstChild->getAttribute('data-site-id'),
                              'docUrl' => $nodeContent->firstChild->getAttribute('id'),
                              'name' => $nodeContent->firstChild->nodeValue
                              );
              $uniqueIDs[] = $nodeContent->firstChild->getAttribute('data-site-id');
              $arrLinksToId[$nodeContent->firstChild->getAttribute('id')] = $nodeContent->firstChild->getAttribute('data-site-id');
            }
            if($nodeContent->getAttribute('class')  == 'Container'){
              end($insertNodes);
              $key = key($insertNodes);
              $insertNodes[$key]['nodes'] = parseContainer($nodeContent, $arrLinksToId, $uniqueIDs);
            }
          }
          
        }
      }
    }
  }
  return $insertNodes;
}
 

exit(); 
 
