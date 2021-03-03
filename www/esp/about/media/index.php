<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Mentions about Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, media, plots");
$APPLICATION->SetPageProperty("title", "Inobitec / Menciones en Internet y Televisión");
$APPLICATION->SetTitle("Medios de comunicación");
?>
<div class="page-media">
	<div class="intro">
		<?
		// включаемая область для раздела
		$APPLICATION->IncludeFile(SITE_DIR."/includes/media_text.php", Array(), Array(
		    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
		    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
		    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
		    ));
		?>
	</div>
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
					array('IBLOCK_CODE' => 'media_'.SITE_ID, "ACTIVE" => "Y"),
					false,
					false,
					array('ID', 'NAME', 'PREVIEW_TEXT', 'PROPERTY_YOU_LINK')
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
	<?
		if($result){
	?>
	<div class="media__items">
		<?foreach ($result as $key => $arItem){?>
		<div class="media__item">
			<div class="video">
				<iframe width="100%" height="100%" src="<?=$arItem['PROPERTY_YOU_LINK_VALUE']?>" frameborder="0" allowfullscreen></iframe>
			</div>
			<div class="text">
				<?=$arItem['PREVIEW_TEXT']?>
			</div>
		</div>
		<?}?>
	</div>
	<?}?>
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