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


$currencySymbol = '';
if (SITE_ID == 's1') {
  $currencySymbol = '₽';
} elseif (SITE_ID == 's2') {
  $currencySymbol = '$';
} else {
  $currencySymbol = '€';
}


?>

<script>
    $(document).ready(function(){
        $('.buy-key-input-main').mask('0000-0000-0000-0000');
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

<div class="popup" id="another_user_compcode">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_LIC_ANOTHER_USER_COMPCODE');?></p>
    </div>
</div>

<div class="popup" id="already_have_updates">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="sub-text"><?=GetMessage('CT_BCS_CATALOG_SECTION_LIC_HAVE_UPDATES_ALREADY');?></p>
    </div>
</div>

<div class="js-buyPage" ng-controller="buyServerController">
   <div class="buy buy-s" ng-init="sectionID=<?=$arResult["ID"]?>; site='<?=SITE_ID?>'; initDicomserverPage()">
    <div class="buy_description">
      <div class="buy_description-logo buy-s_logo-desc">
        <div class="buy_description-logo-lite">
          <img src="<?= $arResult["PICTURE"]["SRC"]?>" alt="">
          <!-- <p><?//=(SITE_ID == "s2") ? $arResult["UF_SHORT_NAME_EN"] : $arResult["UF_SHORT_NAME"]?></p> -->

          <?
            if (SITE_ID == 's1') {
              echo "<p>".$arResult["UF_SHORT_NAME"]."</p>";
            } elseif (SITE_ID == 's2') {
              echo "<p>".$arResult["UF_SHORT_NAME_EN"]."</p>";
            } elseif (SITE_ID == 's3') {
              echo "<p>".$arResult["UF_SHORT_NAME_EN"]."</p>";
            } elseif (SITE_ID == 's4') {
              echo "<p>".$arResult["UF_SHORT_NAME_EN"]."</p>";
            } elseif (SITE_ID == 's5') {
              echo "<p>".$arResult["UF_SHORT_NAME_ES"]."</p>";
            }


            // echo '<pre>'; var_dump($arResult['UF_SHORT_NAME_ES']); echo '</pre>';
          ?>

        </div>
      </div>
      
      <div class="buy_description-logo buy-s_logo-mobile">
        <?if(SITE_ID == "s2"){?>
          <?foreach($arResult["UF_LOGOS_EN"] as $photoID):?>
            <img ng-src="<?=CFile::GetPath($photoID)?>" >
          <?endforeach;?>
          
        <?}else{?>
          <?foreach($arResult["UF_LOGOS"] as $photoID):?>
            <img ng-src="<?=CFile::GetPath($photoID)?>" >
          <?endforeach;?>
          
        <?}?>
        
      </div>
      <div class="buy_description-text">
       <?//=(SITE_ID == "s2") ? $arResult["~UF_SEC_DET_DESCRIP"] : $arResult["~UF_DETAIL_DESCRIP"]?>

       <?
       // echo "<pre>"; var_dump($arResult); echo "</pre>";

          if (SITE_ID == 's1') {
            echo "<p>".$arResult["~UF_DETAIL_DESCRIP"]."</p>";
          } elseif (SITE_ID == 's2') {
            echo "<p>".$arResult["~UF_SEC_DET_DESCRIP"]."</p>";
          } elseif (SITE_ID == 's3') {
            echo "<p>".$arResult["~UF_SEC_DET_DESCRIP_F"]."</p>";
          } elseif (SITE_ID == 's4') {
            echo "<p>".$arResult["~UF_DESC_DE"]."</p>";
          }
        ?>

      </div>
    </div>
    <div class="buy_sub-text">
      <div class="buy_sub-text-row">
        <a href="" ng-click="changeViewBlock('buy')" ng-class="{'active': showBuyBlock}"><?=GetMessage('CT_BCS_CATALOG_SECTION_BUY_LIC');?></a>
        <a href="" ng-click="changeViewBlock('lic')" ng-class="{'active': !showBuyBlock}"><?=GetMessage('CT_BCS_CATALOG_SECTION_RENEW_LIC');?></a>
      </div>
      <?if (!$USER->IsAuthorized()):?>      
        <div class="buy_sub-text-row" ng-show="!showBuyBlock" >
          <p><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_NEED_AUTH');?></p>
        </div>
        <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>login/?returnUrl=<?=SITE_DIR?>buy/dicomserver/')" ng-show="!showBuyBlock"><?=GetMessage('CT_BCS_CATALOG_SECTION_SUBSCIBE_AUTH_BUTTON');?></button>
      <?else:?>
        <div class="buy_sub-text-row" ng-show="!showBuyBlock && checkIfLicenseForUpdate()">
          <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CHOOSE_LIC_TO_EXT');?></p>
        </div>
        <div class="buy_sub-text-row" ng-show="!showBuyBlock && !checkIfLicenseForUpdate()" ng-cloak=""><p><?=GetMessage('CT_BCS_CATALOG_SECTION_NOWLICENSEFORUPDATE');?></p></div>
      <?endif;?>  
    </div>
    <div class="buy_row" ng-show="showBuyBlock" ng-cloak="">
       <?foreach($arResult["UF_LOGOS"] as $photoID):?>
          <div class="buy_s-logo" ng-cloak="">
              <img ng-src="{{serverRedaction.img}}" >
          </div>
        <?endforeach;?>
    </div>
    <div class="buy_connects"  ng-show="showBuyBlock" ng-cloak="">
      <div class="buy_connects-slider">
        <p class="buy_connects-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_AE_CONNECTIONS');?></p>
        <div class="range-slider-container">
          <div class="slider"></div>
        </div>
        <p><?=  GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_MORE50_INFO')?></p>
      </div>
      <div class="buy_connects-container">
        <div class="buy_connects-price">
          <p class="buy_connects-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_COST');?></p>
          <div class="buy_connects-price-container">
            <p class="buy_connects-price-main"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{serverRedaction.offers[serverConnections].price}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></p>
            <p class="buy_connects-price-sub"><?=  GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED_SERVER')?></p>
          </div>
        </div>
        <div class="buy_connects-key">
          <p class="buy_connects-title"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_TITLE_PRODUCT_KEY');?></p>
          <span class="buy-key-input " ng-class="{'buy-key-input-active': tryAddServer && !serverProductKey && !serverRedaction.inbasket}">
            <input type="text" ng-disabled="serverRedaction.inbasket || (redactionForNewConnections && redactionForNewConnections.inbasket)" ng-model="serverProductKey" name="serverProductKey" required="" minlength="19" class="buy-key-input-main" placeholder="6450-6065-0800-0000">
            <span class="buy-key-input-info" onclick="Popup('page_buy_p-info')">?</span>
            <span><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_SERVER_KEY_ERROR');?></span>
          </span>
          <div class="button-container" ng-class="{'button-container-active': serverRedaction.inbasket || (redactionForNewConnections && redactionForNewConnections.inbasket)}">
            <button ng-click="addServerToBasket()" class="btn-blue">{{serverRedaction.inbasket ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_IN_CART');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_ADD_IN_CART');?>'}}</button>
            <span><a href="" ng-click="delServerFromBasket(serverRedaction)"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_DEL_FROM_CART');?></a></span>
          </div>
        </div>
      </div>
    </div>
     
     <div class="buy_sub-text current_connects" ng-hide="!redactionForNewConnections || !showBuyBlock || redactionForNewConnections.haveUpdates" ng-cloak="">
      <p><?=GetMessage('CT_BCS_CATALOG_SECTION_PRIVIOUS_CONNECTIONS');?> <span>{{redactionForNewConnections.numConnection}}</span>.</p> 
      <p><?=GetMessage('CT_BCS_CATALOG_SECTION_TOTAL_SERVER_CONNECTIONS');?> {{ parseInt(redactionForNewConnections.numConnection) + parseInt(serverConnections)}}.</p>
    </div>

    <div class="buy_product"  ng-show="showBuyBlock && serverRedaction.inbasket" ng-cloak="">
      <div class="buy_product-row show-title">
        <div class="show-title-row selected-options" ng-show="serverRedaction.license.inbasket">
          <p class="show-title-row-subtitle"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADDITTIONAL_SUBSCRIBTION")?>: +<span>{{serverRedaction.license.years}}</span> {{serverRedaction.license.years == 1 ? '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?>' : '<?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?>'}}</p>
          <span class="price buy-s_price 1"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicServerPrice(serverRedaction.offers[serverConnections].price, serverRedaction.license.years)*serverRedaction.license.years)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span>
        </div>
        <div class="show-title-row">
          <p class="buy_product-row-show-title"><?=GetMessage("CT_BCS_CATALOG_SECTION_ADD_SUBSCRIPTION")?></p>
          <img data-open="0" src="/verstka_new/buy_p/img/blueArrow.svg" alt="">
        </div>
      </div>
      <div class="buy_product-show-case open-case radio-case">
        <p class="buy_product-show-case-title"><?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_INCLUDED_MORE")?></p>
        <div class="buy_product-show-case-sub-title">
          <div>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_1YEAR_FREE_SUBSCRIBE');?></p>
            <p><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PERCENT_SUBSCRIBE_PRICE');?></p>
            <p class="buy-s_price"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_SUBSCRIBE_PRICE');?></p>
          </div>
        </div>
        <div class="buy_product-show-case-row">
          <div class="buy_product-show-case-row-price">
            <p>+1 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEAR');?><span class="share">-70%</span></p>
            <p class="disable-mobile">30% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?><?if(SITE_ID != "s1"):?><span> <?=$currencySymbol?></span> <?endif;?> {{serverRedaction.offers[serverConnections].price}} <?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
            <p class="buy-s_price"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?>{{parsePrice(calculateLicServerPrice(serverRedaction.offers[serverConnections].price, 1))}} <?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container" >
              <div class="check-container">
                <input type="checkbox"  ng-checked="ifServerLicSolid(1,serverRedaction)" ng-click="changeServerLic(1,serverRedaction)" id="sub-option-2.1">
                <label for="sub-option-2.1"></label>
              </div>
            </div>
          </div>
        </div>
        <div class="buy_product-show-case-row">
          <div class="buy_product-show-case-row-price">
            <p>+2 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?><span class="share">-75%</span></p>
            <p class="disable-mobile">25% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> <?if(SITE_ID != "s1"):?><span> <?=$currencySymbol?></span> <?endif;?> {{serverRedaction.offers[serverConnections].price}} <?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?></p>
            <p class="buy-s_price"><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?> {{parsePrice(calculateLicServerPrice(serverRedaction.offers[serverConnections].price, 2)*2)}} <?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?><span class="sub-text">{{parsePrice(yearLicPrice["2"])}} <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container" >
              <div class="check-container">
                <input type="checkbox"  ng-checked="ifServerLicSolid(2,serverRedaction)" ng-click="changeServerLic(2,serverRedaction)" id="sub-option-2.2">
                <label for="sub-option-2.2"></label>
              </div>
            </div>
          </div>
        </div>
        <?/*?>
        <div class="buy_product-show-case-row">
          <div class="buy_product-show-case-row-price">
            <p>+3 <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_YEARS');?><span class="share">-80%</span></p>
            <p class="disable-mobile">20% <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM');?> <?if(SITE_ID == "s2"):?><span>$</span> <?endif;?> {{serverRedaction.offers[serverConnections].price}} <?if(SITE_ID == "s1"):?> <span>₽</span><?endif;?></p>
            <p class="buy-s_price"><?if(SITE_ID == "s2"):?><span>$</span> <?endif;?>{{parsePrice(calculateLicServerPrice(serverRedaction.offers[serverConnections].price, 3)*3)}} <?if(SITE_ID == "s1"):?> <span>₽</span><?endif;?><span class="sub-text">{{parsePrice(yearLicPrice["3"])}} <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
          </div>
          <div class="buy_product-row-button-container">
            <div class="button-container" >
              <div class="check-container">
                <input type="checkbox"  ng-checked="ifServerLicSolid(3,serverRedaction)" ng-click="changeServerLic(3,serverRedaction)" id="sub-option-2.3">
                <label for="sub-option-2.3"></label>
              </div>
            </div>
          </div>
        </div>
        <?*/?>
      </div>
    </div>
    
     
      
    <div class="buy_current-price"  ng-show="showBuyBlock" ng-cloak="">
      <div class="buy_current-price-column">
        <p><?=GetMessage('CT_BCS_CATALOG_SECTION_RENEW_TOTAL')?> <?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calcServerFullPrices())}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></p>
        <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>cart/')"><?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_BUTTON_GO_TO_BASKET');?></button>
      </div>
    </div>
    <?if ($USER->IsAuthorized()):?>
    
     
    <div class="buy_product license-list" ng-class="{'license-list-active' : extItem.inbasket}" ng-show="!showBuyBlock" ng-repeat="(key, extItem) in extRedactions.server | filter: itemAddupdateFilter" ng-cloak="">
      <div class="buy_product-row">
        <div class="buy_product-row-check-container">
          <!--input type="checkbox" id="pro8" class="checkbox">
          <label for="pro8" class="checkbox-label"></label-->
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
          <span class="price"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{extItem.updateLic && extItem.updateLic.years ? parsePrice(calculateLicServerPrice_renew(extItem,parseInt(extItem.updateLic.years))*parseInt(extItem.updateLic.years))  : 0}} <?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?></span>
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
            <p class="disable-mobile">{{30-5*$index}}% <?= GetMessage("CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_FROM")?> <span ng-show="extItem.currency == 'USD'"><?=$currencySymbol?> </span>{{parsePrice(calcBuyServerPrice(extItem),extItem.currency)}}<span ng-hide="extItem.currency == 'USD'"> <?=$currencySymbol?></span></p>
            <p><?if(SITE_ID != "s1"):?><span><?=$currencySymbol?></span> <?endif;?> {{parsePrice(calculateLicServerPrice_renew(extItem,($index+1))*($index+1))}}<?if(SITE_ID == "s1"):?> <span><?=$currencySymbol?></span><?endif;?><span class="sub-text" ng-show="$index > 0"><?if(SITE_ID != "s1"):?><?=$currencySymbol?> <?endif;?>{{parsePrice(calculateLicServerPrice_renew(extItem,($index+1)),false,true)}}<?if(SITE_ID == "s1"):?> <?=$currencySymbol?><?endif;?> <?=GetMessage('CT_BCS_CATALOG_SECTION_CUSTOM_TEXT_PER_YEAR');?></span></p>
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
</div> 
  
  
