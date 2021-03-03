<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Partners of Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, partners");
$APPLICATION->SetPageProperty("title", "Inobitec / Partner");
$APPLICATION->SetTitle("Partner");

?>

<?
	$сache = Bitrix\Main\Data\Cache::createInstance();

	if(false &&  $сache -> initCache(36000, 'partners_de_list'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' => 'partners_s4', "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT')
			);

			while($arItem = $preItems -> GetNext()){
		
				if($arItem['PREVIEW_PICTURE']){
					$previewPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>240, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true);  

					if($previewPicture){
						$arItem['PREVIEW_PICTURE'] = $previewPicture;
					}

					$result[] = $arItem;
				}else{
				
				}
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
	<div class="partners-page">
		<div class="partners__list">
			<ul>
				<?foreach ($result as $key => $arItem) {?>
				<li>
					<span class="ico"><span class="in"><span class="inn"><img src="<?=$arItem['PREVIEW_PICTURE']['src']?>" width="<?=$arItem['PREVIEW_PICTURE']['width']?>" height="<?=$arItem['PREVIEW_PICTURE']['height']?>" alt="<?=$arItem['NAME']?>" /></span></span></span>
					<span class="text">
						<?if($arItem['DETAIL_TEXT']){?>
	 					<b><a href="<?=$arItem['ID']?>/"><?=$arItem['NAME']?></a></b>
	 					<?}else{?>
	 						<b><?=$arItem['NAME']?></b>
	 					<?}?>
						
	 					<?=$arItem['PREVIEW_TEXT']?>
	 					
					</span>
				</li>
				<?
				}?>
				
			</ul>
		</div>
	</div>
<?
	}
?>

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