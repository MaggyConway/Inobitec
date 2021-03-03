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
<section class="completed-projects">
  <div class="wr">
      <div class="section-header completed-projects__header">
        <svg width="120" height="122">
          <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-projects"></use>
        </svg>
        <h2><?=GetMessage("CT_BNL_H2");?></h2>
        <p><?=GetMessage("CT_BNL_PREVIEWTEXT");?></p>
      </div>
      <div class="completed-projects__list">
        <?foreach($arResult["ITEMS"] as $arItem):?>
          <?//print_r($arItem)?>
          <a target="_blanck" class="completed-projects__card" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
            <?if(isset($arItem['PROPERTIES']['ATT_IFVIDEO']) && $arItem['PROPERTIES']['ATT_IFVIDEO']['VALUE'] == 'y'):?>
            <svg class="completed-projects__video-icon" width="52" height="52">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-video"></use>
            </svg>
            <?endif;?>
            <div class="completed-projects__logo"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="Amycard"></div><span class="completed-projects__more-details"><?=GetMessage("CT_BNL_MORE");?>
              <svg width="19" height="8">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
              </svg></span>
          </a>
        <?endforeach;?>
      </div>
  </div>
</section>