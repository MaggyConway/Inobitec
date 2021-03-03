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
$APPLICATION->SetTitle("Просмотрщик");
$typeVal = false;
if(isset($arResult["UF_SERIES"])){
  $rsType = CUserFieldEnum::GetList(array(), array("ID" => $arResult["UF_SERIES"]));
  if ($arType = $rsType->GetNext())
    $typeVal = $arType["VALUE"];
}
//print_r($arResult);
$cartPreloader = true;
?>
<div ng-controller="buyPageController" ng-cloak="">
<div class="buy_p" >
    <div class="buy_p-row" ng-init="sectionID=<?=$arResult["ID"]?>; site='<?=SITE_ID?>'; initDicomviewerPage()">
        <div class="buy_p-logo">
            <div class="buy_s-logo-lite">
                <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/INDV-LITE-RGB.svg">
                <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?></p>
                <p>Lite</p>
            </div>
            <div class="buy_s-logo-pro">
                <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/INDV-PRO-RGB.svg">
                <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?></p>
                <p>Pro</p>
            </div>
        </div>
        <div class="buy_p-text">
            <?=(SITE_ID == "s2" || SITE_ID == "s3") ? $arResult["~UF_SEC_DET_DESCRIP"] : $arResult["~UF_DETAIL_DESCRIP"]?>
        </div>
    </div>
  
  <?foreach($arResult["ITEMS"] as $item){
    if($item["PROPERTIES"]["LIC_TYPE"]["VALUE"] == "pro"){
      include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/goodProTemplate.php");
    }elseif($item["PROPERTIES"]["LIC_TYPE"]["VALUE"] == "lite"){
      include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/goodLiteTemplate.php");
    }
  }?>
	

	
    
	

	<div class="page_buy_p_updates" ng-hide="calculateDicomViewerPrice() == 0">
		<div class="page_buy_p_updates_title">
			<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIOANL_SUBSCRIBE');?>
		</div>
		<div class="page_buy_p_updates_subtitle">
			<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED_MORE');?>
		</div>
		<div class="page_buy_p_updates_elements">
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></span></div>
					<div class="page_buy_p_total_element_percent"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></span></div>
					<div class="page_buy_p_total_element_price"><span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></span></div>
					<div class="page_buy_p_total_element_button">
					</div>
				</div>
			</div>
            <div class="page_buy_p_total_element in" ng-class="ifViewerLicSolid(1)">
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+1 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?> <i>-70%</i></div>
					<div class="page_buy_p_total_element_percent">30% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{parsePrice(fullViewerPrice)}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifViewerLicSolid(1)}">{{parsePrice(yearLicPrice["1"])}} &#8381;</div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addViewerLic(1)" ng-class="{'solid' : ifViewerLicSolid(1), 'disabled' : !ifViewerLicActive(1)}">{{ifViewerLicSolid(1) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
						<a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delViewerLic(1)" ng-show="ifViewerLicSolid(1)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in" ng-class="ifViewerLicSolid(2)">
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+2 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?> <i>-75%</i></div>
					<div class="page_buy_p_total_element_percent">25% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{parsePrice(fullViewerPrice)}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifViewerLicSolid(2)}">{{parsePrice(yearLicPrice["2"]*2)}} &#8381; <span>{{parsePrice(yearLicPrice["2"])}} &#8381; <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addViewerLic(2)" ng-class="{'solid' : ifViewerLicSolid(2), 'disabled' : !ifViewerLicActive(2)}">{{ifViewerLicSolid(2) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
                        <a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delViewerLic(2)" ng-show="ifViewerLicSolid(2)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in"  ng-class="ifViewerLicSolid(3)">
				<div class="page_buy_p_total_element_content" ng-cloak="">
					<div class="page_buy_p_total_element_time">+3 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?> <i>-80%</i></div>
					<div class="page_buy_p_total_element_percent">20% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> {{parsePrice(fullViewerPrice)}} &#8381;</div>
					<div class="page_buy_p_total_element_price" ng-class="{'price-font-bold': ifViewerLicSolid(3)}">{{parsePrice(yearLicPrice["3"]*3)}} &#8381; <span>{{parsePrice(yearLicPrice["3"])}} &#8381; <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n" ng-click="addViewerLic(3)" ng-class="{'solid' : ifViewerLicSolid(3), 'disabled' : !ifViewerLicActive(3)}">{{ifViewerLicSolid(3) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</div>
                        <a href="#" onclick="return false;" class="page_buy_delete_basket" ng-click="delViewerLic(3)" ng-show="ifViewerLicSolid(3)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_summ" ><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TOTAL');?> <span class="buy_p_mobile-new-line">{{parsePrice(fullPrice)}} &#8381;</span>  </div>
	<a href="<?=SITE_DIR?>cart/" class="page_buy_button to_basket btn_n"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_GO_TO_BASKET');?></a>
</div>
</div>