<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<style>
  [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
    display: none !important;
   }
</style>
<div class="page_basket" ng-controller="cartController" ng-cloak=""  ng-init=" site='<?=SITE_ID?>'; initCart()" >
    <div class="page_basket_message" ng-show="!(checkIfViewerInBasket() || serverRedaction || beforeFirstLoading || licenseUpdateViewerCount() > 0 || newServConn.inbasket)" >
        <div class="page_basket_message_img">
			<img src="/bitrix/components/bitrix/sale.basket.basket/templates/.default/images/empty_cart.svg" />
		</div>
		<div class="page_basket_message_title">
			<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EMPTY_CART');?>
		</div>
		<div class="buttons_box center">
			<a href="<?=SITE_DIR?>buy/" class="btn_n big"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CONTINUE_SHOP');?></a>
		</div>
	</div>
    <div class="basket" ng-show="checkIfViewerInBasket() || serverRedaction || licenseUpdateViewerCount() > 0 || newServConn.inbasket" ng-cloak="">
      <div class="basket_title" ng-show="checkIfViewerInBasket()">
        <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRODUCT');?></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.AMOUNT');?></span></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRICE');?></span></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?></span></p>
      </div>
      <div class="basket_container" ng-show="checkIfViewerInBasket()">
        <div class="basket_container-title">
          <a href="<?=SITE_DIR?>buy/dicomviewer/" class="basket_container-title-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.VIEWER');?></a>
          <a href="<?=SITE_DIR?>buy/dicomviewer/" class="basket_container-title-option"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.RETURN_SELECTION');?></a>
        </div>
        <div class="basket_container-check basket_container-check-active" ng-repeat="liteItem in liteRedactions | filter: itemFilter">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name green">Lite</p>
            <p class="basket_container-check-sum"><span>{{liteItem.quantity}}</span></p>
            <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{liteItem.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(liteItem.price)*liteItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="delLiteFromBasket($event, liteItem)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          </div>
          <div class="basket_container-check-show-title" ng-show="liteItem.license.inbasket">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{liteItem.license.years}} {{liteItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active" ng-show="liteItem.license.inbasket">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{liteItem.license.years}} {{liteItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{liteItem.license.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(liteItem.license.price)*liteItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red" ng-click="defaultDelGood(liteItem.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            </div>
          </div>
          <p class="control" ng-show="liteItem.license.inbasket"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
        <div class="basket_container-check basket_container-check-active" ng-repeat="(key, proItem) in proRedactions | filter: itemFilter">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name purple">pro</p>
            <p class="basket_container-check-sum"><span>{{proItem.quantity}}</span></p>
            <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{proItem.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(proItem.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="delProFromBasket($event, proItem)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          </div>
          <div class="basket_container-check-show-title" ng-show="{{proItem.license.inbasket}}">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{proItem.license.years}} {{proItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active" ng-show="{{proItem.license.inbasket}}">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{proItem.license.years}} {{proItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price 12"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{proItem.license.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price 12"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(proItem.license.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red" ng-click="defaultDelGood(proItem.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            </div>
          </div>
          <div class="basket_container-check-show-title" ng-show="countBoughtModules(proItem) > 0">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_MODULES');?>, <?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TEXT_SELECTED');?>: {{countBoughtModules(proItem)}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active">
            <div class="basket_container-check-container" ng-repeat="module in proItem.modules | filter: itemFilter">
              <p class="basket_container-check-name">{{module.name}}</p>
              <p class="basket_container-check-sum"><span></span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{module.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(module.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red"  ng-click="delModuleFromBasket($event, module)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            </div>

          </div>

          <p class="control" ng-show="countBoughtModules(proItem) > 0 || proItem.license.inbasket" data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
      </div>
      <div class="basket_title" ng-show="serverRedaction">
        <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRODUCT');?></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.AMOUNT_CONNECTIONS');?></span></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRICE');?></span></p>
        <p><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?></span></p>
      </div>
      <div class="basket_container" ng-show="serverRedaction">
        <div class="basket_container-title">
          <a href="<?=SITE_DIR?>buy/dicomserver/" class="basket_container-title-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SERVER');?></a>
          <a href="<?=SITE_DIR?>buy/dicomserver/" class="basket_container-title-option"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.RETURN_SELECTION');?></a>
        </div>

        <div class="basket_container-check proItem.license.inbasket">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name black"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SERVER');?></p>
            <p class="basket_container-check-sum"><span>{{serverRedaction.serverConnections}}</span></p>
            <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.offers[1].price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.offers[serverRedaction.serverConnections].price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="delServerFromBasket($event)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          </div>

          <div class="basket_container-check-show-title"  ng-show="serverRedaction.license && serverRedaction.license.inbasket">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{serverRedaction.license.years}} {{serverRedaction.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active"  ng-show="serverRedaction.license && serverRedaction.license.inbasket">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{serverRedaction.license.years}} {{serverRedaction.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.license.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.license.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red" ng-click="defaultDelGood(serverRedaction.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            </div>
          </div>
          <p class="control"  ng-show="serverRedaction.license && serverRedaction.license.inbasket"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
      </div>
      <div class="basket_container" ng-show="licenseUpdateViewerCount() > 0 || newServConn.inbasket">
        <div class="basket_container-title">
          <p class="basket_container-title-name black-bold"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.LICENSE_RENEWAL');?></p>
          <!--a href="<?//=SITE_DIR?>buy/dicomviewer/" class="basket_container-title-option"><?//=GetMessage('INOBITEK.SALE.BASKET.BASKET.RETURN_SELECTION');?></a-->
        </div>

        <div class="basket_container-check basket_container-check-active" ng-repeat="itemUpdate in extRedactions.lite | filter: licenseUpdateFilter">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name black">{{itemUpdate.lic}}</p>
            <p class="basket_container-check-key">
              <span class="key-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>
              <span class="key-main">{{itemUpdate.licKey}}</span>
            </p>
            <p class="basket_container-check-current-price"><span></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            
          </div>

          <div class="basket_container-check-show-title">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red"></p>
            </div>
          </div>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
        
        <div class="basket_container-check basket_container-check-active" ng-repeat="itemUpdate in extRedactions.pro | filter: licenseUpdateFilter">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name black">{{itemUpdate.lic}}</p>
            <p class="basket_container-check-key">
              <span class="key-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>
              <span class="key-main">{{itemUpdate.licKey}}</span>
            </p>
            <p class="basket_container-check-current-price"><span></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            
          </div>

          <div class="basket_container-check-show-title">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red"></p>
            </div>
          </div>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
        
        <div class="basket_container-check basket_container-check-active" ng-repeat="itemUpdate in extRedactions.server | filter: licenseUpdateFilter">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name black">{{itemUpdate.lic}}</p>
            <p class="basket_container-check-key">
              <span class="key-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>
              <span class="key-main">{{itemUpdate.licKey}}</span>
            </p>
            <p class="basket_container-check-current-price"><span></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            
          </div>

          <div class="basket_container-check-show-title">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="basket_container-check-sum"><span>+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red"></p>
            </div>
          </div>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
        
        
        <div class="basket_container-check basket_container-check-active" ng-show="newServConn.inbasket">
          <div class="basket_container-check-container">
            <p class="basket_container-check-name black">{{newServConn.lic}}</p>
            <p class="basket_container-check-key">
              <span class="key-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>
              <span class="key-main">{{newServConn.licKey}}</span>
            </p>
            <p class="basket_container-check-current-price"><span></span></p>
            <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{newServConn.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
            <p class="basket_container-check-remove red" ng-click="defaultDelGood(newServConn.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
            
          </div>

          <div class="basket_container-check-show-title">
            <p class="basket_container-check-show-title-sub-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_CONNECTIONS');?>: {{newServConn.updateLic.connectionsNum}}</p>
          </div>
          <div class="basket_container-check-show-list basket_container-check-show-list-active">
            <div class="basket_container-check-container">
              <p class="basket_container-check-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_CONNECTIONS');?></p>
              <p class="basket_container-check-sum"><span>+ {{newServConn.updateLic.connectionsNum}}</span></p>
              <p class="basket_container-check-current-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{newServConn.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-price"><span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{newServConn.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></span></p>
              <p class="basket_container-check-remove red"></p>
            </div>
          </div>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
        
        
        
        
      </div>
      <div class="buy_current-price">
        <div class="buy_current-price-column">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?> <?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{calculateFullPrice()}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
          <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>cart/order/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CHECKOUT');?></button>
          <p class="buy_current-price-column-remove-basket" ng-click="clearBasket()"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CLEAR_BASKET');?></p>
          <p class="buy_current-price-column-remove-basket" ng-click="redirect('<?=SITE_DIR?>buy/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CONTINUE_SHOP');?></p>
        </div>
      </div>
    </div>
    <div class="basket-mobile" ng-show="checkIfViewerInBasket() || serverRedaction || licenseUpdateViewerCount() > 0 || newServConn.inbasket">
      <div class="basket-mobile-title"  ng-show="checkIfViewerInBasket()">
        <p ng-click="redirect('<?=SITE_DIR?>buy/dicomviewer/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.VIEWER');?></p>
        <p ng-click="redirect('<?=SITE_DIR?>buy/dicomviewer/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.RETURN_SELECTION');?></p>
      </div>

      <div class="basket-mobile-check"  ng-repeat="liteItem in liteRedactions | filter: itemFilter">
        <div class="basket-mobile-check-title">
          <p class="green">LITE</p>
          <p class="red"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
        </div>
        <div class="basket-mobile-check-subtitle">
          <p class="basket-mobile-check-subtitle-sum"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.AMOUNT');?></span>{{liteItem.quantity}}</p>
          <p class="basket-mobile-check-subtitle-current-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRICE');?></span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{liteItem.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
          <p class="basket-mobile-check-subtitle-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?></span><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(liteItem.price)*liteItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
        </div>

        <div class="basket-mobile-check-show-title"  ng-show="liteItem.license.inbasket">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{liteItem.license.years}} {{liteItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
        </div>
        <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active"  ng-show="liteItem.license.inbasket">
          <div class="basket-mobile-check-show-container-row">
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="column-sub-name">+{{liteItem.license.years}} {{liteItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
            </div>
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-remove red"  ng-click="defaultDelGood(liteItem.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
              <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(liteItem.license.price)*liteItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
            </div>
          </div>
        </div>

        <p class="control" ng-show="liteItem.license.inbasket"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>

      <div class="basket-mobile-check"  ng-repeat="(key, proItem) in proRedactions | filter: itemFilter">
        <div class="basket-mobile-check-title">
          <p class="purple">pro</p>
          <p class="red"  ng-click="delProFromBasket($event, proItem)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
        </div>
        
        
        <div class="basket-mobile-check-subtitle">
          <p class="basket-mobile-check-subtitle-sum"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.AMOUNT');?></span>{{proItem.quantity}}</p>
          <p class="basket-mobile-check-subtitle-current-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRICE');?></span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{proItem.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
          <p class="basket-mobile-check-subtitle-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?></span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(proItem.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
        </div>

        <div class="basket-mobile-check-show-title" ng-show="{{proItem.license.inbasket}}">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{proItem.license.years}} {{proItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
        </div>
        <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active" ng-show="{{proItem.license.inbasket}}">
          <div class="basket-mobile-check-show-container-row">
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="column-sub-name">+{{proItem.license.years}} {{proItem.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
            </div>
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-remove red" ng-click="defaultDelGood(proItem.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
              <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(proItem.license.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
            </div>
          </div>
        </div>

        <div class="basket-mobile-check-show-title">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_MODULES');?>, <?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TEXT_SELECTED');?>: {{countBoughtModules(proItem)}}</p>
        </div>
        <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active">
          <div class="basket-mobile-check-show-container-row"  ng-repeat="module in proItem.modules | filter: itemFilter">
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-name">{{module.name}}</p>
              <p class="column-sub-name"></p>
            </div>
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-remove red" ng-click="delModuleFromBasket($event, module)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
              <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{parsePrice(strPriceToNumber(module.price)*proItem.quantity)}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
            </div>
          </div>
        </div>

        <p class="control" ng-show="{{proItem.license.inbasket || countBoughtModules(proItem) > 0}}"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>

      <div class="basket-mobile-title" ng-show="serverRedaction">
        <p ng-click="redirect('<?=SITE_DIR?>buy/dicomserver/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SERVER');?></p>
        <p ng-click="redirect('<?=SITE_DIR?>buy/dicomserver/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.RETURN_SELECTION');?></p>
      </div>

      <div class="basket-mobile-check" ng-show="serverRedaction">
        <div class="basket-mobile-check-title">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SERVER');?></p>
          <p class="red" ng-click="delServerFromBasket($event)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
        </div>
        
        
        <div class="basket-mobile-check-subtitle">
          <p class="basket-mobile-check-subtitle-sum"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.AMOUNT_CONNECTIONS');?></span>{{serverRedaction.serverConnections}}</p>
          <p class="basket-mobile-check-subtitle-current-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.PRICE');?></span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.offers[1].price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
          <p class="basket-mobile-check-subtitle-price"><span class="subtitle-title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?></span><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.offers[serverRedaction.serverConnections].price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
        </div>

        <div class="basket-mobile-check-show-title" ng-show="serverRedaction.license && serverRedaction.license.inbasket">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?>: +{{serverRedaction.license.years}} {{serverRedaction.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
        </div>
        <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active"  ng-show="serverRedaction.license && serverRedaction.license.inbasket">
          <div class="basket-mobile-check-show-container-row">
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SUBSCRIBE');?></p>
              <p class="column-sub-name">+{{serverRedaction.license.years}} {{serverRedaction.license.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
            </div>
            <div class="basket-mobile-check-show-container-row-column">
              <p class="column-remove red" ng-click="defaultDelGood(serverRedaction.license)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
              <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{serverRedaction.license.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
            </div>
          </div>
        </div>

        <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"  ng-show="serverRedaction.license && serverRedaction.license.inbasket"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>
      
      
        <div class="basket-mobile-title" ng-show="licenseUpdateViewerCount() > 0">
          <p calss="black-bold"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.LICENSE_RENEWAL');?></p>
          
        </div>

        <div class="basket-mobile-check" ng-repeat="itemUpdate in extRedactions.lite | filter: licenseUpdateFilter" >
          <div class="basket-mobile-check-title title-key">
            <p class="green">{{itemUpdate.lic}}</p>
            <p class="basket-mobile-check-title-key"><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>{{itemUpdate.licKey}}</p>
          </div>

          <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active">
            <div class="basket-mobile-check-show-container-row">
              <div class="basket-mobile-check-show-container-row-column">
                <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?></p>
                <p class="column-sub-name">+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
              </div>
              <div class="basket-mobile-check-show-container-row-column">
                
                <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
              </div>
            </div>
          </div>
          <p class="column-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
        </div>
       
      <div class="basket-mobile-check" ng-repeat="itemUpdate in extRedactions.pro | filter: licenseUpdateFilter">
          <div class="basket-mobile-check-title title-key">
            <p class="purple">{{itemUpdate.lic}}</p>
            <p class="basket-mobile-check-title-key"><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>{{itemUpdate.licKey}}</p>
          </div>

          <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active">
            <div class="basket-mobile-check-show-container-row">
              <div class="basket-mobile-check-show-container-row-column">
                <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?></p>
                <p class="column-sub-name">+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
              </div>
              <div class="basket-mobile-check-show-container-row-column">
                
                <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
              </div>
            </div>
          </div>
          <p class="column-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>
      
      <div class="basket-mobile-check" ng-repeat="itemUpdate in extRedactions.server | filter: licenseUpdateFilter">
          <div class="basket-mobile-check-title title-key">
            <p class="black">{{itemUpdate.lic}}</p>
            <p class="basket-mobile-check-title-key"><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>{{itemUpdate.licKey}}</p>
          </div>

          <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active">
            <div class="basket-mobile-check-show-container-row">
              <div class="basket-mobile-check-show-container-row-column">
                <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_SUBSCRIBE');?></p>
                <p class="column-sub-name">+{{itemUpdate.updateLic.years}} {{itemUpdate.updateLic.years == 1 ? '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEAR');?>' : '<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.YEARS');?>'}}</p>
              </div>
              <div class="basket-mobile-check-show-container-row-column">
                
                <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{itemUpdate.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
              </div>
            </div>
          </div>
          <p class="column-remove red" ng-click="defaultDelGood(itemUpdate.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>
      
      <div class="basket-mobile-check" ng-show="newServConn.inbasket">
          <div class="basket-mobile-check-title title-key">
            <p class="black">{{newServConn.lic}}</p>
            <p class="basket-mobile-check-title-key"><span><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.KEY');?></span>{{newServConn.licKey}}</p>
          </div>

          <div class="basket-mobile-check-show-container basket-mobile-check-show-container-active">
            <div class="basket-mobile-check-show-container-row">
              <div class="basket-mobile-check-show-container-row-column">
                <p class="column-name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ADDITIONAL_CONNECTIONS');?></p>
                <p class="column-sub-name">+ {{newServConn.updateLic.connectionsNum}}</p>
              </div>
              <div class="basket-mobile-check-show-container-row-column">
                
                <p class="column-price"><?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{newServConn.updateLic.price}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
              </div>
            </div>
          </div>
          <p class="column-remove red" ng-click="defaultDelGood(newServConn.updateLic)"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.REMOVE_FROM_BASKET');?></p>
          <p class="control"  data-open="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.EXPAND');?>" data-close="<?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?>"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ROOLUP');?></p>
      </div>

      <div class="buy_current-price">
        <div class="buy_current-price-column">
          <p><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.TOTAL');?> <?if(SITE_ID == "s2"):?>$ <?endif;?><?php if(SITE_ID == "s3" || SITE_ID == "s4" || SITE_ID == "s5"):?>€ <?php endif;?>{{calculateFullPrice()}}<?if(SITE_ID == "s1"):?> ₽<?endif;?></p>
          <button class="btn-blue"  ng-click="redirect('<?=SITE_DIR?>cart/order/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CHECKOUT');?></button>
          <p class="buy_current-price-column-remove-basket" ng-click="clearBasket()"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CLEAR_BASKET');?></p>
          <p class="buy_current-price-column-remove-basket" ng-click="redirect('<?=SITE_DIR?>buy/')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CONTINUE_SHOP');?></p>
        </div>
      </div>
    </div>

</div>  

<div class="popup"  ng-controller="popupController" id="basketAlert">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CLEAR_BASKET');?>?</p>
        <form>
            <p class="pass-success-text"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ACCEPTMENT');?></p>
            <div class="popup-main_row">
                <button class="btn-blue btn-red authorization-button" ng-click='clearBasketPopup()'><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CLEAR');?></button>
                <button class="btn-blue authorization-button" ng-click="closePopup('basketAlert')"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CANCEL');?></button>
            </div>
        </form>
    </div>
</div>