<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>
<?
	if($_POST['ITEM_ID'] && $_POST['ITEM_ID'] != ''){

		$сache = Bitrix\Main\Data\Cache::createInstance();

		if($сache -> initCache(36000, 'product-features__'.$_POST['ITEM_ID'].'_'.$_POST['SITE_ID'])) 
		{ 
		    $result = $сache -> getVars(); 
		} 
		elseif($сache -> startDataCache())
		{ 
			if(CModule::IncludeModule('iblock')){
				$preInfo = CIBlockElement::GetList(
					array('sort' => 'asc'),
					array('IBLOCK_CODE' => 'keystones_'.$_POST['SITE_ID'], 'ID' => $_POST['ITEM_ID']),
					false,
					array('nTopCount' => 1),
					array('ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE')
				);

				if($result = $preInfo -> GetNext()){
					if($result['PREVIEW_PICTURE']){
						$result['PREVIEW_PICTURE'] = CFile::GetFileArray($result['PREVIEW_PICTURE']);
					}
				}else{
					$isInvalid = true;
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
?>

<?
if($result){
	if($result['PREVIEW_PICTURE']){?>
<div class="ico">
	<img src="<?=$result['PREVIEW_PICTURE']['SRC']?>" width="<?=$result['PREVIEW_PICTURE']['WIDTH']?>" height="<?=$result['PREVIEW_PICTURE']['HEIGHT']?>" alt="<?=$result['NAME']?>" />
</div>
<?
	}
?>
<div class="name">
	<?=$result['NAME']?>
</div>
<div class="text">
	<?=$result['PREVIEW_TEXT']?>
</div>
<?	
}else{
	echo 'error';
}?>


