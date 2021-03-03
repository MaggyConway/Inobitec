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

if(SITE_ID == 's1'){
	$no_title_block = ($pages[1] == 'services' && !$pages[2]) || ($pages[1] == 'products' && $pages[2] && !$pages[3]) && !$error_404;	 // НЕ ВЫВОДИТЬ БЛОК ЗАГОЛОВКА (УСЛУГИ, ПРОДУКТЫ)
	$no_f_links 	= $is_main || $pages[1] == 'services'; // НЕ ВЫВОДИТЬ БЛОК ССЫЛОК (НАШИ ПРОДУКТЫ, СКАЧАТЬ)
    $serverSlider =  ($pages[1] == 'buy' && $pages[2] == 'dicomserver') ? true : false;
}
if(SITE_ID == 's2'){
	$no_title_block = ($pages[2] == 'services' && !$pages[3]) || ($pages[2] == 'products' && $pages[3] && !$pages[4])&& !$error_404;	 // НЕ ВЫВОДИТЬ БЛОК ЗАГОЛОВКА (УСЛУГИ, ПРОДУКТЫ)
	$no_f_links 	= $is_main || $pages[2] == 'services'; // НЕ ВЫВОДИТЬ БЛОК ССЫЛОК (НАШИ ПРОДУКТЫ, СКАЧАТЬ)
    $serverSlider =  ($pages[2] == 'buy' && $pages[3] == 'dicomserver') ? true : false ;
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
		<?}else{?>
			<meta property="og:image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/icons/share_eng.png?v=<?=$version_files;?>">
		<?}?>
		<meta property="og:image:type" content="image/png">
	</head>
	<body <?if ($is_main){?> id="main-page"<?}else{?> class="inner"<?}?>>
		<?$APPLICATION->ShowPanel();?>
		<script>
			var site_id = '<?=SITE_ID?>';
		</script>

        <header class="headerNew">
    <div class="headerNew-wrapper">
      <div class="headerNew-container">
        <a href="<?=SITE_DIR?>" class="headerNew-container-logo">
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
        <div class="headerNew-nav-icons">
          <a href="<?=SITE_DIR?>personal/"><img src="/verstka_new/buy_p/img/user.svg" alt=""></a>
          <div class="headerNew-nav-icons-basket">
            <img src="/verstka_new/buy_p/img/basket.svg" alt="">
            <span class="headerNew-nav-icons-basket-counter">5</span>
            <div class="headerNew-basket">
              <div class="headerNew-basket_title">
                <p>корзина <br><span>5 наименований</span></p>
                <a href="">перейти в корзину</a>
              </div>
              <div class="headerNew-basket_products">
                <div class="headerNew-basket_products-product">
                  <a href="" class="headerNew-basket_products-product-title">Просмотрщик</a>
                  <div class="headerNew-basket_products-product-container product-active">
                    <p class="pro">PRO</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>

                  <div class="headerNew-basket_products-product-container product-disabled">
                    <p class="lite">LITE</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>

                  <div class="headerNew-basket_products-product-container product-active">
                    <p class="pro">PRO</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>

                  <div class="headerNew-basket_products-product-container product-active">
                    <p class="black">Модуль РЕТ\СТ</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>

                  <div class="headerNew-basket_products-product-container product-active">
                    <p class="black">Подписка на обновления</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>

                  <div class="headerNew-basket_products-product-container product-disabled">
                    <p class="black">Модуль Коронарографии</p>
                    <p class="price">50 000 ₽</p>
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C7C7C7"/>
                      <rect x="11.8579" y="10.0792" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0792)" fill="#C7C7C7"/>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="headerNew-basket_price">
                <p>итого <span>446 000 ₽</span></p>
                <button class="btn-blue">перейти в корзину</button>
              </div>
            </div>
          </div>
          <a href="<?=SITE_DIR?>search/"><img src="/verstka_new/buy_p/img/search.svg" alt=""></a>
        </div>
        <div class="headerNew-nav-lines">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </nav>
    </div>
    <ul class="headerNew-nav-list sub-list">
      <li><a href="">КОМПАНИЯ</a></li>
      <li><a href="">УСЛУГИ</a></li>
      <li><a href="">ПРОДУКТЫ</a></li>
      <li><a href="">ЦЕНТР ЗАГРУЗКИ</a></li>
      <li><a href="">КУПИТЬ</a></li>
      <li><a href="">ДОКУМЕНТАЦИЯ</a></li>
    </ul>
    <h1 class="headerNew-list-title"><?$APPLICATION->ShowTitle(false)?></h1>
    <span class="headerNew-basket-mask"></span>
  </header>
        
        
		<div class="main-container">
          
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
			<?if(!$is_main){?>
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
				<div class="page__content <?if($no_title_block){echo "notitle";}?>">
					<div class="wr">
			<?}?>
