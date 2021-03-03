<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Vacancies of Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, vacancies");
$APPLICATION->SetPageProperty("title", "Inobitec / Stellenangebote");
$APPLICATION->SetTitle("Stellenangebote");
?>

<?
	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'careers__list'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' => 'vacancies_'.SITE_ID, "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PROPERTY_DUTIES', 'PROPERTY_TYPE_OF_EMPLOYMENT', 'PROPERTY_REQUIREMENTS', 'PROPERTY_CONDITIONS', 'PROPERTY_CONDITIONS', 'PROPERTY_WELCOMED', 'PROPERTY_FREE_HTML')
			);

			while($arItem = $preItems -> GetNext()){

				$result[] = $arItem;
			}

		}else{

			$isInvalid = true;
		}

	    if ($isInvalid) 
	    { 
	        $cache->abortDataCache(); 
	    } 
	    $сache->endDataCache($result); 
	}
?>

<div class="careers">
	<?
		if($result){
	?>
	<div class="careers__left">
		<div class="careers__selector">
			<?foreach ($result as $key => $arName) {?>
				<a href="#" class="item <?if($key == 0) echo 'active';?>"><?=$arName['NAME']?></a>
			<?
			}?>
		</div>
	</div>	
	<div class="careers__right">
		<?foreach ($result as $key => $arItem) {?>
		<div class="tab_content <?if($key == 0) echo 'shown';?>">
			<h2>
				<?=$arItem['NAME']?>
			</h2>
			<?if($arItem['~PROPERTY_DUTIES_VALUE']['TEXT']){?>
			<div class="block">
				<div class="title"><?=$MESS[SITE_ID]['CARRERS_DUTIES']?></div>
				<?=$arItem['~PROPERTY_DUTIES_VALUE']['TEXT']?>
			</div>
			<?}?>
			<?if($arItem['~PROPERTY_TYPE_OF_EMPLOYMENT_VALUE']['TEXT']){?>
			<div class="block">
				<div class="title"><?=$MESS[SITE_ID]['CARRERS_TYPE_OF_EMPLOYMENT']?></div>
				<?=$arItem['~PROPERTY_TYPE_OF_EMPLOYMENT_VALUE']['TEXT']?>
			</div>
			<?}?>
			<div class="cl"></div>
			<?if($arItem['~PROPERTY_REQUIREMENTS_VALUE']['TEXT']){?>
			<div class="block">
				<div class="title"><?=$MESS[SITE_ID]['CARRERS_REQUIREMENTS']?></div>
				<?=$arItem['~PROPERTY_REQUIREMENTS_VALUE']['TEXT']?>
			</div>
			<?}?>
			<?if($arItem['~PROPERTY_CONDITIONS_VALUE']['TEXT']){?>
			<div class="block">
				<div class="title"><?=$MESS[SITE_ID]['CARRERS_CONDITIONS']?></div>
				<?=$arItem['~PROPERTY_CONDITIONS_VALUE']['TEXT']?>
			</div>
			<?}?>
			<div class="cl"></div>
			<?if($arItem['~PROPERTY_WELCOMED_VALUE']['TEXT']){?>
			<div class="block wide">
				<div class="title"><?=$MESS[SITE_ID]['CARRERS_WELCOME']?>:</div>
				<?=$arItem['~PROPERTY_WELCOMED_VALUE']['TEXT']?>
			</div>
			<?}?>
			<?if($arItem['~PROPERTY_FREE_HTML_VALUE']['TEXT']){?>
			<div class="block wide">
				<?=$arItem['~PROPERTY_FREE_HTML_VALUE']['TEXT']?>
			</div>
			<?}?>
		</div>
		<?}?>
	</div>	
	<div class="cl"></div>
	<?}?>
	<div class="carees__highlight">
	<?
	// включаемая область для раздела
	$APPLICATION->IncludeFile(SITE_DIR."/includes/careers_text.php", Array(), Array(
	    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
	    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
	    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
	    ));
	?>
	</div>
</div>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter28963570 = new Ya.Metrika({id:28963570,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/28963570" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>