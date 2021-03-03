<?
$newPageCSS = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Компания Инобитек предоставляет сотрудникам учреждений здравоохранения дистрибутивы собственных тиражных продуктов для загрузки: Инобитек DICOM-просмотрщик (Inobitec DICOM-viewer), Инобитек DICOM-сервер (Inobitec DICOM-server)");
$APPLICATION->SetPageProperty("keywords", "Скачать, загрузить, установить, DICOM, dicom, просмотрщик, просмотровщик, viewer, сервер, server, PACS, pacs, документация, руководство пользователя, Windows, Mac OS, Linux, 2D, 3D");
$APPLICATION->SetPageProperty("title", "Инобитек DICOM-просмотрщик и сервер для ОС: Windows, Mac OS, Linux");
$APPLICATION->SetTitle("Версии для загрузки");
$no_f_links = true;
  use Bitrix\Main\Localization\Loc;
  Loc::loadMessages(__FILE__);
?>
<?
	if($_GET["CODE"]){

		$сache = Bitrix\Main\Data\Cache::createInstance();

		if($сache -> initCache(36000, 'downloads_detail_'.SITE_ID)) 
		{ 
		    $result = $сache -> getVars(); 
		} 
		elseif($сache -> startDataCache())
		{ 
			if(CModule::IncludeModule('iblock')){

				$preItems = CIBlockElement::GetList(
					array('sort' => 'asc', 'active_from' => 'desc'),
					array('IBLOCK_CODE' => 'downloads_'.SITE_ID, "ACTIVE" => "Y", "CODE" => $_GET["CODE"]),
					false,
					array('nTopCpunt' => 1),
					array('ID', 'NAME', 'DETAIL_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'PROPERTY_FORMATED_NAME', 'PROPERTY_WIN_TITLE', 'PROPERTY_WIN_DISTR', 'PROPERTY_LINUX_TITLE', 'PROPERTY_LINUX_DISTR', 'PROPERTY_MAC_TITLE', 'PROPERTY_MAC_DISTR', 'PROPERTY_MANUALS', 'PROPERTY_WEB_LINK', 'PROPERTY_NEW_DESIGN', 'PROPERTY_ADD_DESCRIPTION', 'PROPERTY_ADD_DISTR')
				);

				if($preItems->result->num_rows && $preItems->result->num_rows > 0){

					if($arItem = $preItems -> GetNext()){
						$result = $arItem;
					}

				}

			    if ($isInvalid) 
			    { 
			        $cache->abortDataCache(); 
			    } 

			    $сache->endDataCache($result); 
			}
		}
	}
  
	 
?>



	<?


		if($arItem = $result){
          $APPLICATION -> SetPageProperty('title', $arItem['NAME'].' / '.$MESS[SITE_ID]['DOWNLOAD_CENTER']);
          $previewPicture = CFile::GetFileArray($arItem['DETAIL_PICTURE']);
          $distFirst = true;  
         /* switch($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 1){
            case 1:
              
              require($_SERVER["DOCUMENT_ROOT"]."/includes/doawnloadsWebViewerTemplate.php");
              break;
            
            default:*/

			

			//$previewPicture = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>330, 'height'=>250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
	?>
		<section class="download <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download-viewer':''?>" style="margin-top: 0px;">
    <a href="<?=SITE_DIR?>downloads/" class="download__back <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download_back-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>"><?=Loc::getMessage('DOWNLOADS_BACK')?></a>
    <div class="download__head <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download_head-viewer':''?>">
        <div class="download__head-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download_head-logo_viewer':''?>">
            <img src="<?=$previewPicture['SRC']?>" alt="">
        </div>
        <div class="download__head-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download_head-case__'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
            <h2><?=$arItem["NAME"]?></h2>
            <?=$arItem["DETAIL_TEXT"]?>
        </div>
    </div>
    <div class="download__main <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__main-viewer':''?>">
      <?if($arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      <div class="download__main-description">
      <?endif;?>
        <?if($arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT'] || $arItem['PROPERTY_ADD_DISTR_VALUE']):?>
        <div class="download__description">
            <div class="download__description-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download__description-logo-viewer':''?>">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-1.svg" alt="">
            </div>
            <div class="download__description-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__description-case-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
              <?if($arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT']):?>
                <?=$arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT']?>
                <br/>
              <?endif;?>
              <?  //ADD DISTRIBUTIVES
                  if($arItem['PROPERTY_ADD_DISTR_VALUE'])
                  {
                    unset($arFiles);
                    $сache = Bitrix\Main\Data\Cache::createInstance();

                    if($сache -> initCache(36000, 'downloads_add_'.$arItem['ID'].'_'.SITE_ID)) 
                    { 
                        $arFiles = $сache -> getVars(); 
                    } 
                    elseif($сache -> startDataCache())
                    { 

                        $preFileBlock = CIBlockElement::GetList(
                            array('sort' => 'asc', 'active_from' => 'desc'),
                            array('IBLOCK_CODE' => 'distribs_'.SITE_ID, "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_ADD_DISTR_VALUE']),
                            false,
                            array(),
                            array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_FILES', 'PROPERTY_NAME_DESCRIPTION')
                        );

                        $i = 0;

                        while($fileBlock = $preFileBlock -> GetNext()){



                            if($fileBlock['PREVIEW_TEXT']){
                                $arFiles[$i]['TITLE'] = $fileBlock["PREVIEW_TEXT"];
                            }else{
                                $arFiles[$i]['TITLE'] = $fileBlock["NAME"];
                            }

                            $arFiles[$i]['ID'] = $fileBlock['ID'];

                            if($fileBlock['DETAIL_TEXT']){
                                $arFiles[$i]['DESCRIPTION'] = $fileBlock["DETAIL_TEXT"];
                            }

                            $j = 0;

                            foreach ($fileBlock['PROPERTY_FILES_VALUE'] as $key => $file) {

                                if($fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] && $fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] == 'Y'){
                                    $fileName = $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j];

                                    $arFiles[$i]['ITEMS'][$j] = array(
                                        'VALUE' => $file,
                                        'DESCRIPTION' => '',
                                        'NAME' => $fileName
                                    );
                                }else{
                                    $fileName = explode('/', $file);
                                    $fileName = $fileName[sizeof($fileName) - 1];

                                    $arFiles[$i]['ITEMS'][$j] = array(
                                        'VALUE' => $file,
                                        'DESCRIPTION' => $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j],
                                        'NAME' => $fileName
                                    );
                                }
                                $j++;
                            }
                            $i++;
                        }

                        if ($isInvalid) 
                        { 
                            $cache->abortDataCache(); 
                        } 

                        $сache->endDataCache($arFiles);
                    }

                ?>

                
                        <?foreach ($arFiles as $i => $arFile){?>
                          <p class="text-bold text-big"><?=$arFile['TITLE']?></p>
                          <?foreach ($arFile['ITEMS'] as $j => $curFile) {
                          ?>

                          <a href="<?=$curFile['VALUE']?>"><?=$curFile['NAME']?></a>
                          <?}?>

                          <?if($arFile['DESCRIPTION']){?>
                          <a class="release_info" data-id="<?=$arFile['ID']?>" href="#"><?=$MESS[SITE_ID]['RELEASE_MORE']?></a>
                          <?}?>

                        <?}?>


                <?
                  }
                ?>
                
            </div>
        </div>
        <?endif;?>
        <?
          //WINDOWS DISTRIBUTIVES
          if($arItem['PROPERTY_WIN_DISTR_VALUE'])
          {
            unset($arFiles);
            $сache = Bitrix\Main\Data\Cache::createInstance();

            if($сache -> initCache(36000, 'downloads_win_'.$arItem['ID'].'_'.SITE_ID)) 
            { 
                $arFiles = $сache -> getVars(); 
            } 
            elseif($сache -> startDataCache())
            { 

                $preFileBlock = CIBlockElement::GetList(
                    array('sort' => 'asc', 'active_from' => 'desc'),
                    array('IBLOCK_CODE' => 'distribs_'.SITE_ID, "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_WIN_DISTR_VALUE']),
                    false,
                    array(),
                    array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_FILES', 'PROPERTY_NAME_DESCRIPTION')
                );

                $i = 0;

                while($fileBlock = $preFileBlock -> GetNext()){



                    if($fileBlock['PREVIEW_TEXT']){
                        $arFiles[$i]['TITLE'] = $fileBlock["PREVIEW_TEXT"];
                    }else{
                        $arFiles[$i]['TITLE'] = $fileBlock["NAME"];
                    }

                    $arFiles[$i]['ID'] = $fileBlock['ID'];

                    if($fileBlock['DETAIL_TEXT']){
                        $arFiles[$i]['DESCRIPTION'] = $fileBlock["DETAIL_TEXT"];
                    }

                    $j = 0;

                    foreach ($fileBlock['PROPERTY_FILES_VALUE'] as $key => $file) {

                        if($fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] && $fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] == 'Y'){
                            $fileName = $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j];

                            $arFiles[$i]['ITEMS'][$j] = array(
                                'VALUE' => $file,
                                'DESCRIPTION' => '',
                                'NAME' => $fileName
                            );
                        }else{
                            $fileName = explode('/', $file);
                            $fileName = $fileName[sizeof($fileName) - 1];

                            $arFiles[$i]['ITEMS'][$j] = array(
                                'VALUE' => $file,
                                'DESCRIPTION' => $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j],
                                'NAME' => $fileName
                            );
                        }
                        $j++;
                    }
                    $i++;
                }

                if ($isInvalid) 
                { 
                    $cache->abortDataCache(); 
                } 

                $сache->endDataCache($arFiles);
            }
						
        ?>
           
        <div class="download__description">
            <div class="download__description-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download__description-logo-viewer':''?>">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-2.svg" alt="">
            </div>
            <div class="download__description-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__description-case-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
                <?if($distFirst){ $distFirst=false;?><h3><?Loc::getMessage('DOWNLOADS_GET_ACCESS_DIST')?></h3><?}?>
                <?foreach ($arFiles as $i => $arFile){?>
                  <p class="text-bold text-big"><?=$arFile['TITLE']?></p>
                  <?foreach ($arFile['ITEMS'] as $j => $curFile) {
                  ?>

                  <a href="<?=$curFile['VALUE']?>"><?=$curFile['NAME']?></a>
                  <?}?>

                  <?if($arFile['DESCRIPTION']){?>
                  <a class="release_info" data-id="<?=$arFile['ID']?>" href="#"><?=$MESS[SITE_ID]['RELEASE_MORE']?></a>
                  <?}?>
                
                <?}?>
                
            </div>
        </div>
          
        <?
          }
        ?>
      
      
        
        
        <?
        //LINUX DISTRIBUTIVES
        unset($arFiles);
        if($arItem['PROPERTY_LINUX_DISTR_VALUE'])
        {
          $сache = Bitrix\Main\Data\Cache::createInstance();

          if($сache -> initCache(36000, 'downloads_linux_'.$arItem['ID'].'_'.SITE_ID)) 
          { 
              $arFiles = $сache -> getVars(); 
          } 
          elseif($сache -> startDataCache())
          { 

              $preFileBlock = CIBlockElement::GetList(
                  array('sort' => 'asc', 'active_from' => 'desc'),
                  array('IBLOCK_CODE' => 'distribs_'.SITE_ID, "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_LINUX_DISTR_VALUE']),
                  false,
                  array(),
                  array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_FILES', 'PROPERTY_NAME_DESCRIPTION')
              );

              $i = 0;

              while($fileBlock = $preFileBlock -> GetNext()){

                  if($fileBlock['PREVIEW_TEXT']){
                      $arFiles[$i]['TITLE'] = $fileBlock["PREVIEW_TEXT"];
                  }else{
                      $arFiles[$i]['TITLE'] = $fileBlock["NAME"];
                  }

                  $arFiles[$i]['ID'] = $fileBlock['ID'];

                      if($fileBlock['DETAIL_TEXT']){
                          $arFiles[$i]['DESCRIPTION'] = $fileBlock["DETAIL_TEXT"];
                      }

                  $j = 0;

                  foreach ($fileBlock['PROPERTY_FILES_VALUE'] as $key => $file) {

                      if($fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] && $fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] == 'Y'){
                          $fileName = $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j];

                          $arFiles[$i]['ITEMS'][$j] = array(
                              'VALUE' => $file,
                              'DESCRIPTION' => '',
                              'NAME' => $fileName
                          );
                      }else{
                          $fileName = explode('/', $file);
                          $fileName = $fileName[sizeof($fileName) - 1];

                          $arFiles[$i]['ITEMS'][$j] = array(
                              'VALUE' => $file,
                              'DESCRIPTION' => $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j],
                              'NAME' => $fileName
                          );
                      }

                      $j++;
                  }
                  $i++;
              }

              if ($isInvalid) 
              { 
                  $cache->abortDataCache(); 
              } 

              $сache->endDataCache($arFiles); 
          }
        ?>    
          
        <div class="download__description">
            <div class="download__description-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download__description-logo-viewer':''?>">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-3.svg" alt="">
            </div>
            <div class="download__description-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__description-case-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
                <?if($distFirst){ $distFirst=false;?><h3><?Loc::getMessage('DOWNLOADS_GET_ACCESS_DIST')?></h3><?}?>
                <?foreach ($arFiles as $i => $arFile){?>
                  <p class="text-bold text-big"><?=$arFile['TITLE']?></p>
                  <?foreach ($arFile['ITEMS'] as $j => $curFile) {
                  ?>

                  <a href="<?=$curFile['VALUE']?>"><?=$curFile['NAME']?></a>
                  <?}?>

                  <?if($arFile['DESCRIPTION']){?>
                  <a class="release_info" data-id="<?=$arFile['ID']?>" href="#"><?=$MESS[SITE_ID]['RELEASE_MORE']?></a>
                  <?}?>
                
                <?}?>
                
            </div>
        </div>
        <?} ?>
      
      
      
      <?
        //MAC DISTRIBUTIVES
        unset($arFiles);
        if($arItem['PROPERTY_MAC_DISTR_VALUE'])
        {
          
          $сache = Bitrix\Main\Data\Cache::createInstance();
          if($сache -> initCache(36000, 'downloads_mac_'.$arItem['ID'].'_'.SITE_ID)) 
          { 
              $arFiles = $сache -> getVars(); 
          } 
          elseif($сache -> startDataCache())
          { 

              $preFileBlock = CIBlockElement::GetList(
                  array('sort' => 'asc', 'active_from' => 'desc'),
                  array('IBLOCK_CODE' => 'distribs_'.SITE_ID, "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_MAC_DISTR_VALUE']),
                  false,
                  array(),
                  array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_FILES', 'PROPERTY_NAME_DESCRIPTION')
              );

              $i = 0;

              while($fileBlock = $preFileBlock -> GetNext()){

                  if($fileBlock['PREVIEW_TEXT']){
                      $arFiles[$i]['TITLE'] = $fileBlock["PREVIEW_TEXT"];
                  }else{
                      $arFiles[$i]['TITLE'] = $fileBlock["NAME"];
                  }

                  $arFiles[$i]['ID'] = $fileBlock['ID'];

                  if($fileBlock['DETAIL_TEXT']){
                      $arFiles[$i]['DESCRIPTION'] = $fileBlock["DETAIL_TEXT"];
                  }

                  $j = 0;

                  foreach ($fileBlock['PROPERTY_FILES_VALUE'] as $key => $file) {

                      $fileName = explode('/', $file);
                      $fileName = $fileName[sizeof($fileName) - 1];

                      if($fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] && $fileBlock['PROPERTY_NAME_DESCRIPTION_VALUE'] == 'Y'){
                          $fileName = $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j];

                          $arFiles[$i]['ITEMS'][$j] = array(
                              'VALUE' => $file,
                              'DESCRIPTION' => '',
                              'NAME' => $fileName
                          );
                      }else{
                          $fileName = explode('/', $file);
                          $fileName = $fileName[sizeof($fileName) - 1];

                          $arFiles[$i]['ITEMS'][$j] = array(
                              'VALUE' => $file,
                              'DESCRIPTION' => $fileBlock['PROPERTY_FILES_DESCRIPTION'][$j],
                              'NAME' => $fileName
                          );
                      }

                      $j++;
                  }
                  $i++;
              }

              if ($isInvalid) 
              { 
                  $cache->abortDataCache(); 
              } 

              $сache->endDataCache($arFiles); 
          }
        ?>    
          
        <div class="download__description">
            <div class="download__description-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download__description-logo-viewer':''?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/platform-mac-ico.jpg" alt="">
            </div>
            <div class="download__description-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__description-case-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
                <?if($distFirst){ $distFirst=false;?><h3><?Loc::getMessage('DOWNLOADS_GET_ACCESS_DIST')?></h3><?}?>
                <?foreach ($arFiles as $i => $arFile){?>
                  <p class="text-bold text-big"><?=$arFile['TITLE']?></p>
                  <?foreach ($arFile['ITEMS'] as $j => $curFile) {
                  ?>

                  <a href="<?=$curFile['VALUE']?>"><?=$curFile['NAME']?></a>
                  <?}?>

                  <?if($arFile['DESCRIPTION']){?>
                  <a class="release_info" data-id="<?=$arFile['ID']?>" href="#"><?=$MESS[SITE_ID]['RELEASE_MORE']?></a>
                  <?}?>
                
                <?}?>
                
            </div>
        </div>
        <?} ?>
      <?if($arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      </div>
      <?endif;?>  
      
    <?if(!$arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      </div>
    <?endif;?>    
    
    <?
        if($arItem['PROPERTY_MANUALS_VALUE'] || $arItem['PROPERTY_WEB_LINK_VALUE'])
        {
    ?>
    <div class="download__documents <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__documents-viewer':''?>">
        <h3><?=Loc::getMessage('DOWNLOADS_GET_ACCESS_GUIDES')?></h3>
        <ul>
          <?
            foreach ($arItem['PROPERTY_MANUALS_VALUE'] as $key => $manual){
                $manual = CFile::GetFileArray($manual);
                switch ($manual["CONTENT_TYPE"]) {
                    case 'application/pdf':
                        $item_class = 'pdf';
                        break;
                    default:
                        $item_class = 'def';
                        break;
                }

                if($manual){
                ?>
                  <li class="download__documents-<?=$item_class?>"><a target="_blank" href="<?=$manual['SRC']?>"><?=$manual['DESCRIPTION']?></a></li>
                <?
                }
            }
            foreach ($arItem['PROPERTY_WEB_LINK_VALUE'] as $key => $manual_link){

                    if($arItem['PROPERTY_WEB_LINK_DESCRIPTION']){
                        $name = $arItem['PROPERTY_WEB_LINK_DESCRIPTION'][$key];
                    }else{
                        $name = Loc::getMessage('DOWNLOADS_DEFAULT_LINK');
                    }
            ?>
                <li class="download__documents-web"><a target="_blank" href="<?=$manual_link?>"><?=$name?></a></li>
            <?
                }
          ?>
        </ul>
    </div>
    <?if($arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      </div>
    <?endif;?>  
    <?
        }
    ?>
</section>

<?      //break;
      //}
    }else{
      localRedirect('/404/');
    }
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>