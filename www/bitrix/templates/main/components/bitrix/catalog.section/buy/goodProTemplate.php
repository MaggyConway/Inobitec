<?php
$APPLICATION->AddHeadScript('/verstka_new/js/buy_p.js');
?>

<div ng-repeat="(key, proItem) in proRedactions" on-finish-render="proRedactionsRenderEnd">
  <div class="buy_p-gray-container buy_p-gray-container-main">
      <div class="buy_p-product-logo">
          <img ng-src="{{proItem.img}}">
          <p class="buy_p-product-text-description" ng-bind-html="proItem.detailText"></p>
      </div>
      <div class="buy_p-product-text">
          
          <p class="buy_p-product-text-prise" ng-class="{'price-font-bold': proItem.inbasket}">{{proItem.price}} &#8381;</p>
          <div class="buy_p-btn-case">
              <button class="btn-blue" data-show="{{key}}" ng-disabled="proItem.inbasket" ng-click="addToBasket($event, proItem)" ng-class="{'btn-blue-active': proItem.inbasket}">{{proItem.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
              <span class="buy_p-clear" ng-click="deleteFromBasket($event, proItem)" ng-show="proItem.inbasket"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></span>
          </div>
      </div>
  </div>
  <div class="buy_p-gray-container-show">
      <div class="buy_p-gray-container-show-wrapper">
          <p class="buy_p-gray-container-show-subtext"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIONAL_MODULES');?></p>
          <span></span>
      </div>
      <div class="buy_p-gray-container-show-wrapper">
          <p class="buy_p-gray-container-show-subtext"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIONAL_MODULES');?>, <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SELECTED');?>: {{countBoughtModules(proItem)}}</p>
          <p class="buy_p-gray-container-show-showtext" data-show="{{key}}"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_EXPAND');?></p>
      </div>
    
  </div>
  <div class="buy_p_showed-container">
      <div class="buy_p-gray-container buy_p-gray-container-sub" ng-repeat="module in proItem.modules">
          <div class="buy_p-product-name">
              <p>{{module.name}}</p>
              <p class="buy_p-product-text-description" ng-bind-html="module.detailText"></p>
          </div>
          <div class="buy_p-product-text">
              
              <p class="buy_p-product-text-prise" ng-class="{'price-font-bold': proItem.inbasket&&module.inbasket}">{{module.price}} &#8381;</p>
              <div class="buy_p-btn-case">
                  <button class="btn-blue" ng-click="addModuleToBasket($event, module, proItem)" ng-class="{'btn-blue-active': proItem.inbasket&&module.inbasket, 'btn-blue-disable':!proItem.inbasket}">{{proItem.inbasket&&module.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
                  <span class="buy_p-clear" ng-click="deleteFromBasket($event, module)" ng-show="proItem.inbasket&&module.inbasket"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></span>
              </div>
          </div>
      </div>
      <div class="buy_p-gray-container-close">
          <div class="buy_p-gray-container-show-wrapper">
              <p class="buy_p-gray-container-close-showtext" data-close="{{key}}"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ROLLUP');?></p>
          </div>
      </div>
  </div>
  
</div>



<?php /*?>
<div ng-repeat="proItem in proRedactions">
  <div class="buy_p-row-gray-main">
      <div class="buy_p-row-gray-main-container">
          <div class="buy_p-row-gray-logo">
              <img ng-src="{{proItem.img}}">
          </div>
          <div class="buy_p-row-gray-main-text">
              <p ng-bind-html="proItem.detailText"></p>
              <div class="buy_p-row-gray-main-text-wrapper">
                  <p class="buy_p-price">{{proItem.price}} &#8381</p>
                  <div class="buy_p-row-gray-main-text-settings">
                      <button class="btn-blue" ng-click="addToBasket($event, proItem)" ng-class="{'btn-blue-active': proItem.inbasket}">{{proItem.inbasket ? 'В Корзине' : 'В Корзину'}}</button>
                      <a href="javascript:void(0);" class="page_buy_delete_basket" ng-click="deleteFromBasket($event, proItem)" ng-show="proItem.inbasket">убрать из корзины</a>
                  </div>
              </div>
          </div>
      </div>
      <div class="buy_p-row-gray-main-container buy_p-show-text">
          <div>
              <p>Дополнительные модули для Pro, выбрано: {{countBoughtModules(proItem)}}</p>
              <p class="buy_p-show">развернуть</p>
          </div>
          <div>
              <p>Дополнительные модули для Pro</p>
              <span></span>
          </div>
      </div>
  </div>
  <div class="buy_p-row-gray-additional">
      <div class="buy_p-row-gray-additional-container" ng-repeat="module in proItem.modules">
          <p class="buy_p-blue-text">{{module.name}}</p>
          <p ng-bind-html="module.detailText"></p>
          <div class="buy_p-row-gray-additional-text-wrapper">
              <p class="buy_p-price">{{module.price}} &#8381</p>
              <div class="buy_p-row-gray-additional-text-settings">
                  <button class="btn-blue" ng-click="addModuleToBasket($event, module, proItem)" ng-class="{'btn-blue-active': proItem.inbasket&&module.inbasket, 'disabled':!proItem.inbasket}">{{proItem.inbasket&&module.inbasket ? 'В Корзине' : 'В Корзину'}}</button>
                  <a href="javascript:void(0);" class="page_buy_delete_basket" ng-click="deleteFromBasket($event, module)" ng-show="proItem.inbasket&&module.inbasket">убрать из корзины</a>
              </div>
          </div>
      </div>
      <div class="buy_p-row-gray-additional-container">
          <p class="buy_p-hide">свернуть</p>
      </div>
  </div>
</div>  

<?php */?>
</div>
<div class="page_buy_p">
<div class="page_buy_p_pro" ng-cloak="">
    <div class="page_buy_p_pro">
		<div class="page_buy_p_pro_element in left">
			<div class="page_buy_p_pro_element_content">
				<div class="page_buy_button btn_n left disabled js-add_pro" ng-show="checkIfProRedactionsFull()" ng-click="addRedactionpro()"><span class="plus">+</span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_PRO_RED');?></div>
				<div class="clear"></div>
                <div ng-hide="calculateDicomViewerPrice() == 0" class="page_buy_p_total_element_price page_buy_p_total_element_price-new" ng-cloak=""> <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_AMOUNT')?> <span class="price-font-bold">{{parsePrice(calculateDicomViewerPrice())}} &#8381;</span> <span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED');?></span></div>
			</div>
		</div>
	</div>
</div>

