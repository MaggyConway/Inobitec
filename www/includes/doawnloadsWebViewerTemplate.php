<?php
  use Bitrix\Main\Localization\Loc;
  Loc::loadMessages(__FILE__);

  $previewPicture = CFile::GetFileArray($arItem['DETAIL_PICTURE']);

  
  $distFirst = true;
?>
<section class="download" style="margin-top: 0px;">
    <a href="<?=SITE_DIR?>downloads/" class="download__back"><?=Loc::getMessage('DOWNLOADS_BACK')?></a>
    <div class="download__head">
        <div class="download__head-logo">
            <img src="<?=$previewPicture['SRC']?>" alt="">
        </div>
        <div class="download__head-case">
            <h2><?=$arItem["NAME"]?></h2>
            <?=$arItem["DETAIL_TEXT"]?>
        </div>
    </div>
    <div class="download__main">
        <div class="download__description">
            <div class="download__description-logo">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-1.svg" alt="">
            </div>
            <div class="download__description-case">
                <?=$arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT']?>
            </div>
        </div>
        
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
            <div class="download__description-logo">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-2.svg" alt="">
            </div>
            <div class="download__description-case">
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
            <div class="download__description-logo">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-3.svg" alt="">
            </div>
            <div class="download__description-case">
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
            <div class="download__description-logo">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/platform-mac-ico.jpg" alt="">
            </div>
            <div class="download__description-case">
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
    </div>
    <?
        if($arItem['PROPERTY_MANUALS_VALUE'] || $arItem['PROPERTY_WEB_LINK_VALUE'])
        {
    ?>
    <div class="download__documents">
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
    <?
        }
    ?>
</section>

