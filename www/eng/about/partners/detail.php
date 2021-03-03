<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнеры");
?>

<?
	if($_GET['ID']){

		$сache = Bitrix\Main\Data\Cache::createInstance();

		if($сache -> initCache(36000, 'reviews__detail-eng'.$_GET['ID'].'')) 
		{ 
		    $result = $сache -> getVars(); 
		} 
		elseif($сache -> startDataCache())
		{ 
			if(CModule::IncludeModule('iblock')){

				$preItems = CIBlockElement::GetList(
					array('sort' => 'asc', 'active_from' => 'desc'),
					array('IBLOCK_ID' =>12, "ACTIVE" => "Y", 'ID' => $_GET['ID']),
					false,
					false,
					array('ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT')
				);

				if($arItem = $preItems -> GetNext()){

					if($arItem['PREVIEW_PICTURE']){
						$previewPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>240, 'height'=>120), BX_RESIZE_IMAGE_PROPORTIONAL, true);  

						if($previewPicture){
							$arItem['PREVIEW_PICTURE'] = $previewPicture;
						}

						$result = $arItem;
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
	}

	if($result){
?>
	<div class="partners__detail">
		<div class="preview">
			<?if($result['PREVIEW_PICTURE']){?>
			<div class="ico"><img src="<?=$result['PREVIEW_PICTURE']['src']?>" width="<?=$result['PREVIEW_PICTURE']['width']?>" height="<?=$result['PREVIEW_PICTURE']['height']?>" /></div>
			<?}?>
			<?=$result['PREVIEW_TEXT'];?>
		</div>
		<?=$result['DETAIL_TEXT'];?>
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