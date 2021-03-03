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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
foreach ($arResult['SECTIONS'] as &$arSection){
  $rsSectionInfo = CIBlockSection::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => $arSection["IBLOCK_ID"], "ID" => $arSection["ID"]), false, $arSelect = array("UF_*"));
  $arSectionInfo = $rsSectionInfo->GetNext();

if (LANGUAGE_ID == 'ru') {
	$sectionLangName = $arSection['NAME'];
	$sectionLogos = $arSectionInfo["UF_LOGOS"];
    $sectionLangDescription = $arSection["DESCRIPTION"];
} else {
	$sectionLangNameProperty = 'UF_NAME_' . strtoupper(LANGUAGE_ID);
	$sectionLangDescriptionProperty = 'UF_DESCRIPTION_' . strtoupper(LANGUAGE_ID);
	$sectionLangName = (strlen($arSectionInfo[$sectionLangNameProperty])) ? $arSectionInfo[$sectionLangNameProperty] : $arSection['NAME'];
	$sectionLangDescription = (strlen($arSectionInfo[$sectionLangDescriptionProperty])) ? $arSectionInfo[$sectionLangDescriptionProperty] : $arSection['DESCRIPTION'];
	// $sectionLogos = $arSectionInfo["UF_LOGOS_" . strtoupper(LANGUAGE_ID)];
	$sectionLogos = $arSectionInfo["UF_LOGOS_EN"];

//	echo "<pre>"; var_dump(LANGUAGE_ID); echo "</pre>";
}
?> 
  <div class="buy-row">
      	<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$sectionLangName;?></a>
      	<div class="buy-row-logo">
        <?foreach($sectionLogos as $photoID):?>
          <div>
              <a href="<?=$arSection["SECTION_PAGE_URL"]?>"><img src="<?=CFile::GetPath($photoID)?>"></a>
          </div>
        <?endforeach;?>
      </div>
      <p><?=$sectionLangDescription;?></p>
	  <?if($arSection["ID"] == 233):?>
	  <a href="<?=$arSection["SECTION_PAGE_URL"]?>" onclick="gtag_report_conversion(); location.href = '<?=$arSection["SECTION_PAGE_URL"]?>';  return false;"><button class="btn-blue" ><?=GetMessage('CT_BCSL_BUY_BOTTON');?></button>
	  </a>
	  <?else:?>
      <a href="<?=$arSection["SECTION_PAGE_URL"]?>" ><button class="btn-blue"><?=GetMessage('CT_BCSL_BUY_BOTTON');?></button></a>
	  <?endif;?>
  </div>
<?

}
