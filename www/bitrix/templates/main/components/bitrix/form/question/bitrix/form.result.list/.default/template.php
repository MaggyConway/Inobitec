<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

require($_SERVER["DOCUMENT_ROOT"].'/lang.php'); // Подключили языковой файл
?>

<div class="feedback__ok-mess">
	<img src="<?=SITE_TEMPLATE_PATH?>/img/ok-mess-ico.png" width="128" height="128" alt=""/>
	<?=$MESS[SITE_ID]['OK_FEEDBACK']?>
</div>

<script>
	$("body,html").animate({"scrollTop":$('.feedback__ok-mess').offset().top},300);
</script>