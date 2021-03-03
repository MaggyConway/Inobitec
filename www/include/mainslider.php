<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?> 
<?
  use Bitrix\Main\Localization\Loc;
  Loc::loadMessages(__FILE__);
  $APPLICATION->AddHeadScript('/verstka_new/js/jquery.touchSwipe.min.js');
  $APPLICATION->AddHeadScript('/verstka_new/js/slider.js');
	$сache = Bitrix\Main\Data\Cache::createInstance();

	if($сache -> initCache(36000, 'main__banner'.SITE_ID)) 
	{ 
	    $result = $сache -> getVars(); 
	} 
	elseif($сache -> startDataCache())
	{ 
		if(CModule::IncludeModule('iblock')){

			$preItems = CIBlockElement::GetList(
				array('sort' => 'asc', 'active_from' => 'desc'),
				array('IBLOCK_CODE' =>'banner_'.SITE_ID, "ACTIVE" => "Y"),
				false,
				false,
				array('ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'PROPERTY_LINK', 'PROPERTY_UNDER_TITLE', 'PROPERTY_TOP_TITLE')
			);

			while($arItem = $preItems -> GetNext()){

				if($arItem['PREVIEW_PICTURE']){
					if($img = CFile::GetFileArray($arItem['PREVIEW_PICTURE'])){
						$arItem['PREVIEW_PICTURE'] = $img;
					}
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

<? if($result) { ?>

<div class="main-slider">
  <img class="main-slider_bg-logo" src="<?=SITE_TEMPLATE_PATH?>/img/slider-images/slider-logo-bg.svg">
	<?foreach ($result as $key => $arItem) {
	?>
      <div class="main-slider_content <?if ($key == 0): ?>main-slider_content-active<?endif;?>">
      	<div class="main-slider_content-text-wrapper">
	        <div class="main-slider_content-text">
	            
	            
	            <p><?if($arItem["PROPERTY_TOP_TITLE_VALUE"]):?><span class="text-blue"><?=$arItem["PROPERTY_TOP_TITLE_VALUE"]?></span><br><?endif;?>
	            <?=$arItem['~PROPERTY_UNDER_TITLE_VALUE']?>
	            </p>
	            
	        </div>
	        <div class="main-slider_content-button">
	            <a target="_blanck" href="<?=$arItem['PROPERTY_LINK_VALUE']?>"><?=Loc::getMessage("MAINSLIDER_CUSTOM_MORE")?></a>
	        </div>
	        <div class="main-slider_content-description">
	            <p> <?=$arItem['PREVIEW_TEXT']?></p>
	        </div>
    	</div>
        <div class="main-slider_content-image">
            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>">
        </div>
      </div >

	<?}?>
    <div class="main-slider_navigation">
        <div class="main-slider_navigation-counter">
            <div class="main-slider_navigation-counter-current"><p>1</p></div>
            <p><?=count($result)?></p>
            <span class="main-slider_navigation-counter-line"></span>
            <span class="main-slider_navigation-counter-line-active"></span>
        </div>
        <div class="main-slider_navigation-arrows">
            <div class="main-slider_navigation-arrows-left">
                <svg width="15" height="6" viewBox="0 0 15 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5102 1L2 5H15" class="svg-arrow-left" stroke="#4272A9"/>
                </svg>
                <span></span>
            </div>
            <div class="main-slider_navigation-arrows-right">
                <svg width="16" height="7" viewBox="0 0 16 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.14286 6L14 1L5.01691e-07 1" class="svg-arrow-right" stroke="#4272A9"/>
                </svg>
                <span></span>
            </div>
        </div>
    </div>  
</div>

<?}?>
