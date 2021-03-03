<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

CModule::IncludeModule('iblock');

$preIBlock = CIBlock::GetByID($arParams["IBLOCK_ID"]);

if($iBlock = $preIBlock -> GetNext()){
	
}


require($_SERVER["DOCUMENT_ROOT"].'/lang.php'); // Подключили языковой файл
?>
<div class="manual__page">
    <!-- MANUAL LEFT -->
    <div class="manual__left <?if($_SESSION['LEFT_WIDTH'] < 500){?>slim<?}?>" <?if($_SESSION['LEFT_WIDTH'] && $_SESSION['LEFT_WIDTH'] != ''){?>style="width:<?=$_SESSION['LEFT_WIDTH']?>px"<?}?>>
        <div class="inn">
            <div class="manual__logo">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/logo_white.svg" width="225" height="130" alt="ИНОБИТЕК" />
            </div>
            <div class="links">
                <a class="active" href="#"><?=$MESS[SITE_ID]['MANUAL_CONTENTS']?></a>
                <!--<a href="#">Предметный указатель</a>-->
                <div class="clearfix"></div>
            </div>
            <div class="manual__menu">
            <?$APPLICATION->IncludeComponent(
			    "bitrix:menu", 
			    "inobitek_multilevel", 
			    array(
			        "ALLOW_MULTI_SELECT" => "Y",
			        "CHILD_MENU_TYPE" => "left",
			        "COMPONENT_TEMPLATE" => ".default",
			        "DELAY" => "N",
			        "MAX_LEVEL" => "3",
			        "MENU_CACHE_GET_VARS" => array(
			        ),
			        "MENU_CACHE_TIME" => "3600",
			        "MENU_CACHE_TYPE" => "A",
			        "MENU_CACHE_USE_GROUPS" => "N",
			        "MENU_THEME" => "site",
			        "ROOT_MENU_TYPE" => "left",
			        "USE_EXT" => "Y"
			    ),
			    false
			);?>
            </div>    
        </div>
    </div>
     <!-- END :: MANUAL LEFT -->
    <div class="manual__resizer">
        <a href="#" class="manual__close-left">&nbsp;</a>
        <div class="fixer"></div>
    </div>
    <!-- MANUAL RIGHT --> 
    <div class="manual__right <?if($_SESSION['RIGHT_WIDTH'] < 800){?>slim<?}?>" <?if($_SESSION['RIGHT_WIDTH'] && $_SESSION['RIGHT_WIDTH'] != ''){?>style="width:<?=$_SESSION['RIGHT_WIDTH']?>px"<?}?>>

        <div class="inn">
            <!-- header-block -->
            <div class="header-block">
                <div class="product-name">
                    <?=$iBlock['NAME']?>
                </div>
                <div class="search-block">
                    <form action="<?=$arResult['FOLDER']?>search/" method="post" id="manual-search">
                        <input type="text" name="q" placeholder="<?=$MESS[SITE_ID]['MANUAL_SEARCH']?>" />
                        <input type="submit" value="<?=$MESS[SITE_ID]['MANUAL_SEARCH_1']?>"/>
                    </form>
                </div>
                <div class="clearfix"></div>
                <h1>
                    <? $APPLICATION->ShowProperty("h1"); ?>
                </h1>
            </div> 
            <!-- END :: header-block -->
            <!-- content-block -->
            <div class="content-block">
                <?
                //print_r($arResult["VARIABLES"]["componentPage"]);
                if($arResult["VARIABLES"]["SECTION_ID"] === 'search'){
                	require "search.php"; 
                }else{
	            	switch ($arResult["VARIABLES"]["componentPage"]) {
	            		case 'detail':
	            			require "element.php"; 
	        			break;

	            		case 'section':
	            			require "section.php"; 
	        			break;

	        			case 'search':
	            			require "search.php"; 
	        			break;
                        case '404':
                          require "404.php"; 
	            		default:
	            			$APPLICATION -> SetPageProperty('title', $iBlock['NAME']);	
							$APPLICATION -> SetPageProperty('h1', $iBlock['NAME']);	

	            			echo $iBlock['DESCRIPTION'];

	        			break;
	            	}
                }	
                ?>
            </div> 
             <!-- END :: content-block --> 
        </div>
    </div>
    <!-- END :: MANUAL RIGHT --> 
    <div class="clearfix"></div>
</div>
<div class="manual__footer">
	<?
	$APPLICATION->IncludeComponent( // MENU.TOP COMPONENT
		"bitrix:menu",
		"common__bottom",
		Array(
			"ALLOW_MULTI_SELECT" => "N",
			"CHILD_MENU_TYPE" => "left",
			"DELAY" => "N",
			"MAX_LEVEL" => "1",
			"MENU_CACHE_GET_VARS" => array(""),
			"MENU_CACHE_TIME" => "3600",
			"MENU_CACHE_TYPE" => "A",
			"MENU_CACHE_USE_GROUPS" => "Y",
			"ROOT_MENU_TYPE" => "manual",
			"USE_EXT" => "N"
		)
	);?>
    <div class="copy">
    	<?
		// включаемая область для раздела
		$APPLICATION->IncludeFile(SITE_DIR."/includes/copy.php", Array(), Array(
		    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
		    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
		    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
		    ));
		?>
    </div>
</div> 


<?/*?>
<?if($arParams["USE_RSS"]=="Y"):?>
	<?
	if(method_exists($APPLICATION, 'addheadstring'))
		$APPLICATION->AddHeadString('<link rel="alternate" type="application/rss+xml" title="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" href="'.$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"].'" />');
	?>
	<a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["rss"]?>" title="rss" target="_self"><img alt="RSS" src="<?=$templateFolder?>/images/gif-light/feed-icon-16x16.gif" border="0" align="right" /></a>
<?endif?>

<?if($arParams["USE_SEARCH"]=="Y"):?>
<?=GetMessage("SEARCH_LABEL")?><?$APPLICATION->IncludeComponent(
	"bitrix:search.form",
	"flat",
	Array(
		"PAGE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["search"]
	),
	$component
);?>
<br />
<?endif?>
<?if($arParams["USE_FILTER"]=="Y"):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.filter",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"FIELD_CODE" => $arParams["FILTER_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["FILTER_PROPERTY_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
	),
	$component
);
?>
<br />
<?endif?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"SORT_BY1" => $arParams["SORT_BY1"],
		"SORT_ORDER1" => $arParams["SORT_ORDER1"],
		"SORT_BY2" => $arParams["SORT_BY2"],
		"SORT_ORDER2" => $arParams["SORT_ORDER2"],
		"FIELD_CODE" => $arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
		"MESSAGE_404" => $arParams["MESSAGE_404"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"SHOW_404" => $arParams["SHOW_404"],
		"FILE_404" => $arParams["FILE_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
		"PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL" => $arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
	),
	$component
);?>

<?*/?>
