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
  //print_r($item);
?>

<div class="buy_p-product" ng-repeat="liteItem in liteRedactions">
    <div class="buy_p-product-logo">
        <img ng-src="{{liteItem.img}}" />
        <p class="buy_p-product-text-description" ng-bind-html="liteItem.detailText"></p>
    </div>
    <div class="buy_p-product-text">
        
        <p class="buy_p-product-text-prise" ng-class="{'price-font-bold': liteItem.inbasket}">{{liteItem.price}} &#8381;</p>
        <div class="buy_p-btn-case">
            <button ng-click="addToBasket($event, liteItem)" class="btn-blue" ng-class="{'btn-blue-active': liteItem.inbasket}">{{liteItem.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
            <span class="buy_p-clear" onclick="return false;" ng-click="deleteFromBasket($event, liteItem)" ng-show="liteItem.inbasket"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></span>
        </div>
    </div>
</div>
<button class="btn-blue btn-blue-gray" ng-show="checkIfLiteRedactionsFull()" ng-click="addRedactionlite()"><span class="plus">+</span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_LITE_RED');?></button>

<?/*
<div class="page_buy_p_lite" ng-cloak="">
    <div class="page_buy_p_lite_elements">
      
       
      <div class="page_buy_p_lite_element in" ng-class="{'in_basket': liteItem.inbasket}" ng-repeat="liteItem in liteRedactions" >
            <div class="page_buy_p_lite_element_content">
                <div class="page_buy_p_lite_element_name">РЕДАКЦИЯ<span>{{liteItem.shortName}}</span></div>
                <div class="page_buy_p_lite_element_description" ng-bind-html="liteItem.detailText"></div>
                <div class="page_buy_p_lite_element_price rub">{{liteItem.price}}</div>
                <div class="page_buy_p_lite_element_button">
                    <div class="page_buy_button btn_n" ng-click="addToBasket($event, liteItem)" ng-class="{'solid': liteItem.inbasket}">{{proItem.inbasket ? 'В Корзине' : 'В Корзину'}}</div>
                    <a href="javascript:void(0);" class="page_buy_delete_basket" ng-click="deleteFromBasket($event, liteItem)" ng-show="liteItem.inbasket">убрать из корзины</a>
                </div>
                    
            </div>
        </div>
      
      
        </div>
        <div class="page_buy_p_lite_element in left" ng-cloak="">
            <div class="page_buy_p_lite_element_content">
                <div class="page_buy_button btn_n left" ng-show="checkIfLiteRedactionsFull()" ng-click="addRedactionlite()">+ добавить редакцию <?=strtoupper($item["PROPERTIES"]["SHORT_NAME"]["VALUE"])?></div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
    */ ?>

