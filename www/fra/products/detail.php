<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
LocalRedirect("/fra/products/");
$APPLICATION->SetPageProperty("description", "Inobitec DICOM-viewer - a modern software product for viewing medical studies with advanced 3D-visualization and all necessary viewing functionality in 2D");
$APPLICATION->SetPageProperty("keywords", "DICOM, dicom, viewer, 2D, 3D, MPR, ECG");
$APPLICATION->SetPageProperty("title", "Inobitec DICOM-viewer - cross-platform viewer of DICOM-data with advanced 3D-visualization");
?>

<?
	if($_GET['CODE'] && $_GET['CODE'] != ''){

	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'product-detail__'.$_GET['CODE'].'_'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' => 'products_'.SITE_ID, "ACTIVE" => "Y", "CODE" => $_GET['CODE']),
				false,
				array('nTopCount' => 1),
				array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'PROPERTY_NAME_FORMATED_DETAIL', 'PROPERTY_DOWNLOAD_LINK', 'PROPERTY_FREE_HTML')
			);

			if($result = $preItems -> GetNext()){

				if($result['DETAIL_PICTURE']){
					$result['DETAIL_PICTURE'] = CFile::GetFileArray($result['DETAIL_PICTURE']);
				}	

				$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues(4,$result['ID']); 
				$arProperty  = $ipropValues->getValues();

				$result['SEO'] = $arProperty;

				$preFeatures = CIBlockElement::GetList(
					array('sort' => 'asc', 'name' => 'asc'),
					array('IBLOCK_CODE' => 'keystones_'.SITE_ID, 'PROPERTY_PRODUCT' => $result['ID']),
					false,
					false,
					array('ID', 'NAME', 'PREVIEW_PICTURE')
				);

				if($preFeatures ->result->num_rows && $preFeatures ->result->num_rows > 0){

					$i = 0;

					while ( $features = $preFeatures -> GetNext() ) {

						$result['FEATURES'][$i] = $features;

						if($features['PREVIEW_PICTURE']){
							$result['FEATURES'][$i]['PREVIEW_PICTURE'] = CFile::GetFileArray($features['PREVIEW_PICTURE']);
						}
						$i++;
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

	if($result["SEO"]["ELEMENT_META_TITLE"]){
	 	$APPLICATION -> SetPageProperty('title', $result["SEO"]["ELEMENT_META_TITLE"]);
	}else{
		$APPLICATION -> SetPageProperty('title', $result["NAME"]);
	}

	if($result["SEO"]["ELEMENT_META_KEYWORDS"]){
	 	$APPLICATION -> SetPageProperty('keywords', $result["SEO"]["ELEMENT_META_KEYWORDS"]);
	}else{
		$APPLICATION -> SetPageProperty('keywords', $result["NAME"]);
	}

	if($result["SEO"]["ELEMENT_META_DESCRIPTION"]){
	 	$APPLICATION -> SetPageProperty('description', $result["SEO"]["ELEMENT_META_DESCRIPTION"]);
	}else{
		$APPLICATION -> SetPageProperty('description', $result["NAME"]);
	}
	

?>
</div><!-- WR CLOSED -->
<div class="product__title" <?if($result['DETAIL_PICTURE']){?>style="background-image: url(<?=$result['DETAIL_PICTURE']['SRC']?>)"<?}?>>
	<div class="wr">
		<h1>
			<?=$result['~PROPERTY_NAME_FORMATED_DETAIL_VALUE']['TEXT']?>
		</h1>
		<ul class="product__links">
			<li><a class="keystones" href="#"><span><?=$MESS[SITE_ID]['PRODUCT_FEATURES']?></span></a></li>
			<?
				if($result['PROPERTY_DOWNLOAD_LINK_VALUE']){
			?>
			<li class="down"><a href="<?=$result['PROPERTY_DOWNLOAD_LINK_VALUE']?>"><span><?=$MESS[SITE_ID]['PRODUCT_DOWNLOAD']?></span></a></li>
			<?
				}
			?>
			<li><a href="gallery/"><span><?=$MESS[SITE_ID]['PRODUCT_GALLERY']?></span></a></li>
		</ul>
		<?if($result['DETAIL_TEXT']){?>
		<div class="product__title-description">
			<?=$result['DETAIL_TEXT']?>
		</div>
		<?}?>
	</div>
</div>

<?if($result ["~PROPERTY_FREE_HTML_VALUE"]["TEXT"]){?>
<div class="product__free-html">
	<div class="wr">
		<?=$result ["~PROPERTY_FREE_HTML_VALUE"]["TEXT"]?>
	</div>
</div>
<?}?>
<?
if($result['FEATURES']){
?>

<div class="product__features">
	<div class="wr">

		<h2>
			<?=$MESS[SITE_ID]['PRODUCT_FEATURES_TITLE']?>
		</h2>
		<div class="items">
			<?
			foreach ($result['FEATURES'] as $key => $feature) 
			{
			?>
			<a class="item" href="#" data-id="<?=$feature['ID']?>">
				<img src="<?=$feature['PREVIEW_PICTURE']['SRC']?>" width="<?=$feature['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$feature['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$feature['NAME']?>" />
			</a>
			<?
			}
			?>
			<div class="cl"></div>
		</div>
	</div>
</div>
<?
}
?>
<div class="wr"><!-- WR OPEN -->
<?
	}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>