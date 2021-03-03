<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */
if(isset($arResult["UF_SERIES"])){
  $rsType = CUserFieldEnum::GetList(array(), array("ID" => $arResult["UF_SERIES"]));
  if ($arType = $rsType->GetNext())
    $typeVal = $arType["VALUE"];
}
$cartPreloader = true;
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mask.js');
?>

<script>
    $(document).ready(function(){
        $('.page_buy-key-mask').mask('0000-0000-0000-0000');
    });
</script>
<div class="popup" id="page_buy_p-info">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SERVER_KEY_NOTICE');?></p>
    </div>
</div>

<div class="page_buy_p" ng-controller="buyServerController" >
    <div class="buy_s">
        <div class="buy_s-row">
            <div class="buy_s-logo">
                <div class="buy_s-logo-container">
                    <img src="<?= $arResult["PICTURE"]["SRC"]?>">
                    <p><?=(SITE_ID == "s2") ? $arResult["UF_SHORT_NAME_EN"] : $arResult["UF_SHORT_NAME"]?></p>
                </div>
            </div>
            <div class="buy_s-description">
                <?=(SITE_ID == "s2") ? $arResult["~UF_SEC_DET_DESCRIP"] : $arResult["~UF_DETAIL_DESCRIP"]?>
            </div>
        </div>
        <div class="buy_s-row">
          <?foreach($arResult["UF_LOGOS"] as $photoID):?>
            <div class="buy_s-logo" ng-cloak="">
                <img ng-src="{{serverRedaction.img}}" >
            </div>
          <?endforeach;?>
        </div>
    </div>

  <div class="page_buy_p_counter">
		<div class="page_buy_p_counter_titles">
			<div class="page_buy_p_counter_element count"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_AE_CONNECTIONS');?></div>
			<div class="page_buy_p_counter_element price"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_COST');?></div>
			<div class="page_buy_p_counter_element buy"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TITLE_PRODUCT_KEY');?></div>
		</div>
		<div class="page_buy_p_counter_elements " ng-class="{'in_basket': serverRedaction.inbasket }" ng-init="sectionID=<?=$arResult["ID"]?>; site='<?=SITE_ID?>'; initDicomserverPage()" ng-cloak="">
			<div class="page_buy_p_counter_element count">
				<div class="title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_AE_CONNECTIONS');?></div>
                
                <div class="range-slider-container">
                    <div class="slider"></div>
                </div>
                
				<!--div class="input_range">
					<div class="val_mrg">
						<div class="val"></div>
					</div>
					<input type="range" min="1" max="50" value="1" ng-model="serverConnections" />
					<div class="vals">
						<div class="min"></div>
						<div class="max"></div>
					</div>
				</div-->
				<p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_MORE50_INFO');?></p>
			</div>
			<div class="page_buy_p_counter_element price">
				<div class="title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_COST');?></div>
				{{serverRedaction.offers[serverConnections].price}} &#8381; <span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED');?></span>
			</div>
			<div class="page_buy_p_counter_element buy">
              
                <p class="page_buy-mobile-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TITLE_PRODUCT_KEY');?></p>
                <label class="input" ng-class="{'error': tryAddServer && !serverProductKey && !serverRedaction.inbasket}">
                  <input type="text" class="page_buy-key page_buy-key-mask" ng-disabled="serverRedaction.inbasket" ng-model="serverProductKey" name="serverProductKey" minlength="19" required="" placeholder="1111-2222-3333-4444"> 
                  <div class="error-text" ng-show="tryAddServer && !serverProductKey && !serverRedaction.inbasket" data-empty-text="<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_SERVER_KEY_ERROR');?>"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_SERVER_KEY_ERROR');?></div>  
                </label>
                <div class="page_buy-info" onclick="Popup('page_buy_p-info')" title="подробнее">?</div>
                <div class="page_buy_p_total_element_button">
                  <div class="page_buy_button btn_n" ng-class="{'solid': serverRedaction.inbasket }" ng-click="addServerToBasket()" >{{serverRedaction.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
                  <a href="#" onclick="return false;" ng-show="serverRedaction.inbasket" ng-click="delServerFromBasket()" class="page_buy_delete_basket" ><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
                </div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_updates" ng-show="serverRedaction.inbasket">
		<div class="page_buy_p_updates_title">
			<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIOANL_SUBSCRIBE');?>
		</div>
		<div class="page_buy_p_updates_subtitle">
			<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED_MORE');?>
		</div>
		<div class="page_buy_p_updates_elements" ng-cloak="">
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></span></div>
					<div class="page_buy_p_total_element_percent"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></span></div>
					<div class="page_buy_p_total_element_price"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></span></div>
					<div class="page_buy_p_total_element_button">
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in" ng-class="ifServerLicSolid(1)" >
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+1 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?> <i>-70%</i></div>
					<div class="page_buy_p_total_element_percent">30% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{serverRedaction.offers[serverConnections].price}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifServerLicSolid(1)}">{{parsePrice(yearLicPrice["1"])}} &#8381;</div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addServerLic(1)" ng-class="{'solid' : ifServerLicSolid(1), 'disabled' : !ifServerLicActive(1)}">{{ifServerLicSolid(1) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
						<a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delServerLic(1)" ng-show="ifServerLicSolid(1)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in" ng-class="ifServerLicSolid(2)">
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+2 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?> <i>-75%</i></div>
					<div class="page_buy_p_total_element_percent">25% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{serverRedaction.offers[serverConnections].price}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifServerLicSolid(2)}">{{parsePrice(yearLicPrice["2"]*2)}} &#8381; <span>{{parsePrice(yearLicPrice["2"])}} &#8381; <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addServerLic(2)" ng-class="{'solid' : ifServerLicSolid(2), 'disabled' : !ifServerLicActive(2)}">{{ifServerLicSolid(2) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
                        <a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delServerLic(2)" ng-show="ifServerLicSolid(2)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in"  ng-class="ifServerLicSolid(3)">
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+3 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?> <i>-80%</i></div>
					<div class="page_buy_p_total_element_percent">20% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{serverRedaction.offers[serverConnections].price}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifServerLicSolid(3)}">{{parsePrice(yearLicPrice["3"]*3)}} &#8381; <span>{{parsePrice(yearLicPrice["3"])}} &#8381; <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addServerLic(3)" ng-class="{'solid' : ifServerLicSolid(3), 'disabled' : !ifServerLicActive(3)}">{{ifServerLicSolid(3) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
                        <a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delServerLic(3)" ng-show="ifServerLicSolid(3)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="page_buy_p_summ" ng-cloak=""><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TOTAL');?> <span>{{parsePrice(calcServerFullPrices())}} &#8381;</span></div>
	<a href="<?=SITE_DIR?>cart/" class="page_buy_button to_basket btn_n"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_GO_TO_BASKET');?></a>
</div>