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
  <div class="support-links">
    <div class="wr">
      <?foreach($arResult["ITEMS"] as $key => $item):?>
      <div class="support-links__section">
        <svg width="80" height="80">
          <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#<?=$item['PROPERTIES']['ATT_ICON']['VALUE']?>"></use>
        </svg>
        
        <h2><?=$item['NAME']?></h2>
        <div class="support-links__list link link--blue">
          <?foreach($item['PROPERTIES']['ATT_LINKS']['VALUE'] as $keyVal => $val):?>
            <a target="_blanck" href="<?=$val?>"><?=$item['PROPERTIES']['ATT_LINKS']['DESCRIPTION'][$keyVal]?></a>
          <?endforeach;?>
          
        </div>
      </div>
      <?endforeach;?>
    </div>
  </div>
<?endif;?>
