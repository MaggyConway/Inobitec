<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobitec fournit aux spécialistes, qui travaillent dans le secteur de la santé, ses propres produits à télécharger: Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS)");
$APPLICATION->SetPageProperty("keywords", "Télécharger, installer, DICOM, Dicom, Viewer, Web Viewer, Dicom Viewer pour Mac, Dicom Viewer pour Windows, Dicom Viewer pour Linux, Serveur, PACS, PACS");
$APPLICATION->SetPageProperty("title", "Inobitec / Distributifs de ses propres produits à télécharger: Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS)");
$APPLICATION->SetTitle("Téléchargements");
$no_f_links = true;
?>

<div class="downloads__page">
	<div class="title">
		<?=$MESS[SITE_ID]['CHOISE_PRODUCT']?>
	</div>
	<?

	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'downloads_list_'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' => 'downloads_'.SITE_ID, "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PAGE_URL', 'PROPERTY_FORMATED_NAME', 'PROPERTY_LAST_VERSION', 'PROPERTY_LAST_REFRESH')
			);

			if($preItems->result->num_rows && $preItems->result->num_rows > 0){

				while($arItem = $preItems -> GetNext()){
					$Items[] = $arItem;
				}

			}

			$result = $Items; 

		    if ($isInvalid) 
		    { 
		        $cache->abortDataCache(); 
		    } 

		    $сache->endDataCache($result); 
		}
	} 
	?>

	<?
		if($result){
	?>
		<div class="downloads__products-list">
			<? 	foreach ($result as $key => $arItem) {
				
					$previewPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>330, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			?>
			<div class="item">
				<div class="c1">
				<?	if ($previewPicture) { ?>
					<img src="<?=$previewPicture['src']?>" width="<?=$previewPicture['width']?>" height="<?=$previewPicture['height']?>" alt="<?=$arItem['NAME']?>" />
				<?
					}
				?>
					
				</div>
				<div class="c2">
					<span class="name"><?=$arItem["~PROPERTY_FORMATED_NAME_VALUE"]["TEXT"]?></span>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="more"><?=$MESS[SITE_ID]['MORE']?></a>
				</div>
				<div class="c3">
					<div class="text"><?=$arItem['PREVIEW_TEXT']?></div>
					<?	if($arItem["~PROPERTY_LAST_VERSION_VALUE"]){ ?>
					<div class="version">
						<span class="label"><span><?=$MESS[SITE_ID]['LAST_VERSION']?></span></span>
						<span class="value"><span><b><?=$arItem["~PROPERTY_LAST_VERSION_VALUE"]?></b></span></span>
						<div class="cl"></div>
					</div>
					<?	} ?>
					<?	if($arItem["~PROPERTY_LAST_REFRESH_VALUE"]){ ?>
					<div class="version">
						<span class="label"><span><?=$MESS[SITE_ID]['LAST_UPDATE']?></span></span>
						<span class="value"><span><?=$arItem["~PROPERTY_LAST_REFRESH_VALUE"]?></span></span>
						<div class="cl"></div>
					</div>	
					<?	} ?>
				</div>
				<div class="cl"></div>
			</div>
			<? } ?>
		</div>
	<?
		}
	?>
</div>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'YpxlNpZnHd';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/geo-widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->

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