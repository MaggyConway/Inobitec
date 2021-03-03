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
<?if(count($arResult["ITEMS"]) > 0):?>
  <div class="distribution-header__products">
      <?foreach($arResult["ITEMS"] as $key => $item):?>
        <?if($item['PROPERTIES']['ATT_ICON']['VALUE']):?>
          <div class="distribution-header__product-logo">
          	<div style="background: url(<?=CFile::GetPath($item['PROPERTIES']['ATT_ICON']['VALUE'])?>) no-repeat left top;" title="<?=$item['NAME']?>"></div>
            <!-- <img alt="<?//=$item['NAME']?>" src=""> -->
          </div>
        <?endif;?>
      <?endforeach;?>
  </div>
<?endif;?>
