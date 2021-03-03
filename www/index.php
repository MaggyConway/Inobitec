<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Компания Инобитек разрабатывает программное обеспечение для организаций, работающих в сфере здравоохранения, на заказ и по приемлемым ценам, а также предлагает сотрудникам частных диагностических центров, медицинских клиник, государственных учреждений здравоохранения, медицинским стартапам собственные тиражные продукты: Инобитек DICOM-Просмотрщик, Инобитек Web DICOM-Просмотрщик, Инобитек DICOM-Сервер (PACS)");
$APPLICATION->SetPageProperty("keywords", "DICOM, dicom, просмотрщик, PACS, сервер, архив, программное обеспечение, разработка на заказ, МИС, РИС, ЛИС, worklist, HL7");
$APPLICATION->SetPageProperty("title", "Инобитек / Программное обеспечение для медицины: Инобитек DICOM-Просмотрщик, Инобитек Web DICOM-Просмотрщик, Инобитек DICOM-Сервер (PACS)");
$APPLICATION->SetTitle("Инобитек / Разработка программного обеспечения для медицины / Собственные тиражные продукты: Инобитек DICOM-Просмотрщик, Инобитек Web DICOM-Просмотрщик, Инобитек DICOM-Сервер (PACS)");

?>
<!-- MP-BANNER -->

<?require($_SERVER["DOCUMENT_ROOT"]."/include/mainslider.php");?>

<!-- END :: MP-BANNER -->

<!-- START :: MP-SOLUTIONS -->

<?require($_SERVER["DOCUMENT_ROOT"]."/includes/mp_solutions.php");?>

<!-- END :: MP-SOLUTIONS -->

<? // echo "<pre>"; var_dump(SITE_DIR."/includes/po_title.inc.php"); echo "</pre>"; ?>
<!-- START :: MP-PO -->
<div class="mp-po">
	<div class="inn">
		<div class="title">
			<?
			// включаемая область для раздела
			$APPLICATION->IncludeFile("/includes/po_title.inc.php", Array(), Array(
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
  "name": "ООО «Инобитек»",
  "url": "http://inobitec.ru",
  "logo": "http://inobitec.com/InobitecLogo.png",
  "image": "http://inobitec.com/upload/resize_cache/iblock/ccc/800_600_1/1_heart_mesh_edit.png",
  "description": "Загрузите Инобитек DICOM-просмотрщик для Windows, Mac OS X, Linux: http://inobitec.ru/downloads/dicomviewer/",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "ул. Никитинская, дом 8а, 7 этаж, офис 711",
    "addressLocality": "Воронеж",
    "addressRegion": "Воронежская область'",
    "postalCode": "394052",
    "addressCountry": "RU"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+7-920-405-6743",
    "contactType": "sales",
    "availableLanguage": "Русский"
  }
}
 </script>

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
<!-- End Facebook Pixel Code --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>