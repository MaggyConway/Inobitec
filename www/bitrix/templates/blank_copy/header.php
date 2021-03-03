<?
    if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

    use Bitrix\Main\Application;
    use Bitrix\Main\Page\Asset;
    use Bitrix\Main\Loader;

    require($_SERVER["DOCUMENT_ROOT"].'/lang.php'); // Подключили языковой файл
	
	$version_files = "4";
	
	$pages = $APPLICATION -> GetCurDir();		  
	$pages = explode('/', $pages); // МАССИВ НАЗВАНИЙ ТЕКУЩИХ РАЗДЕЛОВ
	
	$httpScheme = 'http';
	if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
		$httpScheme = 'https';
	}
?>

<!doctype html>
<html class="no-js" lang="ru">
    <head>
        
        <title>
            <?$APPLICATION->ShowTitle();?>
        </title>
		<meta name="google-site-verification" content="leKR625ixanknOPfUmBaBFcmPFTUmwYb4q6fjVN-jSc" />
		
		<link rel="apple-touch-icon" sizes="57x57" href="/iconss/apple-icon-57x57.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="60x60" href="/iconss/apple-icon-60x60.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="72x72" href="/iconss/apple-icon-72x72.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="76x76" href="/iconss/apple-icon-76x76.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="114x114" href="/iconss/apple-icon-114x114.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="120x120" href="/iconss/apple-icon-120x120.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="144x144" href="/iconss/apple-icon-144x144.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="152x152" href="/iconss/apple-icon-152x152.png?v=<?=$version_files;?>">
		<link rel="apple-touch-icon" sizes="180x180" href="/iconss/apple-icon-180x180.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" href="/favicon.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="192x192"  href="/iconss/android-icon-192x192.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="32x32" href="/iconss/favicon-32x32.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="96x96" href="/iconss/favicon-96x96.png?v=<?=$version_files;?>">
		<link rel="icon" type="image/png" sizes="16x16" href="/iconss/favicon-16x16.png?v=<?=$version_files;?>">
		<link rel="manifest" href="/manifest.json">
		<meta name="msapplication-TileColor" content="#00163f">
		<meta name="msapplication-TileImage" content="/icons/ms-icon-144x144.png?v=<?=$version_files;?>">
		<meta name="theme-color" content="#00163f">

        <script data-skip-moving=true src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script data-skip-moving=true>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
        <!-- Place favicon.ico in the root directory -->

        <?$APPLICATION->ShowHead();?>
        
        <?

            Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1">');
            Asset::getInstance()->addString('<link rel="apple-touch-icon" href="apple-touch-icon.png">');
            Asset::getInstance()->addString('<meta http-equiv="x-ua-compatible" content="ie=edge">');
            Asset::getInstance()->addString('<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=cyrillic" rel="stylesheet">');
/*if(SITE_ID == 's1'){
			Asset::getInstance()->addCss('/includes/cron/ru_docs/Viewer_ru.css');
}else{
			Asset::getInstance()->addCss('/includes/cron/html_default/Viewer_default.css');
}*/
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/css/normalize.css');
            Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/template_styles.css');

            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/vendor/modernizr-2.8.3.min.js');
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/vendor/velocity.min.js');
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/plugins.js');
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/main.js');

        ?>
		
		<?if($pages[1] != "eng"){?>
			<meta property="og:image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/iconss/share.png?v=<?=$version_files;?>">
		<?}else{?>
			<meta property="og:image" content="<?=$httpScheme;?>://<?=$_SERVER['SERVER_NAME'];?>/iconss/share_eng.png?v=<?=$version_files;?>">
		<?}?>
		<meta property="og:image:type" content="image/png">
    </head>
    <body>
        <?$APPLICATION->ShowPanel();?>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div>