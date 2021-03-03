<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */
$APPLICATION->SetTitle(GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_VIEWER_TITLE'));
$typeVal = false;
if(isset($arResult["UF_SERIES"])){
  $rsType = CUserFieldEnum::GetList(array(), array("ID" => $arResult["UF_SERIES"]));
  if ($arType = $rsType->GetNext())
    $typeVal = $arType["VALUE"];
}
//print_r($arResult);
$cartPreloader = true;


$currencySymbol = '';
if (SITE_ID == 's1') {
  $currencySymbol = '₽';
} elseif (SITE_ID == 's2') {
  $currencySymbol = '$';
} else {
  $currencySymbol = '€';
}

?>

<div  ng-controller="buyPageController" class="buy">
    <div class="buy_description" ng-init="sectionID=<?=$arResult["ID"]?>; site='<?=SITE_ID?>'; initDicomviewerPage()">
      <div class="buy_description-logo">
        <div class="buy_description-logo-lite">
          <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/INDV-LITE-RGB.svg" alt="<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?> Lite">
          <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?></p>
          <p>LITE</p>
        </div>
        <div class="buy_description-logo-pro">
          <img src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/INDV-PRO-RGB.svg" alt="<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?> Pro">
          <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_EDITION');?></p>
          <p>PRO</p>
        </div>
      </div>
      <div class="buy_description-text">
        <?
          if (SITE_ID == 's1') {
            echo "<p>".$arResult["~UF_DETAIL_DESCRIP"]."</p>";
          } elseif (SITE_ID == 's2') {
            echo "<p>".$arResult["~UF_SEC_DET_DESCRIP"]."</p>";
          } elseif (SITE_ID == 's3') {
            echo "<p>".$arResult["~UF_SEC_DET_DESCRIP_F"]."</p>";
          } elseif (SITE_ID == 's4') {
            echo "<p>".$arResult["~UF_SEC_DET_DESCRIP_D"]."</p>";
          } elseif (SITE_ID == 's5') {
            echo "<p>".$arResult["~UF_DET_DESCRIP_ES"]."</p>";
          }
          ?>
		
      </div>
    </div>
  
    <div class="buy_sub-text" ng-cloak="">
      <div class="buy_sub-text-row">
        <a href="" ng-click="changeViewBlock('buy')" ng-class="{'active': showBuyBlock}" ><?=GetMessage('CT_BCS_CATALOG_SECTION_BUY_LIC');?></a>
        <a href="" ng-click="changeViewBlock('lic')" ng-class="{'active': !showBuyBlock}"><?=GetMessage('CT_BCS_CATALOG_SECTION_RENEW_LIC');?></a>
      </div>
      <div class="buy_sub-text-row" ng-show="showBuyBlock" >
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_RENEW_LIC_TEXT');?></p>
      </div>
    <?if (!$USER->IsAuthorized()):?>      
      <div class="buy_sub-text-row" ng-show="!showBuyBlock" >
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_NEED_AUTH');?></p>
      </div>
      <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>login/?returnUrl=<?=SITE_DIR?>buy/dicomviewer/')" ng-show="!showBuyBlock"><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_AUTH_BUTTON');?></button>
    <?else:?>
      <div class="buy_sub-text-row" ng-show="!showBuyBlock && checkIfLicenseForUpdate()">
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CHOOSE_LIC_TO_EXT');?></p>
      </div>
      <div class="buy_sub-text-row" ng-show="!showBuyBlock && !checkIfLicenseForUpdate()" ng-cloak=""><p><?=GetMessage('CT_BCS_CATALOG_SECTION_NOWLICENSEFORUPDATE');?></p></div>
    <?endif;?>  
    </div>
  
  <?foreach($arResult["ITEMS"] as $item){
    if($item["PROPERTIES"]["LIC_TYPE"]["VALUE"] == "pro"){
      include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/goodProTemplate_new.php");
    }elseif($item["PROPERTIES"]["LIC_TYPE"]["VALUE"] == "lite"){
      include($_SERVER["DOCUMENT_ROOT"]."/".$this->GetFolder()."/goodLiteTemplate_new.php");
    }
  }?>


  <?if ($USER->IsAuthorized()):?>   
  <div class="buy_product license-list" ng-class="{'license-list-active' : extItem.inbasket}" ng-show="!showBuyBlock" ng-repeat="(key, extItem) in extRedactions.lite | filter: itemAddupdateFilter" ng-cloak="">
      <div class="buy_product-row" >
        <div class="buy_product-row-check-container">
          <!--input type="checkbox" ng-checked="extItem.updateLic && extItem.updateLic.inbasket" id="pro3" class="checkbox">
          <label for="pro3" class="checkbox-label"></label-->
          <img ng-src="{{extItem.img}}" alt="">
        </div>
        <div class="buy_product-row-wrapper">
          <div class="buy_product-row-wrapper-key">
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_KEY');?></p>
            <p>{{extItem.licKey}}</p>
          </div>
          <div class="buy_product-row-wrapper-data">
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_END_DATE');?></p>
            <p ng-class="{'data-red' : !extItem.actualLic, 'data-green' : extItem.actualLic}">{{extItem.endData}}</p>
          </div>
        </div>
      </div>

      <div class="buy_product-row show-title">
        <div class="show-title-row selected-options"  ng-class="{'show-title-row-active' :  extItem.updateLic && extItem.updateLic.inbasket}">
          <p class="show-title-row-subtitle"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADDITTIONAL_SUBSCRIBTION")?>: +<span>{{extItem.updateLic && extItem.updateLic.years ? extItem.updateLic.years : 0}}</span> {{ (extItem.updateLic && extItem.updateLic.years == 1) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}}   </p>
          <span class="price"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{extItem.updateLic && extItem.updateLic.years ? parsePrice(calculateLicLitePrice_renew(extItem,(extItem.updateLic.years))*(extItem.updateLic.years)) : 0}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span>
        </div>
        <div class="show-title-row show-title-row-active">
          <p class="buy_product-row-show-title"><?=  GetMessage("CT_BCS_CATALOG_SECTION_ADD_SUBSCRIPTION")?></p>
          <img data-open="0" src="/verstka_new/buy_p/img/blueArrow.svg" alt="">
        </div>
      </div>
      <div class="buy_product-show-case  radio-case open-case-active" >
        <div class="buy_product-show-case-sub-title"ng-hide="extItem.haveUpdates">
          <div>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></p>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></p>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></p>
          </div>
        </div>
        <div class="buy_product-show-case-row" ng-repeat="i in getNumber(extItem.maxYears) track by $index" ng-hide="extItem.haveUpdates">
          <div class="buy_product-show-case-row-price">
            <p>+{{$index+1}} {{$index == 0 ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}}<span class="share">-{{70+5*$index}}%</span></p>
            <p class="disable-mobile">{{30-5*$index}}% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <span ng-show="extItem.currency == 'USD'"><?=$currencySymbol?> </span>{{parsePriceCustom(extItem, extItem.currency)}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
            <p><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicLitePrice_renew(extItem,($index+1))*($index+1))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?><span class="sub-text" ng-show="$index > 0"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicLitePrice_renew(extItem,($index+1)),false,true)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?> <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="check-container" ng-click="changeLicenseUpdate($index+1, extItem)">
              <input type="checkbox" ng-checked="checkIfInBaketLicenseUpdate($index+1, extItem)" id="sub-option-1.{{$index}}">
              <label for="sub-option-1.{{$index}}"></label>
            </div>
          </div>
        </div> 
        <p class="sub-text" ng-show="extItem.haveUpdates">
          <?=GetMessage('CT_BCS_CATALOG_SECTION_LIC_HAVE_UPDATES_ALREADY');?>
        </p>
      </div>
    </div>

    <div class="buy_product license-list" ng-class="{'license-list-active' : extItem.inbasket}" ng-show="!showBuyBlock"  ng-repeat="(key, extItem) in extRedactions.pro | filter: itemAddupdateFilter" ng-cloak="">
      <div class="buy_product-row">
        <div class="buy_product-row-check-container">
          <!--input type="checkbox"  ng-checked="extItem.updateLic && extItem.updateLic.inbasket" id="pro4" class="checkbox">
          <label for="pro4" class="checkbox-label"></label-->
          <img ng-src="{{extItem.img}}" alt="">
        </div>
        <div class="buy_product-row-wrapper">
          <div class="buy_product-row-wrapper-key">
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_KEY');?></p>
            <p>{{extItem.licKey}}</p>
          </div>
          <div class="buy_product-row-wrapper-data">
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_END_DATE');?></p>
            <p ng-class="{'data-red' : !extItem.actualLic, 'data-green' : extItem.actualLic}">{{extItem.endData}}</p>
          </div>
        </div>
      </div>
      <div class="buy_product-row show-title ">
        <div class="show-title-row selected-options"  ng-class="{'show-title-row-active' :  extItem.updateLic && extItem.updateLic.inbasket}" >
          <p class="buy_product-row-show-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_LIC_CONTENT')?></p>
          <span class="price"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{extItem.updateLic && extItem.updateLic.years ? parsePrice(calculateLicProPrice_renew(extItem,extItem.updateLic.years)) : 0}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span>
        </div>
        <div class="show-title-row show-title-row-active">
          <p class="show-title-row-subtitle"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_ADDITIONAL_MODULES');?>, <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SELECTED');?>:  <span>{{countBoughtModules(extItem)}}</span></p>
          <img data-open="0" src="<?php echo SITE_TEMPLATE_PATH ?>/img/svg/buy_p/blueArrow.svg" alt="">
        </div>
      </div>
      
      <div class="buy_product-show-case  radio-case open-case-active" >
        <div class="buy_product-show-case-row">
          <div class="buy_product-show-case-row-price mobile-column">
            <div>
              <div class="check-case_check-title">
                
                <p class="text-blue active">{{extItem.shortName}}</p>
                <p class="check-case_check-title-price"><span ng-hide="extItem.currency == 'RUB'"><?=$currencySymbol?> </span>{{extItem.currency == 'RUB' ? extItem.price : extItem.price_en}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
              </div>
              <p class="text-blue">{{extItem.shortName}}</p>
              <p class="text-black" ng-bind-html="extItem.detailText"></p>
            </div>
            <p class="mobile-text-right"><span ng-hide="extItem.currency == 'RUB'">$ </span>{{extItem.currency == 'RUB' ? extItem.price : extItem.price_en}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container">
              
            </div>
          </div>
        </div>
        <div class="buy_product-show-case-row" ng-repeat="(keyMod, module) in extItem.modules">
          <div class="buy_product-show-case-row-price mobile-column">
            <div>
              <div class="check-case_check-title">
                
                <p class="text-blue active">{{module.name}}</p>
                <p class="check-case_check-title-price"><span ng-hide="extItem.currency == 'RUB'">$ </span>{{extItem.currency == 'RUB' ? module.price : module.price_en}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
              </div>
              <p class="text-blue">{{module.name}}</p>
              <p class="text-black" ng-bind-html="module.detailText"></p>
            </div>
            <p class="mobile-text-right"><span ng-hide="extItem.currency == 'RUB'"><?=$currencySymbol?> </span>{{extItem.currency == 'RUB' ? module.price : module.price_en}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container">
              
            </div>
          </div>
        </div>
        <div class="buy_product-show-case-row">
          <div class="buy_product-show-case-row-price mobile-column">
            <div>
              <div class="check-case_check-title">
                
                <p class="text-blue active"><?=GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TOTAL")?></p>
                <p class="check-case_check-title-price"><span ng-hide="extItem.currency == 'RUB'"><?=$currencySymbol?> </span>{{parsePrice(calculateLicProPrice(extItem,0), extItem.currency)}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
              </div>
              <p class="text-blue"><?=GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TOTAL")?></p>
              <p class="text-black"></p>
            </div>
            <p class="mobile-text-right"><span ng-hide="extItem.currency == 'RUB'">$ </span>{{parsePrice(calculateLicProPrice(extItem,0), extItem.currency)}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container">
              
            </div>
          </div>
        </div>

      </div>
      
      <div class="buy_product-row show-title">
        <div class="show-title-row selected-options " ng-class="{'show-title-row-active' :  extItem.updateLic && extItem.updateLic.inbasket}">
          <p class="show-title-row-subtitle"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADDITTIONAL_SUBSCRIBTION")?>: +<span>{{extItem.updateLic && extItem.updateLic.years ? extItem.updateLic.years : 0}}</span> {{ (extItem.updateLic && extItem.updateLic.years == 1) ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}} <span> <?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{extItem.updateLic && extItem.updateLic.years ? parsePrice(calculateLicProPrice_renew(extItem,extItem.updateLic.years)) : 0}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span></p>
          <span class="price"></span>
        </div>
        <div class="show-title-row show-title-row-active">
          <p class="buy_product-row-show-title"><?= GetMessage("CT_BCS_CATALOG_SECTION_ADD_SUBSCRIPTION")?></p>
          <img data-open="1" src="/verstka_new/buy_p/img/blueArrow.svg" alt="">
        </div>
      </div>
      
      
      
      
      
      <div class="buy_product-show-case  radio-case open-case-active">
        <div class="buy_product-show-case-sub-title" ng-hide="extItem.haveUpdates">
          <div>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></p>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></p>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></p>
          </div>
        </div>
        <div class="buy_product-show-case-row" ng-hide="extItem.haveUpdates"  ng-repeat="i in getNumber(extItem.maxYears) track by $index">
          <div class="buy_product-show-case-row-price">
            <p>+{{$index+1}} {{$index == 0 ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}}<span class="share">-{{70+5*$index}}%</span></p>
            <p class="disable-mobile">{{30-5*$index}}% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <span ng-hide="extItem.currency == 'RUB'"><?=$currencySymbol?> </span>{{parsePrice(calculateLicProPrice(extItem,0),extItem.currency)}}<span ng-show="extItem.currency == 'RUB'"> <?=$currencySymbol?></span></p>
            <p><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicProPrice_renew(extItem,($index+1))*($index+1))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?> <span class="sub-text" ng-show="$index > 0"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicProPrice_renew(extItem,($index+1)),false,true)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?> <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="check-container" ng-click="changeLicenseUpdate($index+1, extItem)">
              <input type="checkbox" ng-checked="checkIfInBaketLicenseUpdate($index+1, extItem)" id="sub-option-1.{{$index}}">
              <label for="sub-option-1.{{$index}}"></label>
            </div>
          </div>
        </div>
        <p class="sub-text" ng-show="extItem.haveUpdates">
          <?=GetMessage('CT_BCS_CATALOG_SECTION_LIC_HAVE_UPDATES_ALREADY');?>
        </p>
      </div>
    </div>
	<div class="buy_current-price flex-end" ng-cloak="" ng-show="!showBuyBlock" >

      <div class="buy_current-price-column" >
        <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>cart/')"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_GO_TO_BASKET');?></button>
      </div>
    </div>
  <?endif;?>  
  
  </div>

<?
/*
<script>
function gtag_report_addToBasket() {
  gtag('event', 'conversion', {
	  'send_to': 'AW-852382855/-dCLCLLn7LEBEIepuZYD',
	  'value': 50.0,
	  'currency': 'RUB',
	  'transaction_id': ''
  });
  return;
}
</script>
*/
?>