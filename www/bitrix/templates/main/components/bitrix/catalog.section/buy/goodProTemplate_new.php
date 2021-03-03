<div class="buy_logo" ng-show="showBuyBlock">
  <img src="<?=(SITE_ID != "s1") ? CFile::GetPath($item["PROPERTIES"]["IMAGE_EN"]["VALUE"]) : CFile::GetPath($item["PROPERTIES"]["IMAGE_RU"]["VALUE"])?>" alt="">

<?php
if (SITE_ID == 's1') {
  echo "<p>".$item["~DETAIL_TEXT"]."</p>";

} elseif (SITE_ID == 's2') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_EN"]["~VALUE"]["TEXT"]."</p>";

} elseif (SITE_ID == 's3') {
  echo "<p>".$item["PROPERTIES"]["PREVIEW_TEXT_FR"]["~VALUE"]["TEXT"]."</p>";

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
<div class="buy_product" ng-repeat="(key, proItem) in proRedactions" on-finish-render="proRedactionsRenderEnd" ng-cloak="" ng-show="showBuyBlock">
  <div class="buy_product-row">
    <div class="buy_product-row-container"   ng-class="{'container-option': proItem.inbasket}" >
      <p class="buy_product-row-container-name pro">PRO</p>
      <div class="buy_product-row-container-wrapper" ng-class="{'flex-end': !proItem.inbasket}">
        <div class="buy_product-row-container-wrapper-options" ng-show="proItem.inbasket">
          <p ng-click="reduceGoodAmount(proItem)">
            <svg width="20" height="4" viewBox="0 0 20 4" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="19.8096" width="3.96191" height="19.8095" transform="rotate(90 19.8096 0)" fill="#6E6E6E"/>
            </svg>
          </p>
          <input ng-change="setGoodAmount(proItem)" type="number" ng-model="proItem.quantity" value="1">
          <p ng-click="increaseGoodAmount(proItem)">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="8.11426" width="3.96191" height="19.8095" fill="#6E6E6E"/>
              <rect x="20" y="7.92401" width="3.96191" height="19.8095" transform="rotate(90 20 7.92401)" fill="#6E6E6E"/>
            </svg>
          </p>
        </div>
        <p class="buy_product-row-container-wrapper-price"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{proItem.price}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
      </div>
    </div>
    <div class="buy_product-row-button-container">
      <div class="button-container" ng-class="{'button-container-active': proItem.inbasket}">
        <button class="btn-blue" ng-click="addToBasket($event, proItem)">{{proItem.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
        <span><a href="" ng-click="deleteFromBasket($event, proItem)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a></span>
      </div>
    </div>
  </div>
  <div class="buy_product-row show-title" ng-show="proItem.inbasket">
    <div class="show-title-row selected-options">
      <p class="show-title-row-subtitle"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIONAL_MODULES');?>, <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SELECTED');?>:  <span>{{countBoughtModules(proItem)}}</span></p>
      <span class="price"><?if(SITE_ID != "s1"):?>$ <?endif;?>{{parsePrice(countModulesPrice(proItem))}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span>
    </div>
    <div class="show-title-row">
      <p class="buy_product-row-show-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_ADDADDITIONALMODULES');?></p>
      <img data-open="0" src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/buy_p/blueArrow.svg" alt="">
    </div>
  </div>
  <div class="buy_product-show-case open-case check-case" ng-show="proItem.inbasket">
    <div class="buy_product-show-case-row" ng-repeat="(keyMod, module) in proItem.modules">
      <div class="buy_product-show-case-row-price mobile-column">
        <div>
          <div class="check-case_check-title">
            <div class="check-container">
              <input type="checkbox" ng-checked="proItem.inbasket&&module.inbasket" ng-click="changeModuleStatus($event, module, proItem)" id="sub-option-1.{{keyMod}}">
              <label for="sub-option-1.{{keyMod}}"></label>
            </div>
            <p class="text-blue active" data-name="<?=serialize($item["PROPERTIES"]['NAME_FR'])?>">{{module.name}}</p>
            <p class="check-case_check-title-price"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{module.price}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
          </div>
          <p class="text-blue">{{module.name}}</p>
          <p class="text-black" ng-bind-html="module.detailText"></p>
        </div>
        <p class="mobile-text-right"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{module.price}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
      </div>
      <div class="buy_product-row-button-container">
        <div class="button-container">
          <div class="check-container">
            <input type="checkbox" ng-checked="proItem.inbasket&&module.inbasket" ng-click="changeModuleStatus($event, module, proItem)" id="sub-option-{{keyMod}}">
            <label for="sub-option-{{keyMod}}"></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="buy_product-row show-title" ng-show="proItem.inbasket">
    <div class="show-title-row" ng-show="proItem.license.inbasket">
      <div class="buy_product-row-selected-option 1">
        <p><?=GetMessage("CT_BCS_CATALOG_SECTION_ADDITTIONAL_SUBSCRIBTION")?>: +{{proItem.license.years}} {{proItem.license.years == 1 ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}} <span><?if(SITE_ID == "s2"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,proItem.license.years) * proItem.license.years)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span></p>
      </div>
    </div>
    <div class="show-title-row">
      <p class="buy_product-row-show-title"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADD_SUBSCRIPTION")?></p>
      <img data-open="1"  src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/buy_p/blueArrow.svg" alt="">
    </div>
  </div>
  <div class="buy_product-show-case open-case radio-case" ng-show="proItem.inbasket">
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
        <p class="disable-mobile">30% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <?if(SITE_ID != "s1"):?><span>$</span> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,0))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
        <p ng-class="{'active': ifViewerLicSolid(1,proItem)}"><span><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,1))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
      </div>
      <div class="buy_product-row-button-container">
        <div class="button-container" >
          <div class="check-container">
            <input type="checkbox"  ng-checked="ifViewerLicSolid(1,proItem)" ng-click="changeViewerLic(1,proItem)" id="sub-option-2.1">
            <label for="sub-option-2.1"></label>
          </div>
        </div>
      </div>
    </div>
    <div class="buy_product-show-case-row">
      <div class="buy_product-show-case-row-price">
        <p>+2 <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS")?> <span class="share">-75%</span></p>
        <p class="disable-mobile">25% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <?if(SITE_ID != "s1"):?><span>$</span> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,0))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
        <p ng-class="{'active': ifViewerLicSolid(2,proItem)}"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,2)*2)}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?><span class="sub-text"><?if(SITE_ID == "s2"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicProPrice(proItem,2))}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?> <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
      </div>
      <div class="buy_product-row-button-container">
        <div class="button-container" >
          <div class="check-container">
            <input type="checkbox"   ng-checked="ifViewerLicSolid(2,proItem)" ng-click="changeViewerLic(2,proItem)"  id="sub-option-2.1">
            <label for="sub-option-2.1"></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  
</div>

<div class="buy_current-price" ng-cloak="" ng-show="showBuyBlock" ng-class="{'flex-end': !checkIfProRedactionsFull()}">
  <button class="btn-plus" ng-show="checkIfProRedactionsFull()" ng-click="addRedactionpro()">
    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect x="8.11426" width="3.96191" height="19.8095" fill="#426DA9"></rect>
      <rect x="20" y="7.92401" width="3.96191" height="19.8095" transform="rotate(90 20 7.92401)" fill="#426DA9"></rect>
    </svg>
    <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_PRO_RED');?>
  </button>
  
  <div class="buy_current-price-column" ng-hide="calculateDicomViewerPrice() == 0" >
    <p><?= GetMessage("CT_BCS_CATALOG_SECTION_RENEW_TOTAL")?> <?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?> {{parsePrice(fullPrice)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></p>
    <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>cart/')"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_GO_TO_BASKET');?></button>
  </div>
</div>

