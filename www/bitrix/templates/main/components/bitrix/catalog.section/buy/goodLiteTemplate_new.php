<?php
$mainId = $this->GetEditAreaId($item['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$buyButtonClassName = 'btn-default';

?>

<div class="buy_logo" ng-show="showBuyBlock">
  <img src="<?=(SITE_ID != "s2") ? CFile::GetPath($item["PROPERTIES"]["IMAGE_EN"]["VALUE"]) : CFile::GetPath($item["PROPERTIES"]["IMAGE_RU"]["VALUE"])?>" alt="">
  <?//<p><?=(SITE_ID == "s2") ? $item["PROPERTIES"]["PREVIEW_TEXT_EN"]["~VALUE"]["TEXT"] : $item["~DETAIL_TEXT"]</p>?>

<?

if (SITE_ID == 's1') {
  echo "<p>".$item["~DETAIL_TEXT"]."</p>";

} elseif (SITE_ID == 's2') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_EN"]["~VALUE"]["TEXT"]."</p>";

} elseif (SITE_ID == 's3') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_FR"]["~VALUE"]["TEXT"]."</p>";

  // echo "<pre>"; var_dump($item["PROPERTIES"]["PREVIEW_TEXT_FR"]); echo "</pre>";

} elseif (SITE_ID == 's4') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_DE"]["~VALUE"]["TEXT"]."</p>";

} elseif (SITE_ID == 's5') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_ES"]["~VALUE"]["TEXT"]."</p>";
}




$currencySymbol = '';
if (SITE_ID == 's1') {
  $currencySymbol = '₽';
} elseif (SITE_ID == 's2') {
  $currencySymbol = '$';
} else {
  $currencySymbol = '€';
}
?>


</div>
<div class="buy_product" ng-repeat="(key,liteItem) in liteRedactions" ng-cloak="" ng-show="showBuyBlock">
  <div class="buy_product-row">
    <div class="buy_product-row-container"  ng-class="{'container-option': liteItem.inbasket}">
      <p class="buy_product-row-container-name lite">LITE</p>
      <div class="buy_product-row-container-wrapper" ng-class="{'flex-end': !liteItem.inbasket}">
        <div class="buy_product-row-container-wrapper-options" ng-show="liteItem.inbasket">
          <p ng-click="reduceGoodAmount(liteItem)">
            <svg width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="19.8096" width="3.96191" height="19.8095" transform="rotate(90 19.8096 0)" fill="#6E6E6E"/>
            </svg>
          </p>
          <input ng-change="setGoodAmount(liteItem)" type="number" ng-pattern="/^[0-9]{1,2}$/" ng-model="liteItem.quantity" value="1">
          <p ng-click="increaseGoodAmount(liteItem)">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="8.11426" width="3.96191" height="19.8095" fill="#6E6E6E"/>
              <rect x="20" y="7.92401" width="3.96191" height="19.8095" transform="rotate(90 20 7.92401)" fill="#6E6E6E"/>
            </svg>
          </p>
        </div>
        <p class="buy_product-row-container-wrapper-price"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{liteItem.price}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
      </div>
    </div>
    <div class="buy_product-row-button-container">
      <div class="button-container" ng-class="{'button-container-active': liteItem.inbasket}">
        <button ng-click="addToBasket($event, liteItem)" class="btn-blue" >{{liteItem.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
        <span><a href="" ng-click="deleteFromBasket($event, liteItem)" ng-show="liteItem.inbasket"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a></span>
      </div>
    </div>
  </div>
  <div class="buy_product-row show-title" ng-show="liteItem.inbasket">
    <div class="show-title-row" ng-show="liteItem.license.inbasket">
      <div class="buy_product-row-selected-option">
        <p><?=GetMessage("CT_BCS_CATALOG_SECTION_ADDITTIONAL_SUBSCRIBTION")?>: +{{liteItem.license.years}} {{liteItem.license.years == 1 ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}} <span><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicLitePrice(liteItem.price,liteItem.license.years) * liteItem.license.years)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span></p>
      </div>
    </div>
    <div class="show-title-row">
      <p class="buy_product-row-show-title"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADD_SUBSCRIPTION")?></p>
      <img data-open="0" src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/buy_p/blueArrow.svg" alt="">
    </div>
  </div>
  <div class="buy_product-show-case open-case radio-case" ng-show="liteItem.inbasket">
    <p class="buy_product-show-case-title"><?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED_MORE")?></p>
    <div class="buy_product-show-case-sub-title">
      <div>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></p>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></p>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></p>
      </div>
    </div>
    <div class="buy_product-show-case-row">
      <div class="buy_product-show-case-row-price">
        <p>+1 <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR")?><span class="share">-70%</span></p>
        <p class="disable-mobile">30% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(liteItem.price)}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
        <p ng-class="{'active': ifViewerLicSolid(1,liteItem)}"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicLitePrice(liteItem.price,1))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
      </div>
      <div class="buy_product-row-button-container">
        <div class="button-container">
          <div class="check-container">
            <input type="checkbox"  ng-checked="ifViewerLicSolid(1,liteItem)" ng-click="changeViewerLic(1,liteItem)" id="sub-option-2.1">
            <label for="sub-option-2.1"></label>
          </div>
        </div>
      </div>
    </div>
    <div class="buy_product-show-case-row">
      <div class="buy_product-show-case-row-price">
        <p>+2 <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS")?> <span class="share">-75%</span></p>
        <p class="disable-mobile">25% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(liteItem.price)}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
        <p ng-class="{'active': ifViewerLicSolid(2,liteItem)}">
          <?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>
            {{ parsePrice(calculateLicLitePrice(liteItem.price,2)*2) }}
            <?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?>
              <span class="sub-text">
                <?if(SITE_ID == "s2"):?><?=$currencySymbol?> <?endif;?>
                  {{parsePrice(calculateLicLitePrice(liteItem.price,2)) }}
                  <?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?> 
                  <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?>
              </span>
        </p>
      </div>
      <div class="buy_product-row-button-container">
        <div class="button-container ">
          <div class="check-container">
            <input type="checkbox"  ng-checked="ifViewerLicSolid(2,liteItem)" ng-click="changeViewerLic(2,liteItem)" id="sub-option-2.1">
            <label for="sub-option-2.1"></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<button class="btn-plus" ng-show="showBuyBlock && checkIfLiteRedactionsFull()" ng-cloak="" ng-click="addRedactionlite()">
  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="8.11426" width="3.96191" height="19.8095" fill="#426DA9"></rect>
    <rect x="20" y="7.92401" width="3.96191" height="19.8095" transform="rotate(90 20 7.92401)" fill="#426DA9"></rect>
  </svg>
  <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_LITE_RED');?>
</button>

<hr ng-show="showBuyBlock" />



