<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>

<?
	if($_GET['CODE'] && $_GET['CODE'] != '')
	{
		$сache = Bitrix\Main\Data\Cache::createInstance();

		if($сache -> initCache(36000, 'gallery_'.$_GET['CODE'].'_'.SITE_ID)) 
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
					array('ID', 'NAME')
				);

				if($arItem = $preItems -> GetNext()){

					$result['NAME'] = $arItem['NAME'];

					$APPLICATION->SetPageProperty('title', $arItem['NAME'].' / '.$MESS[SITE_ID]['GALLERY_NAME']);

					$preGal = CIBlockElement::GetList(
						array('sort' => 'asc', 'active_from' => 'desc'),
						array('IBLOCK_CODE' => 'products_gallery_'.SITE_ID, "ACTIVE" => "Y", "=PROPERTY_PRODUCT_ID" => $arItem['ID']),
						false,
						false,
						array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_PHOTO', 'PROPERTY_VIDEO')
					);

					while($gal = $preGal -> GetNext()){

						$tmpItem = null;

						$tmpItem['NAME'] = $gal['NAME'];
						$tmpItem['TEXT'] = $gal['PREVIEW_TEXT'];

						if($gal['PROPERTY_VIDEO_VALUE']){
							$tmpItem['VIDEO'] = $gal['PROPERTY_VIDEO_VALUE'];
						}

						$photo = null;

						foreach ($gal['PROPERTY_PHOTO_VALUE'] as $key => $photo){
							if($s = CFile::ResizeImageGet($photo, array('width'=>300, 'height'=>300), BX_RESIZE_IMAGE_EXACT, true)){
								$prePhoto['SM'] = $s;
							}
							if($b = CFile::ResizeImageGet($photo, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, true)){
								$prePhoto['BG'] = $b;
							}

							$tmpItem['PHOTO'][] = $prePhoto;
						}

						$result['ITEMS'][] = $tmpItem;
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
	}else{
		define("ERROR_404",true);
		CHTTP::SetStatus("404 Not Found");
	}
?>
<?if($result){
?>
<div class="product__gallery">
	<h1>
		<?=$result['NAME']?>
	</h1>
	<?foreach ($result['ITEMS'] as $key => $arItem){?>
		<h2><?=$arItem['NAME']?></h2>
		<?if($arItem['TEXT']){?>
		<div class="gallery__text">
			<?=$arItem['TEXT']?>
		</div>
		<?}?>
		<?if($arItem['PHOTO']){?>
		<div class="gallery__photo">
			<?foreach ($arItem['PHOTO'] as $key => $photo){?>
				<a href="<?=$photo['BG']['src']?>" class="item" data-fancybox="popup-image">
					<img src="<?=$photo['SM']['src']?>" width="<?=$photo['SM']['width']?>" height="<?=$photo['SM']['height']?>" alt=""/>
				</a>
			<?}?>
			<div class="cl"></div>
		</div>
		<?}?>
		<?if($arItem['VIDEO']){?>
		<div class="gallery__video">
			<?
			$gotWide = false;

			if(sizeof($arItem['VIDEO'])%2 == 1){
				$gotWide = true;
			}
			foreach ($arItem['VIDEO'] as $key => $video){?>
			<div class="item <?if($key == 0 && $gotWide){ echo 'wide'; }?>">
				<iframe width="100%" height="100%" src="<?=$video?>" frameborder="0" allowfullscreen></iframe>
			</div>
			<?}?>
			<div class="cl"></div>
		</div>
		<?}?>
	<?}?>
</div>
<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>