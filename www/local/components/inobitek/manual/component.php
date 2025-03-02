<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if($arParams["USE_FILTER"]=="Y")
{
	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
		$arParams["FILTER_NAME"] = "arrFilter";
}
else
	$arParams["FILTER_NAME"] = "";

$arParams["USE_CATEGORIES"]=$arParams["USE_CATEGORIES"]=="Y";
if($arParams["USE_CATEGORIES"])
{
	if(!is_array($arParams["CATEGORY_IBLOCK"]))
		$arParams["CATEGORY_IBLOCK"] = array();
	$ar = array();
	foreach($arParams["CATEGORY_IBLOCK"] as $key=>$value)
	{
		$value=intval($value);
		if($value>0)
			$ar[$value]=true;
	}
	$arParams["CATEGORY_IBLOCK"] = array_keys($ar);
}
$arParams["CATEGORY_CODE"]=trim($arParams["CATEGORY_CODE"]);
if(strlen($arParams["CATEGORY_CODE"])<=0)
	$arParams["CATEGORY_CODE"]="CATEGORY";
$arParams["CATEGORY_ITEMS_COUNT"]=intval($arParams["CATEGORY_ITEMS_COUNT"]);
if($arParams["CATEGORY_ITEMS_COUNT"]<=0)
	$arParams["CATEGORY_ITEMS_COUNT"]=5;

if(!is_array($arParams["CATEGORY_IBLOCK"]))
	$arParams["CATEGORY_IBLOCK"] = array();
foreach($arParams["CATEGORY_IBLOCK"] as $iblock_id)
	if($arParams["CATEGORY_THEME_".$iblock_id]!="photo")
		$arParams["CATEGORY_THEME_".$iblock_id]="list";

$arDefaultUrlTemplates404 = array(
	"news" => "",
	"search" => "search/",
	"rss" => "rss/",
	"rss_section" => "#SECTION_ID#/rss/",
	"detail" => "#SECTION_ID#/#ELEMENT_ID#/",
	"section" => "#SECTION_ID#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"SECTION_ID",
	"SECTION_CODE",
	"ELEMENT_ID",
	"ELEMENT_CODE",
);

if($arParams["USE_SEARCH"] != "Y")
{
	unset($arDefaultUrlTemplates404["search"]);
	unset($arParams["SEF_URL_TEMPLATES"]["search"]);
}
else
{
	$arComponentVariables[] = "q";
	$arComponentVariables[] = "tags";
}

if($arParams["USE_RSS"] != "Y")
{
	unset($arDefaultUrlTemplates404["rss"]);
	unset($arDefaultUrlTemplates404["rss_section"]);
	unset($arParams["SEF_URL_TEMPLATES"]["rss"]);
	unset($arParams["SEF_URL_TEMPLATES"]["rss_section"]);
}
else
{
	$arComponentVariables[] = "rss";
}

if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();
	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}

	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

	$b404 = false;


	if(!$componentPage)
	{
		$componentPage = "news";
		$b404 = true;
	}

	if(
		$componentPage == "section"
		&& isset($arVariables["SECTION_ID"])
		&& intval($arVariables["SECTION_ID"])."" !== $arVariables["SECTION_ID"]
	)
		$b404 = true;

	if($b404 && CModule::IncludeModule('iblock'))
	{
		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		if (substr($folder404, -1) == "/")
			$folder404 .= "index.php";

		if ($folder404 != $APPLICATION->GetCurPage(true))
		{
			\Bitrix\Iblock\Component\Tools::process404(
				""
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SET_STATUS_404"] === "Y")
				,($arParams["SHOW_404"] === "Y")
				,$arParams["FILE_404"]
			);
		}
	}

	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	$arVariables['componentPage'] = $componentPage;

	$arResult = array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
	);
}
else
{
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);
	$componentPage = "";

   
    
	if(isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["ELEMENT_CODE"]) && strlen($arVariables["ELEMENT_CODE"]) > 0)
		$componentPage = "detail";
	elseif(isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0)
	{
		if(isset($arVariables["rss"]) && $arVariables["rss"]=="y")
			$componentPage = "rss_section";
		else
			$componentPage = "section";
	}
	elseif(isset($arVariables["SECTION_CODE"]) && strlen($arVariables["SECTION_CODE"]) > 0)
	{
		if(isset($arVariables["rss"]) && $arVariables["rss"]=="y")
			$componentPage = "rss_section";
		else
			$componentPage = "section";
	}
	elseif(isset($arVariables["q"]) && strlen(trim($arVariables["q"])) > 0)
		$componentPage = "search";
	elseif(isset($arVariables["tags"]) && strlen(trim($arVariables["tags"])) > 0)
		$componentPage = "search";
	elseif(isset($arVariables["rss"]) && $arVariables["rss"]=="y")
		$componentPage = "rss";
	else
		$componentPage = "news";

	$arVariables['componentPage'] = $componentPage;

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"news" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"section" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#"),
			"detail" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["ELEMENT_ID"]."=#ELEMENT_ID#"),
			"search" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"rss" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?rss=y"),
			"rss_section" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["SECTION_ID"]."=#SECTION_ID#&rss=y"),
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
/*if($arResult["VARIABLES"]["SECTION_CODE"] == 'search')
  $componentPage=="search";*/

if($componentPage=="search")
{
  
	include_once("newstools.php");
	global $BX_NEWS_DETAIL_URL, $BX_NEWS_SECTION_URL;
	$BX_NEWS_DETAIL_URL = $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"];
	$BX_NEWS_SECTION_URL = $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"];
	AddEventHandler("search", "OnSearchGetURL", array("CNewsTools","OnSearchGetURL"), 20);
}

if($arResult["VARIABLES"]["SECTION_CODE"] && $arResult["VARIABLES"]["SECTION_CODE"] == 'search'){
  $arResult["VARIABLES"]['SECTION_ID'] = $arResult["VARIABLES"]["SECTION_CODE"];
}elseif($arResult["VARIABLES"]["SECTION_CODE"]){
  $arResult["VARIABLES"]['SECTION_ID'] = CIBlockFindTools::GetSectionID( $arResult['VARIABLES']['SECTION_ID'], $arResult['VARIABLES']['SECTION_CODE'], array('IBLOCK_ID' => $arParams['IBLOCK_ID']) );
}
if($arResult["VARIABLES"]["ELEMENT_CODE"]){
  $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], '=CODE' => $arResult["VARIABLES"]["ELEMENT_CODE"], 'SECTION_ID' => $arResult["VARIABLES"]['SECTION_ID'] );
  
  $rsElement = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID"));
  if ($arElement = $rsElement->Fetch())
      $arResult["VARIABLES"]['ELEMENT_ID'] = $arElement["ID"];
  
  
}

/* Передаем в подключение шаблона всегда страницу news.php, в переменные пишем вычесленную компонентом страницу, для использования в news.php */
$this->IncludeComponentTemplate('root');

?>