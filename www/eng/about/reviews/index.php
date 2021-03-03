<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Reviews for Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, reviews");
$APPLICATION->SetPageProperty("title", "Inobitec / Company's reviews");
$APPLICATION->SetTitle("Reviews");
?>

<?
	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'reviews__list'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' =>'reviews_'.SITE_ID, "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PREVIEW_PICTURE')
			);

			while($arItem = $preItems -> GetNext()){
				
				if($arItem['PREVIEW_PICTURE']){

					$previewPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>400, 'height'=>566), BX_RESIZE_IMAGE_EXACT, true);  
					$detailPicture  = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>1000, 'height'=>1369), BX_RESIZE_IMAGE_EXACT, true);  

					if($previewPicture){
						$arItem['PREVIEW_PICTURE'] = $previewPicture;
					}
					if($detailPicture){
						$arItem['DETAIL_PICTURE']  = $detailPicture; 
					}

					$result[] = $arItem;
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
<?if($result){?>
<div class="reviews-page">
	<div class="reviews__list">
		<ul>
			<?foreach ($result as $key => $arItem){?>
			<li>
				<span class="inn"><a data-fancybox="popup-image" href="<?=$arItem['DETAIL_PICTURE']['src']?>"><img src="<?=$arItem['PREVIEW_PICTURE']['src']?>" width="<?=$arItem['PREVIEW_PICTURE']['width']?>" height="<?=$arItem['PREVIEW_PICTURE']['height']?>" alt="<?=$arItem['NAME']?>"></a></span>
			</li>
			<?}?>
		</ul>
	</div>	
</div>
<?}?>

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