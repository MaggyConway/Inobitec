<?

unset($result);
unset($сache);
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
        
$сache = Bitrix\Main\Data\Cache::createInstance();

if($сache -> initCache(36000, 'mp-solutions'.SITE_ID)) 
{ 
    $result = $сache -> getVars(); 
} 
elseif($сache -> startDataCache())
{ 
	if(CModule::IncludeModule('iblock')){

		$preItems = CIBlockElement::GetList(
			array('sort' => 'asc', 'active_from' => 'desc'),
			array('IBLOCK_CODE' => 'products_'.SITE_ID, "ACTIVE" => "Y"),
			false,
			false,
			array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_CAPABILITIES_LIST', 'PROPERTY_VIDEO_LINK', 'PROPERTY_PDF_FILE', 'PROPERTY_MAIN_ICON', 'PROPERTY_FREE_HTML', 'PROPERTY_INSERT_GOODS')
		);

		if($preItems->result->num_rows && $preItems->result->num_rows > 0){

		  $in_array = array();
		  while($arItem = $preItems->GetNext()){
				if(in_array($arItem["ID"], $in_array)){ continue; }
				$in_array[$arItem["ID"]] = $arItem["ID"];
				
				if($arItem['PREVIEW_PICTURE']){
					$arItem['PREVIEW_PICTURE'] = CFile::GetFileArray($arItem['PREVIEW_PICTURE']);
				}
                if($arItem['DETAIL_PICTURE']){
                  $arItem['DETAIL_PICTURE'] = CFile::GetFileArray($arItem['DETAIL_PICTURE']);
                }
                if($arItem['PROPERTY_INSERT_GOODS_VALUE']){
                  $allInsertItems = array();
                  $insertItems = CIBlockElement::GetList(
                      array('sort' => 'asc', 'active_from' => 'desc'),
                      array('IBLOCK_CODE' => 'products_modules_'.SITE_ID, "ACTIVE" => "Y", "IBLOCK_SECTION_ID" => $arItem['PROPERTY_INSERT_GOODS_VALUE']),
                      false,
                      false,
                      array('ID', 'NAME', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_MAIN_ICON', 'PROPERTY_CAPABILITIES_LIST')
                  );
				  
				  $in_array2 = array();
                  while($arInsertItem = $insertItems->GetNext()){
					if(in_array($arInsertItem["ID"], $in_array2)){ continue; }
					$in_array2[$arInsertItem["ID"]] = $arInsertItem["ID"];
					
                    if($arInsertItem['PREVIEW_PICTURE']){
                        $arInsertItem['PREVIEW_PICTURE'] = CFile::GetFileArray($arInsertItem['PREVIEW_PICTURE']);
                    }
                    if($arInsertItem['DETAIL_PICTURE']){
                      $arInsertItem['DETAIL_PICTURE'] = CFile::GetFileArray($arInsertItem['DETAIL_PICTURE']);
                    }
                    if($arInsertItem['PROPERTY_MAIN_ICON_VALUE']){
                      $arInsertItem['PROPERTY_MAIN_ICON_VALUE'] = CFile::GetFileArray($arInsertItem['PROPERTY_MAIN_ICON_VALUE']);
                    }
                    $allInsertItems[] = $arInsertItem;
                    
                  }
                  $arItem['PROPERTY_INSERT_GOODS_VALUE'] = $allInsertItems;
                }
				
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
  <div class="wr">
    <section class="products" style="margin-top: 0px;">      
      <?
		foreach ($result as $keyItem => $arItem) {
			$ico = CFIle::GetFileArray($arItem['PROPERTY_MAIN_ICON_VALUE']);
            $pieces = array_chunk($arItem['PROPERTY_CAPABILITIES_LIST_VALUE'], ceil(count($arItem['PROPERTY_CAPABILITIES_LIST_VALUE']) / 2));
      ?>
          <div class="product">
            <div class="product__logo">
                <img src="<?=$ico['SRC']?>" alt="<?=$arItem['NAME']?>" data-open-case="case-<?=$keyItem+1?>">
            </div>
            <div class="product__row">
                <p class="product__text"><?=$arItem['~PROPERTY_FREE_HTML_VALUE']['TEXT']?></p>
                <button type="button" class="btn-blue" data-open-case="case-<?=$keyItem+1?>"><?=$MESS[SITE_ID]['MORE']?></button>
            </div>

            <div class="product-description hidden-case" data-case-name="case-<?=$keyItem+1?>">
                <div class="hidden-case__container">
                  <?php if(count($pieces[0]) > 0 || count($pieces[1]) > 0):?>
                    <div class="description-options product-description__description-options">
                      <?php if(count($pieces[0]) > 0):?>
                        <div class="description-options__item">
                            <ul class="description-options__column">
                              <?foreach($pieces[0] as $key => $value):?>
                              <? $icon = CFile::GetFileArray($value);?>
                                <li class="description-options__option">
                                    <img src="<?=$icon['SRC']?>" alt="">
                                    <p><?=$icon['DESCRIPTION']?></p>
                                </li>
                              <?endforeach;?>  
                            </ul>
                        </div>
                      <?php endif;?>
                      <?php if(count($pieces[1]) > 0):?>
                        <div class="description-options__item">
                            <ul class="description-options__column">
                                <?foreach($pieces[1] as $key => $value):?>
                                  <? $icon = CFile::GetFileArray($value);?>
                                    <li class="description-options__option">
                                        <img src="<?=$icon['SRC']?>" alt="">
                                        <p><?=$icon['DESCRIPTION']?></p>
                                    </li>
                                  <?endforeach;?>
                            </ul>
                        </div>
                      <?php endif;?>
                    </div>
                  <?php endif;?>
                  
                <?php if($arItem['PROPERTY_INSERT_GOODS_VALUE']):?>
                 
                  <div class="product-modules">
                     <?foreach($arItem['PROPERTY_INSERT_GOODS_VALUE'] as $keyModule => $moduleVal):?>
                      <div class="product-module">
                          <div class="product-module__head">
                              <div class="product-module__module-name" data-open="<?=$MESS[SITE_ID]['MORE']?>" data-close="<?=$MESS[SITE_ID]['LESS']?>" data-submodule-toggle="case-<?=$keyItem+1?>.<?=$keyModule+1?>">
                                  <img src="<?=$moduleVal['PROPERTY_MAIN_ICON_VALUE']['SRC']?>" alt="">
                                  <p><?=$moduleVal['NAME']?></p>
                              </div>
                              <div class="hidden-case__close">
                                  <button type="button" data-open="<?=$MESS[SITE_ID]['MORE']?>" data-close="<?$MESS[SITE_ID]['LESS']?>" data-submodule-toggle="case-<?=$keyItem+1?>.<?=$keyModule+1?>" class="hidden-case__close-desc">
                                      <?=$MESS[SITE_ID]['MORE']?>
                                  </button>
                                  <button type="button" data-submodule-toggle="case-<?=$keyItem+1?>.<?=$keyModule+1?>" class="hidden-case__close-mobile">
                                      <span></span>
                                      <span></span>
                                  </button>
                              </div>
                          </div>
                          <div class="hidden-case" data-submodule-name="case-<?=$keyItem+1?>.<?=$keyModule+1?>">
                              <div class="hidden-case__container" data-count="<?=sizeof($moduleVal['PROPERTY_CAPABILITIES_LIST_VALUE'])?>">
                                  <ul class="product-module__description">
                                    <?
									  if(sizeof($moduleVal['PROPERTY_CAPABILITIES_LIST_VALUE'])<=1){
										  
											$result_capabilities_list = array();
											if($сache->initCache((36000*24), 'capabilities_list'.SITE_ID.$moduleVal['ID'])) 
											{
												$result_capabilities_list = $сache->getVars(); 
											}
											elseif($сache->startDataCache())
											{
												$load_block = 40;
												if(SITE_ID == 's2'){ $load_block = 41; } //eng
												if(SITE_ID == 's3'){ $load_block = 69; } //fr
												if(SITE_ID == 's4'){ $load_block = 118; } //de
												if(SITE_ID == 's5'){ $load_block = 161; } //es
												
												$res = CIBlockElement::GetList(
													array("SORT"=>"ASC"), 
													array("IBLOCK_ID" => $load_block, "ACTIVE" => "Y", "ID" => $moduleVal['ID']), 
													false, 
													false, 
													array("ID", "IBLOCK_ID")
												);
												$arr2 = array();
												while ($arr = $res->GetNextElement()) {
													$fields = $arr->GetFields(); 
													$arr2[$fields['ID']] = $arr->GetProperty('CAPABILITIES_LIST');
												}
												foreach($arr2 as $id => $item){
													foreach($item['VALUE'] as $num => $v){
														$result_capabilities_list[] = $v;

													}
												}
												$сache->endDataCache($result_capabilities_list);
											}
											foreach($result_capabilities_list as $v){
												?>
												<li><?=$v?></li>
												<?
											}
									  }
									  else{
									?>
									<?
									foreach($moduleVal['PROPERTY_CAPABILITIES_LIST_VALUE'] as $keyModOpt => $keyModVal):?>
                                      <li><?=$keyModVal?></li>
                                    <?endforeach;?>
									<?}?>
                                  </ul>
                                  <div class="product-module__row">
                                      <img src="<?=$moduleVal['DETAIL_PICTURE']['SRC']?>" alt="">
                                  </div>
                              </div>
                          </div>
                      </div>
                    <?endforeach;?>
                  </div>
                <?php endif;?>  
                  
                  
                    <div class="description-links product-description__description-links">
                      <?foreach( $arItem['PROPERTY_VIDEO_LINK_VALUE'] as $key => $value):?>
                        <a target="_blnack" href="<?=$value?>"><?=$arItem['PROPERTY_VIDEO_LINK_DESCRIPTION'][$key]?></a>
                      <?endforeach;?>  
                      <?if($arItem['PROPERTY_PDF_FILE_VALUE']):?>  
                        <?php $pdfFile = CFile::GetFileArray($arItem['PROPERTY_PDF_FILE_VALUE']);?>
                        <a target="_blnack" href="<?=$pdfFile['SRC']?>"><?=Loc::getMessage('MP_SOLUTIONS_PDF')?></a>
                      <?endif;?>
                    </div>
                    
                  <?if($arItem['DETAIL_PICTURE']):?>
                    <div class="description-image">
                        <img src="<?=$arItem['DETAIL_PICTURE']['SRC']?>" alt="inobitec viewer lite photo">
                    </div>
                  <?endif;?>

                    <div class="hidden-case__close">
                        <button type="button" data-close-case="case-<?=$keyItem+1?>"><?=$MESS[SITE_ID]['LESS']?></button>
                    </div>
                </div>
            </div>
          </div>

      <?
        }
      ?>
    </section>
  </div>
<?
	}
?>