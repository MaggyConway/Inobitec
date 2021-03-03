<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobitec LLC develops software in the medical area by order, and also offers to specialists, who works in hospitals, clinics, institutions, medical startups their own software: Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS)");
$APPLICATION->SetPageProperty("keywords", "DICOM, dicom, viewer, web viewer, PACS, server, software, medical, medicine, health industry, software development, order, HL7");
$APPLICATION->SetPageProperty("title", "Inobitec / Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS), custom software development");
$APPLICATION->SetTitle("Inobitec / Software for Healthcare: Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS), custom software development");
$APPLICATION->AddHeadScript('/verstka_new/js/jquery.touchSwipe.min.js');
$APPLICATION->AddHeadScript('/verstka_new/js/slider.js');
?>
<!-- MP-BANNER -->
<?require($_SERVER["DOCUMENT_ROOT"]."/include/mainslider.php");?>
<!-- END :: MP-BANNER -->

<!-- START :: MP-SOLUTIONS -->

<?require($_SERVER["DOCUMENT_ROOT"]."/includes/mp_solutions.php");?>

<!-- END :: MP-SOLUTIONS -->

<!-- START :: MP-PO -->
<div class="mp-po">
	<div class="inn">
		<div class="title">
			<?
			// включаемая область для раздела
			$APPLICATION->IncludeFile(SITE_DIR."/includes/po_title.inc.php", Array(), Array(
			    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
			    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
			    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
			    ));
			?>
		</div>
		<a href="/services/" class="more-button bold"><?=$MESS[SITE_ID]['MORE']?></a>
		<div class="cl"></div>
	</div>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/mp-po-bg.jpg" width="1200" height="675" alt="разработка ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ">
</div>
<!-- END :: MP-PO -->

<?

	unset($сache);
	unset($result);

	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'mp_benefits_'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' =>'mp_benefits_'.SITE_ID, "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PREVIEW_PICTURE', 'PROPERTY_HTML_DESCR', 'PROPERTY_ADD_CLASSES', 'PROPERTY_FREE_HTML')
			);

			while($arItem = $preItems -> GetNext()){

				if($arItem['PREVIEW_PICTURE']){
					$arItem['PREVIEW_PICTURE'] = CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
				}

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

<?if($result){?>
<!-- START :: MP-BENEFITS -->
<div class="mp-benefits">
	<?foreach ($result as $key => $arItem){
		if($arItem['PROPERTY_FREE_HTML_VALUE'] == 'YES'){
	?>
		<div class="mp-benefits__item <?=$arItem['PROPERTY_ADD_CLASSES_VALUE']?>">
			<?=$arItem['~PROPERTY_HTML_DESCR_VALUE']['TEXT']?>
		</div>
		<?}else{?>
		<div class="mp-benefits__item">
			<?if($arItem['PREVIEW_PICTURE']){?>
			<div class="ico"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" width="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$arItem['NAME']?>"></div>
			<?}?>
			<div class="text">
				<div class="ebold"><?=$arItem['NAME']?></div>
				<?=$arItem['~PROPERTY_HTML_DESCR_VALUE']['TEXT']?>
			</div>
		</div>
		<?}?>
	<?}?>
	
	<div class="cl"></div>
</div>
<?}?>
<!-- END :: MP-BENEFITS -->

<script type='application/ld+json'> 
{
  "@context": "http://www.schema.org",
  "@type": "Organization",
  "name": "Inobitec, LLC",
  "url": "http://inobitec.com",
  "logo": "http://inobitec.com/InobitecLogo.png",
  "image": "http://inobitec.com/upload/resize_cache/iblock/ccc/800_600_1/1_heart_mesh_edit.png",
  "description": "Download Inobitec DICOM Viewer for Windows, Mac OS X, Linux now: http://inobitec.com/eng/downloads/dicomviewer/",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "office 711, 8a, Nikitinskaya St.",
    "addressLocality": "Voronezh",
    "addressRegion": "Voronezhskaya oblast'",
    "postalCode": "394052",
    "addressCountry": "RU"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+7-920-405-6743",
    "contactType": "sales",
    "availableLanguage": "English"
  }
}
 </script>

<script type="application/ld+json">
{
  "@context": "http://www.schema.org",
  "@type": "Product",
  "name": "Inobitec DICOM Viewer, version 1.9.0",
  "image": "http://inobitec.com/upload/iblock/9fc/sol_ico_1.png",
  "description": "Inobitec DICOM-viewer",
  "brand": {
    "@type": "Thing",
    "name": "DICOM-viewer"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.7",
    "reviewCount": "91"
  },
  "offers": {
    "@type": "Offer",
    "priceCurrency": "USD",
    "price": "555",
    "priceValidUntil": "2017-07-01",
    "seller": {
      "@type": "Organization",
      "name": "Inobitec, LLC"
    }
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "url": "http://inobitec.com/eng/products/dicomviewer/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "http://inobitec.com/eng/products/dicomviewer/search?q={dicom}",
    "query-input": "required name=dicom"
  }
}
</script>

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
<!-- /Yandex.Metrika counter -->

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1785887255057377'); // Insert your pixel ID here.
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1785887255057377&ev=PageView&noscript=1"
/></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1533950036693403');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1533950036693403&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script>
fbq('track', 'Search', {
search_string: 'dicom viewer',
search_string: 'dicom viewer mac',
search_string: 'dicom viewer macbook',
search_string: 'dicom viewer linux',
search_string: 'dicom viewer 3d',
search_string: 'dicom viewer 3D',
search_string: 'dcm viewer',
search_string: 'dicom image viewer',
search_string: 'mri viewer'
});</script><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

<script type="text/javascript">
_linkedin_partner_id = "2470858";
window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
window._linkedin_data_partner_ids.push(_linkedin_partner_id);
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=2470858&fmt=gif" />
</noscript>