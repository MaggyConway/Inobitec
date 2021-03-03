<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
	if (strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"],"ru") === false && strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"],"RU") === false && strpos($_SERVER['REQUEST_URI'],"eng") === false && $_SESSION["REDIRECT_ENG"] != "Y"){
		$_SESSION["REDIRECT_ENG"] = "Y";
		header('Location: /eng'.$_SERVER['REQUEST_URI']);
	}
}

use \Bitrix\Main\Application;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Localization\Loc;

$pages = $APPLICATION -> GetCurDir();		  
$pages = explode('/', $pages); // МАССИВ НАЗВАНИЙ ТЕКУЩИХ РАЗДЕЛОВ

if(defined("ERROR_404") && ERROR_404)
{
	$error_404 = true;
}

$is_main = ($APPLICATION -> GetCurDir() == '/') || ($APPLICATION -> GetCurDir() == SITE_DIR) || $error_404; // ЭТО ГЛАВНАЯ СТРАНИЦА
$no_h1   = ($pages[1] == 'news' || $pages[2] == 'news');
$serverSlider = false;
$newPages = false;

if(SITE_ID == 's1'){
	$no_title_block = ($pages[1] == 'services' && !$pages[2]) || ($pages[1] == 'products' && $pages[2] && !$pages[3]) && !$error_404;	 // НЕ ВЫВОДИТЬ БЛОК ЗАГОЛОВКА (УСЛУГИ, ПРОДУКТЫ)
	$no_f_links 	= $is_main || $pages[1] == 'services'; // НЕ ВЫВОДИТЬ БЛОК ССЫЛОК (НАШИ ПРОДУКТЫ, СКАЧАТЬ)
    $serverSlider =  ($pages[1] == 'buy' && $pages[2] == 'dicomserver') ? true : false;
    $newPages = ($pages[1] == 'buy') ? true : false;
    $ruAboutViewerLic = ($pages[1] == 'about' && $pages[2] == 'viewerLic') ? true : false;
}
if(SITE_ID == 's2'){
	$no_title_block = ($pages[2] == 'services' && !$pages[3]) || ($pages[2] == 'products' && $pages[3] && !$pages[4])&& !$error_404;	 // НЕ ВЫВОДИТЬ БЛОК ЗАГОЛОВКА (УСЛУГИ, ПРОДУКТЫ)
	$no_f_links 	= $is_main || $pages[2] == 'services'; // НЕ ВЫВОДИТЬ БЛОК ССЫЛОК (НАШИ ПРОДУКТЫ, СКАЧАТЬ)
    $serverSlider =  ($pages[2] == 'buy' && $pages[3] == 'dicomserver') ? true : false ;
    $newPages = ($pages[2] == 'buy') ? true : false;
}
require($_SERVER["DOCUMENT_ROOT"].'/mobile_detect.php');
require($_SERVER["DOCUMENT_ROOT"].'/lang.php'); // Подключили языковой файл

$detect = new Mobile_Detect;

