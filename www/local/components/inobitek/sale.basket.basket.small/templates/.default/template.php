<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
$file = "/var/www/voxwebru/data/www/inobitec.voxweb.ru/local/components/inobitek/sale.basket.basket.small/templates/.default/lang/ru/template.php";
print_r(__FILE__);
Loc::loadMessages($file);
?>
<li class="menu_basket top_menu_button 12" ng-controller="basketHeaderController" ng-cloak="" ng-init="site=<?=SITE_ID?>; initCart()">
    <a href="<?=(SITE_ID == 's2')?"/eng":""?>/cart/" class="js-basket_top_button"><img src="/img/svg/basket.svg" width="30" height="27" /><span ng-cloak="">{{calculateProductsNum()}}</span></a>

    <div class="sub_basket">
        <div class="sub_basket_title">
            <?=GetMessage('INOBITEK.SALE.BASKET.BASKET.BASKET');?>
            <span ng-cloak="">{{calculateProductsNum()}} <?=GetMessage('INOBITEK.SALE.BASKET.BASKET.ITEMS');?></span>
        </div>
        <div class="sub_basket_product" ng-show="checkIfViewerInBasket()">
            <a href="<?=(SITE_ID == 's2')?"/eng":""?>/buy/dicomviewer/" class="sub_basket_product_title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.VIEWER');?></a>
            <div class="sub_basket_product_element" ng-repeat="liteItem in liteRedactions | filter: itemFilter">
                <div class="sub_basket_product_element_name">{{liteItem.shortName}}</div>
                <div class="sub_basket_product_element_price">{{liteItem.price}} &#8381;</div>
                <div class="sub_basket_product_element_del" ng-click="delLiteFromBasket($event, liteItem)"></div>
            </div>
            <div ng-repeat="proItem in proRedactions | filter: itemFilter">
              <div class="sub_basket_product_element">
                  <div class="sub_basket_product_element_name">{{proItem.shortName}}</div>
                  <div class="sub_basket_product_element_price">{{proItem.price}} &#8381;</div>
                  <div class="sub_basket_product_element_del" ng-click="delProFromBasket($event, proItem)"></div>
              </div>
              <div class="sub_basket_product_element" ng-repeat="module in proItem.modules | filter: itemFilter">
                  <div class="sub_basket_product_element_name">{{module.name}} </div>
                  <div class="sub_basket_product_element_price">{{module.price}} &#8381;</div>
                  <div class="sub_basket_product_element_del" ng-click="delModuleFromBasket($event, module)"></div>
              </div>
            </div>
            <div class="sub_basket_product_element" ng-show="license.viewerLic && license.viewerLic.inbasket">
                <div class="sub_basket_product_element_name">{{license.viewerLic.name}}</div>
                <div class="sub_basket_product_element_price">{{license.viewerLic.price}} &#8381;</div>
                <div class="sub_basket_product_element_del" ng-click="delLiteFromBasket($event, license.viewerLic)"></div>
            </div>
        </div>
        <div class="sub_basket_product" ng-show="serverRedaction">
            <a href="<?=(SITE_ID == 's2')?"/eng":""?>/buy/dicomserver/" class="sub_basket_product_title"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.SERVER');?></a>
            <div class="sub_basket_product_element">
                <div class="sub_basket_product_element_name"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.CONNECTIONS');?> <b>{{serverRedaction.serverConnections}}</b></div>
                <div class="sub_basket_product_element_price">{{serverRedaction.offers[serverRedaction.serverConnections].price}} РУБ</div>
                <div class="sub_basket_product_element_del" ng-click="delServerFromBasket($event)"></div>
            </div>
            <div class="sub_basket_product_element" ng-show="license.serverLic">
                <div class="sub_basket_product_element_name">{{license.serverLic.name}}</div>
                <div class="sub_basket_product_element_price">{{license.serverLic.price}} &#8381;</div>
                <div class="sub_basket_product_element_del" ng-click="delLiteFromBasket($event, license.serverLic)"></div>
            </div>
        </div>
        <div class="sub_basket_summ">итого {{calculateFullPrice()}} &#8381;</div>
        <a href="<?=(SITE_ID == 's2')?"/eng":""?>/cart/" ng-show="calculateProductsNum() > 0" class="btn_n blue"><?=GetMessage('INOBITEK.SALE.BASKET.BASKET.GOTOBASKET');?></a>
        <div class="clear"></div>
    </div>
</li>