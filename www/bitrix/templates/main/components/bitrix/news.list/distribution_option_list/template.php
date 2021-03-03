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
  <div class="distribution-conditions">
	<div class="wr">
        <?foreach($arResult["ITEMS"] as $key => $item):?>
          <div class="accordion">
            <div class="accordion__header"><?=$item['NAME']?>
              <svg width="64" height="64">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-accordion-arrow"></use>
              </svg>
            </div>
            <div class="accordion__content">
              <ul class="list distribution-conditions__list">
                <?foreach($item['PROPERTIES']['ATT_OPTIONS']['VALUE'] as $val):?>
                  <li><?=$val?></li>
                <?endforeach;?>
              </ul>
            </div>
          </div>
        <?endforeach;?>
	</div>
  </div>

<?endif;?>