$version_files = "2";
//$cartPreloader = false;
$httpScheme = 'http';
if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
    $httpScheme = 'https';
}
?>
<!DOCTYPE html>
<html ng-app="inobitecApp">
	<head>
		<title>
		<?$APPLICATION->ShowTitle();?>
		</title>
		<?
        $APPLICATION->ShowHead();
        CUtil::InitJSCore();
        CJSCore::Init(array("fx"));
        CJSCore::Init(array('ajax'));
        
        ?>

		<meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0">
		<link rel="apple-touch-icon" sizes="57x57" href="/iconss/apple-icon-57x57.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="60x60" href="/iconss/apple-icon-60x60.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="72x72" href="/iconss/apple-icon-72x72.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="76x76" href="/iconss/apple-icon-76x76.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="114x114" href="/iconss/apple-icon-114x114.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="120x120" href="/iconss/apple-icon-120x120.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="144x144" href="/iconss/apple-icon-144x144.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="152x152" href="/iconss/apple-icon-152x152.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="180x180" href="/iconss/apple-icon-180x180.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="192x192"  href="/iconss/android-icon-192x192.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="32x32" href="/iconss/favicon-32x32.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="96x96" href="/iconss/favicon-96x96.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="16x16" href="/iconss/favicon-16x16.png?v=<?=$version_files;?>">
		<link rel="manifest" href="/manifest.json">
        <link href="/bitrix/templates/main/css/angularHide.css" type="text/css"  rel="stylesheet" />
		<meta name="msapplication-TileColor" content="#00163f">
		<meta name="msapplication-TileImage" content="/iconss/ms-icon-144x144.png?v=<?=$version_files;?>">
		<meta name="theme-color" content="#00163f">
        <?if(SITE_ID == 's1'){?>
          <script async defer src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v3.2"></script>
        <?}else{?>
          <script async defer src="https://connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v3.2"></script>
        <?}?>
		
		<? 
		   Asset::getInstance()->addString('<link rel="apple-touch-icon" href="apple-touch-icon.png">');
           Asset::getInstance()->addString('<meta http-equiv="x-ua-compatible" content="ie=edge">');

		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery-2.1.4.js');
           //Asset::getInstance()->addJs('https://code.jquery.com/jquery-2.1.4.js');
           
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/velocity.min.js');
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/velocity.ui.min.js');
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/owl.carousel.js');
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.min.js');
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.maskedinput.js');
           Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/ycp.js');
           $ua = htmlentities($_SERVER['HTTP_USER_AGENT'], ENT_QUOTES, 'UTF-8');
           if(!(preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))) {
                // do stuff for not IE
              Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/ycp.js');
            }
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/main.js');
		   Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/scripts.js');
           if($serverSlider){
              Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery-ui-1.11.1.js');
              Asset::getInstance()->addJs('/verstka_new/js/jquery-ui-slider-pips.js');
              Asset::getInstance()->addJs('/verstka_new/js/range-slider.js');
              $APPLICATION->SetAdditionalCSS("/verstka_new/css/jquery-ui-slider-pips.css");
              $APPLICATION->SetAdditionalCSS("https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css");
           }
           
           //Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/angular.min.js');
           //Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/app/controllers/buy.js');
           $APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
           $APPLICATION->AddHeadScript('/verstka_new/js/popup.js');
           
           $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/angular.js');
           $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/angular.sanitize.js');
           $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/app/app.js');
           $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/app/mask.js');
           //if($buyPage && $_GET['test'] == "newpage"){
             $APPLICATION->SetAdditionalCSS("/verstka_new/buy_p/css/style.bundle.css");
             Asset::getInstance()->addJs('/verstka_new/buy_p/js/index.js');
           //}


		  // Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/owl.carousel.css');
           //$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
		   Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.min.css');
		   Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/animate.css');
		   Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/owl.carousel.css');
           //if(!(preg_match('~MSIE|Internet Explorer~i', $ua) || (strpos($ua, 'Trident/7.0') !== false && strpos($ua, 'rv:11.0') !== false))) {
                // do stuff for not IE
              Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/ycp.css');
           // } 
           $APPLICATION->SetAdditionalCSS("/verstka_new/css/main.css");
           

		   if(!$is_main){
	   	   	Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800&subset=cyrillic" rel="stylesheet">');
		   }
 		?>
          
          

 		<? $APPLICATION->ShowProperty('metaOGTags'); ?>
 		<!--[if (gte IE 6)&(lte IE 8)]>
    		<script src="<?=SITE_TEMPLATE_PATH?>/js/respond.min.js"></script>
    	<![endif]-->
		<?if($pages[1] != "eng"){?>
			<meta property="og:image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share.png?v=<?=$version_files;?>">
            <meta itemprop="image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share.png?v=<?=$version_files;?>">
            <meta name="twitter:image:src" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share.png?v=<?=$version_files;?>">
		<?}else{?>
			<meta property="og:image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share_eng.png?v=<?=$version_files;?>">
            <meta itemprop="image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share_eng.png?v=<?=$version_files;?>">
            <meta name="twitter:image:src" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share_eng.png?v=<?=$version_files;?>">
		<?}?>
		<meta itemprop="name" content="<?$APPLICATION->ShowTitle()?>">
        <meta itemprop="description" content="<?$APPLICATION->ShowProperty('description');?>">
        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?><?=$APPLICATION->GetCurPage()?>">
        <meta name="twitter:title" content="<?$APPLICATION->ShowTitle()?>">
        <meta name="twitter:description" content="<?$APPLICATION->ShowProperty('description');?>">
        <meta property="twitter:url" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?><?=$APPLICATION->GetCurPage()?>">
        <meta name="twitter:creator" content="">
        
        <meta name="twitter:player" content="">
        <meta property="og:url" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?><?=$APPLICATION->GetCurPage()?>">
        <meta property="og:title" content="<?$APPLICATION->ShowTitle()?>">
        <meta property="og:description" content="<?$APPLICATION->ShowProperty('description');?>">
        <meta property="og:site_name" content="<?=$_SERVER['SERVER_NAME'];?>">
        <meta property="og:type" content="website">
        

	</head>
	<body <?if ($is_main){?> id="main-page"<?}else{?> class="inner"<?}?>>
		<?$APPLICATION->ShowPanel();?>
		<script>
			var site_id = '<?=SITE_ID?>';
		</script>
        <div class="new-mobile-menu">
          <div class="new-mobile-menu_toggle">
            <span></span>
            <span></span>
          </div>
          <a href="<?=SITE_DIR?>">
            <img src="/verstka_new/buy_p/img/header-logo.svg" alt="">
          </a>
          <div class="new-mobile-menu_languages">
            <?if(SITE_ID == 's1'){?>
                <p class="active">rus</p>
                <a href="/eng">eng</a>
            <?}else{?>
                <a href="/">rus</a>
                <p class="active">eng</p>
            <?}?>
          </div>
          <ul>
             <?
              $APPLICATION->IncludeComponent(
                  "bitrix:menu", 
                  "common__top__basket", 
                  array(
                      "ALLOW_MULTI_SELECT" => "N",
                      "CHILD_MENU_TYPE" => "left",
                      "DELAY" => "N",
                      "MAX_LEVEL" => "1",
                      "MENU_CACHE_GET_VARS" => array(
                      ),
                      "MENU_CACHE_TIME" => "3600",
                      "MENU_CACHE_TYPE" => "A",
                      "MENU_CACHE_USE_GROUPS" => "Y",
                      "ROOT_MENU_TYPE" => "top",
                      "USE_EXT" => "N",
                      "COMPONENT_TEMPLATE" => "common__top__basket"
                  ),
                  false
              );
              ?>
          </ul>
          <div class="new-mobile-menu_icons">
            <a href="<?=SITE_DIR?>personal/"><img src="/verstka_new/buy_p/img/user.svg" alt=""></a>
            <a href="<?=SITE_DIR?>cart/"><img src="/verstka_new/buy_p/img/basket.svg" alt=""><span  class="new-mobile-menu_icons-counter"></span></a>
            <a href="<?=SITE_DIR?>search/"><img src="/verstka_new/buy_p/img/search.svg" alt=""></a>
          </div>
          <?if(SITE_ID == 's1'):?><a href="tel:88003333089" class="new-mobile-menu_phone">8-800-3333-089</a><?else:?><a href="tel:+79204056743" class="new-mobile-menu_phone">+7-920-405-67-43</a><?endif;?> 
          <a href="mailto:market@inobitec.com" class="new-mobile-menu_mail">market@inobitec.com</a>
        </div>
        <header class="headerNew <?if(!$no_title_block && !$is_main):?>headerNew-bg<?endif;?> "  ng-controller="basketHeaderController" >
    <div class="headerNew-wrapper">
      <div class="headerNew-container">
        <a href="<?=SITE_DIR?>" title="<?=GetMessage('ON_MAIN')?>" class="headerNew-container-logo">
          <img src="/verstka_new/buy_p/img/header-logo.svg" alt="">
        </a>
        <div class="headerNew-container-languages">
          <?if(SITE_ID == 's1'){?>
              <p class="active">rus</p>
              <a href="/eng">eng</a>
          <?}else{?>
              <a href="/">rus</a>
              <p class="active">eng</p>
          <?}?>
        </div>
      </div>
      <nav class="headerNew-nav">
        <span class="headerNew-nav-line"></span>
        <ul class="headerNew-nav-list">
          <?
            $APPLICATION->IncludeComponent(
                "bitrix:menu", 
                "common__top__basket", 
                array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "left",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "top",
                    "USE_EXT" => "N",
                    "COMPONENT_TEMPLATE" => "common__top__basket"
                ),
                false
            );
            ?>
        </ul>  
        <div class="headerNew-nav-icons">
          <a href="<?=SITE_DIR?>personal/" title="<?=GetMessage('HEADER.TITLE_LK')?>"><img src="/verstka_new/buy_p/img/user.svg" alt=""></a>
          <div class="headerNew-nav-icons-basket" ng-init="site='<?=SITE_ID?>'; initCart()" >
            <img src="/verstka_new/buy_p/img/basket.svg" title="<?=GetMessage('HEADER.TITLE_CART')?>" alt="">
            <span class="headerNew-nav-icons-basket-counter" ng-cloak="">{{calculateProductsNum()}}</span>
            <div class="headerNew-basket" ng-cloak="">
              <div class="headerNew-basket_title">
                <p><?=GetMessage('HEADER.TITLE_CART')?> <br><span><?=GetMessage('HEADER.ITEMS')?>: {{calculateProductsNum()}}</span></p>
                <a href="<?=SITE_DIR?>cart/" ng-show="calculateProductsNum() > 0"><?=GetMessage('HEADER.GOTOBASKET')?></a>
              </div>
              <div class="headerNew-basket_products">
                <div class="headerNew-basket_products-product">
                  <a href="<?=SITE_DIR?>buy/dicomviewer/"   ng-show="checkIfViewerInBasket()" class="headerNew-basket_products-product-title"><?=GetMessage('HEADER.VIEWER');?></a>
                  <div  ng-repeat="liteItem in liteRedactions | filter: itemFilter">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="lite product-name">LITE <span class="black-bold">x{{liteItem.quantity}}</span></p>
                      <p class="price">{{liteItem.price}} ₽</p>
                      <svg ng-click="delLiteFromBasket($event, liteItem)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>
                    <div class="headerNew-basket_products-product-container product-active-bg product-active-cross" ng-show="liteItem.license.inbasket">
                      <p class="black product-name"><?=GetMessage('HEADER.SUBSCRIBE')?> <span>x{{liteItem.quantity}}</span></p>
                      <p class="price">{{liteItem.license.price}} ₽</p>
                      <svg ng-click="defaultDelGood(liteItem.license)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>
                  </div>
                  
                  <div  ng-repeat="proItem in proRedactions | filter: itemFilter">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="pro product-name">PRO <span class="black-bold" >x{{proItem.quantity}}</span></p>
                      <p class="price">{{proItem.price}} ₽</p>
                      <svg ng-click="delProFromBasket($event, proItem)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                    <div class="headerNew-basket_products-product-container product-active-bg product-active-cross"  ng-repeat="module in proItem.modules | filter: itemFilter">
                      <p class="black product-name">{{module.name}} <span>x{{proItem.quantity}}</span></p>
                      <p class="price">{{module.price}} ₽</p>
                      <svg width="12"  ng-click="delModuleFromBasket($event, module)" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                    <div class="headerNew-basket_products-product-container product-active-bg product-active-cross"  ng-show="proItem.license.inbasket">
                      <p class="black product-name"><?=GetMessage('HEADER.SUBSCRIBE')?> <span>x{{proItem.quantity}}</span></p>
                      <p class="price">{{proItem.license.price}} ₽</p>
                      <svg ng-click="defaultDelGood(proItem.license)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>
                 </div>
                <a href="<?=SITE_DIR?>buy/dicomserver/" ng-show="serverRedaction" class="headerNew-basket_products-product-title"><?=GetMessage('HEADER.SERVER');?></a>
                <div  ng-show="serverRedaction">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="pro product-name">SERVER, <?=GetMessage('HEADER.CONNECTIONS')?>: {{serverRedaction.serverConnections}} <span class="black-bold" >x1</span></p>
                      <p class="price">{{serverRedaction.offers[serverRedaction.serverConnections].price}} ₽</p>
                      <svg ng-click="delServerFromBasket($event)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                    <div class="headerNew-basket_products-product-container product-active-bg product-active-cross"  ng-show="serverRedaction.license.inbasket">
                      <p class="black product-name"><?=GetMessage('HEADER.SUBSCRIBE')?> </p>
                      <p class="price">{{serverRedaction.license.price}} ₽</p>
                      <svg ng-click="defaultDelGood(serverRedaction.license)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>
                 </div>
                <p ng-show="licenseUpdateViewerCount() > 0 || newServConn.inbasket" class="headerNew-basket_products-product-title"><?=GetMessage('HEADER.LICENSES_RENEWAL')?> </p>
                <div  ng-repeat="itemUpdate in extRedactions.lite | filter: licenseUpdateFilter">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="black product-name"><?=GetMessage('HEADER.LICENSE_RENEWAL')?> {{itemUpdate.lic}} <span class="black-bold" >x1</span></p>
                      <p class="price">{{itemUpdate.updateLic.price}} ₽</p>
                      <svg ng-click="delProFromBasket($event, itemUpdate.updateLic)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                 </div>
                
                <div  ng-repeat="itemUpdate in extRedactions.pro | filter: licenseUpdateFilter">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="black product-name"><?=GetMessage('HEADER.LICENSE_RENEWAL')?> {{itemUpdate.lic}} <span class="black-bold" >x1</span></p>
                      <p class="price">{{itemUpdate.updateLic.price}} ₽</p>
                      <svg ng-click="delProFromBasket($event, itemUpdate.updateLic)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                 </div>
                <div  ng-repeat="itemUpdate in extRedactions.server | filter: licenseUpdateFilter">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="black product-name"><?=GetMessage('HEADER.LICENSE_RENEWAL')?> {{itemUpdate.lic}} <span class="black-bold" >x1</span></p>
                      <p class="price">{{itemUpdate.updateLic.price}} ₽</p>
                      <svg ng-click="delProFromBasket($event, itemUpdate.updateLic)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                 </div>
                
                <div  ng-show="newServConn.inbasket">
                    <div class="headerNew-basket_products-product-container product-active-cross">
                      <p class="black product-name"><?=GetMessage('HEADER.ADDITIONAL_CONNECTIONS')?>: +{{newServConn.updateLic.connectionsNum}} <span class="black-bold" >x1</span></p>
                      <p class="price">{{newServConn.updateLic.price}} ₽</p>
                      <svg ng-click="delProFromBasket($event, newServConn.updateLic)" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                        <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                      </svg>
                    </div>

                 </div>


                </div>
              </div>
              <div class="headerNew-basket_price" ng-show="calculateProductsNum() > 0">
                <p><?=GetMessage('HEADER.TOTAL')?> <span>{{calculateFullPrice()}} ₽</span></p>
                <button class="btn-blue" ng-click="redirect('<?=SITE_DIR?>cart/')"><?=GetMessage('HEADER.GOTOBASKET')?></button>
              </div>
            </div>
          </div>
          <a href="<?=SITE_DIR?>search/" title="<?=GetMessage('HEADER.TITLE_SEARCH')?>"><img src="/verstka_new/buy_p/img/search.svg" alt=""></a>
        </div>
        <div class="headerNew-nav-lines">
          <span></span>
          <span></span>
          <span></span>
        </div>
        
      </nav>
      <div class="headerNew-mobile-toggle">
          <span></span>
          <span></span>
          <span></span>
        </div>
    </div>
    <ul class="headerNew-nav-list sub-list">
       <?
            $APPLICATION->IncludeComponent(
                "bitrix:menu", 
                "common__top__basket", 
                array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "left",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(
                    ),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "top",
                    "USE_EXT" => "N",
                    "COMPONENT_TEMPLATE" => "common__top__basket"
                ),
                false
            );
            ?>
    </ul>
    <?if(!$no_title_block && !$is_main):?><h1 class="headerNew-list-title <?if($ruAboutViewerLic):?>headerNew-list-title-small<?endif;?>"><?$APPLICATION->ShowTitle(false)?></h1><?endif;?>
    
    <?
      $APPLICATION->IncludeComponent( // MENU.LEFT COMPONENT :: МЕНЮ РАЗДЕЛА САЙТА
          "bitrix:menu",
          "common__top__submenu",
          array(
              "ALLOW_MULTI_SELECT" 	=> "N",
              "CHILD_MENU_TYPE" 		=> "left",
              "DELAY" 				=> "N",
              "MAX_LEVEL" 			=> "1",
              "MENU_CACHE_GET_VARS" 	=> array(""),
              "MENU_CACHE_TIME" 		=> "3600",
              "MENU_CACHE_TYPE" 		=> "A",
              "MENU_CACHE_USE_GROUPS" => "Y",
              "ROOT_MENU_TYPE" 		=> "left",
              "USE_EXT" 				=> "N"
          )
      );
      ?>
    
    <span class="headerNew-basket-mask"></span>
    
    
    
    
  </header>
        
        
		<div class="main-container <?if(!$no_title_block && !$is_main):?>header-margin-inner<?endif;?>">
          
          <?/*?>
			<div class="header">
				<div class="header__logo">
					<?if (!$is_main){?>
						<a class="header__logo-link" href="<?=SITE_DIR?>" title="<?=$MESS[SITE_ID]['ON_MAIN']?>"><img src="<?=SITE_TEMPLATE_PATH?>/img/logo_white.svg" width="225" height="87" alt="ИНОБИТЕК / Инновации в медицине"></a> 
					<?}else{?>
						<img src="<?=SITE_TEMPLATE_PATH?>/img/logo_white.svg" width="225" height="87" alt="ИНОБИТЕК / Инновации в медицине">
					<?}?>
                    <ul class="header__lang">
                        <?if(SITE_ID == 's1'){?>
                        <li><span>RUS</span></li>
                        <li><a href="/eng">ENG</a></li>
                        <?}else{?>
                        <li><a href="/">RUS</a></li>
                        <li><span>ENG</span></li>
                        <?}?>
                    </ul>
				</div>
				
				<div class="header__menu">
					<?
					$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"common__top__basket", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "top",
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "common__top__basket"
	),
	false
);
					?>
					<div class="dec">
						<span></span>
						<span class="wd"></span>
						<span></span>
					</div>
				</div>
				<a href="#" class="menu-trigger">
					<span></span>
					<span></span>
					<span></span>
				</a>
			</div>
           * 
           */?>
          
            
          
			<!-- END :: HEADER -->
			<?if(! ($is_main /*|| $newPages*/)){?>
			<!-- START :: CONTENT -->
			<div class="content">
				<?/*if(!$no_title_block){?>
				
				<?if($pages[1] != "eng"){?>
				<div class="page__title 1 <?=($pages[1] != 'search' && $pages[1] != 'buy'  ? $pages[1] : '');?>">
				<?}else{?>
				<div class="page__title 1 <?=($pages[2] != 'search' && $pages[2] != 'buy' ? $pages[2] : '');?>">
				<?}?>
					<?if($no_h1){?>
					<div class="like_h1"><?$APPLICATION->ShowTitle(false)?></div>
					<?}else{?>
					<h1><?$APPLICATION->ShowTitle(false)?></h1>
					<?}?>
					<div class="page__menu">
					<?
					$APPLICATION->IncludeComponent( // MENU.LEFT COMPONENT :: МЕНЮ РАЗДЕЛА САЙТА
						"bitrix:menu",
						"common__top",
						array(
							"ALLOW_MULTI_SELECT" 	=> "N",
							"CHILD_MENU_TYPE" 		=> "left",
							"DELAY" 				=> "N",
							"MAX_LEVEL" 			=> "1",
							"MENU_CACHE_GET_VARS" 	=> array(""),
							"MENU_CACHE_TIME" 		=> "3600",
							"MENU_CACHE_TYPE" 		=> "A",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"ROOT_MENU_TYPE" 		=> "left",
							"USE_EXT" 				=> "N"
						)
					);
					?>
					</div>
				</div>
				<?}*/?>
              <?if(!$newPageCSS):?>
				<div class="page__content <?if($no_title_block){echo "notitle";}?> <?if($ruAboutViewerLic):?>license-page<?endif;?>">
              <?endif;?>    
					<div class="wr">
			<?}?>
