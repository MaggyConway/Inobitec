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
?>

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
);

$сache = Bitrix\Main\Data\Cache::createInstance();
if($сache -> initCache(36000, 'techs_list_'.SITE_ID)) 
{ 
    $result = $сache -> getVars(); 
} 
elseif($сache -> startDataCache())
{ 
    if(CModule::IncludeModule('iblock')){

        $preItems = CIBlockElement::GetList(
            array('sort' => 'asc', 'active_from' => 'desc'),
            array('IBLOCK_CODE' => 'techs_'.SITE_ID, "ACTIVE" => "Y"),
            false,
            false,
            array('ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE',  'DETAIL_PAGE_URL', 'PROPERTY_ATT_ICON', 'PROPERTY_ATT_ICON_GRAY')
        );

        if($preItems->result->num_rows && $preItems->result->num_rows > 0){

            while($arItem = $preItems -> GetNext()){
                $Items[] = $arItem;
            }

        }

        $result = $Items; 

        if ($isInvalid) 
        { 
            $cache->abortDataCache(); 
        } 

        $сache->endDataCache($result); 
    }
} 
?>

<?
if($result){
?>
<section class="programming-languages">
  <div class="wr">
	<h2><?=GetMessage("H2");?></h2>
	<p>
		 <?=GetMessage("DESC");?>
	</p>
	<div class="programming-languages__container js-programming-languages-container">
		<div class="programming-languages__list">
          <?foreach($result as $key => $arItem):?>
            <div class="programming-languages__item">
              <div class="programming-languages__logo">
                <img class="programming-languages__logo-img img programming-languages__logo-img--monochrome" src="<?=CFile::GetPath($arItem["PROPERTY_ATT_ICON_GRAY_VALUE"])?>">
                <img class="programming-languages__logo-img img programming-languages__logo-img--full-color" src="<?=CFile::GetPath($arItem["PROPERTY_ATT_ICON_VALUE"])?>">
              </div>
              <span><?=$arItem['NAME']?></span>
            </div>
          
            
          <?endforeach;?>
		</div>
	</div>
 <button class="btn btn--light-blue-outline programming-languages__button js-programming-languages-button" type="button"><?=GetMessage("MORE");?></button>
</div>
</section>
  
<?
}
?>




<?
$сacheSert = Bitrix\Main\Data\Cache::createInstance();
if($сacheSert -> initCache(36000, 'sertificates_list_'.SITE_ID)) 
{ 
    $resultSert = $сacheSert -> getVars(); 
} 
elseif($сacheSert -> startDataCache())
{ 
    if(CModule::IncludeModule('iblock')){

        $preItemsSert = CIBlockElement::GetList(
            array('sort' => 'asc', 'active_from' => 'desc'),
            array('IBLOCK_CODE' => 'sertificates_'.SITE_ID, "ACTIVE" => "Y"),
            false,
            false,
            array('ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_FORMATED_NAME', 'PROPERTY_LAST_VERSION', 'PROPERTY_LAST_REFRESH')
        );

        if($preItemsSert->result->num_rows && $preItemsSert->result->num_rows > 0){

            while($arItemSert = $preItemsSert -> GetNext()){
                $ItemsSert[] = $arItemSert;
            }

        }

        $resultSert = $ItemsSert; 

        if ($isInvalid) 
        { 
            $cacheSert->abortDataCache(); 
        } 

        $сacheSert->endDataCache($resultSert); 
    }
} 
?>

<div class="services">
	<div class="wr">
		<div class="section-header services__section-header services__integration-header">
            <svg width="106" height="106">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-integration"></use>
            </svg>
			<h2><?=GetMessage("INTEG_TITLE")?></h2>
			<div class="services__header-container">
				<p><?=GetMessage("INTEG_DESC")?></p>
 <a class="btn btn--light-blue-outline services__button" href="integration/"><?=GetMessage("DETAIL_BUTTOM")?></a>
			</div>
		</div>
		<div class="section-header services__section-header services__sla-header">
            <svg width="120" height="120">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-sla"></use>
            </svg>
			<h2><?=GetMessage("SLA_TITLE")?></h2>
			<div class="services__header-container">
				<p><?=GetMessage("SLA_DESC")?></p>
 <a class="btn btn--light-blue-outline services__button" href="sla/"><?=GetMessage("DETAIL_BUTTOM")?></a>
			</div>
		</div>
	</div>
</div>
<div class="services-bottom">
	<div class="wr">
		<div class="certificates">
          <h2><?=GetMessage("SERT_TITLE")?></h2>
          <div class="certificates__slider swiper-container js-certificates-slider">
            <div class="certificates__slides swiper-wrapper">
              <?foreach($resultSert as $ketSert => $arItemSert):?>
                <div class="certificates__slide swiper-slide">
                  <div class="certificates__name"><?=$arItemSert['NAME']?></div>
                  <div class="certificates__preview">
                    <?if($arItemSert['PREVIEW_PICTURE'] && $arItemSert['DETAIL_PICTURE']):?>
                    <a href="<?=CFile::GetPath($arItemSert['DETAIL_PICTURE'])?>" data-fancybox="certificate">
                      <img src="<?=CFile::GetPath($arItemSert['PREVIEW_PICTURE'])?>" alt="<?=$arItemSert['NAME']?>">
                    </a>
                    <?else:?>
                      <span><?=GetMessage("SERT_WAIT")?></span>
                      <svg width="48" height="48">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-loader"></use>
                      </svg>
                    <?endif;?>
                  </div>
                </div>
              <?endforeach;?>
            </div>
            <button class="certificates__slider-button certificates__slider-button--prev js-button-prev swiper-button-prev" type="button">
              <svg width="48" height="48">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-slider-arrow"></use>
              </svg>
            </button>
            <button class="certificates__slider-button certificates__slider-button--next js-button-next swiper-button-next" type="button">
              <svg width="48" height="48">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-slider-arrow"></use>
              </svg>
            </button>
          </div>
        </div>
		<div class="task-assessment">
          <h2><?=  GetMessage("JIVO_TITLE")?></h2>
			<p><?=  GetMessage("JIVO_DESC")?></p>
 <button class="btn btn--white-outline task-assessment__button" id="jivo_custom_widget" type="button" onclick="jivo_api.open()"><?=  GetMessage("JIVO_GOBUTTOM")?></button>
		</div>
	</div>
</div>
<script type="text/javascript">
_linkedin_partner_id = "2470858";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2470858&fmt=gif" />
</noscript>
<?//$APPLICATION->SetTitle(GetMessage("PAGE_TITLE"));?>