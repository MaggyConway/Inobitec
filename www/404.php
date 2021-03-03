<?
define("ERROR_404",true);
CHTTP::SetStatus("404 Not Found");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle($MESS[SITE_ID]['TEXT_404']);



?>

<div class="page404">
	<div class="content404">
		<img src="<?=SITE_TEMPLATE_PATH?>/img/ico404.png" width="154" height="154" alt="<?=$MESS[SITE_ID]['TEXT_404']?>" />
		<div class="err">404</div>
		<div class="text"><?=$MESS[SITE_ID]['TEXT_404']?></div>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>