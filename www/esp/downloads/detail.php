<?
$newPageCSS = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobitec LLC provides distributives of its own products for downloading to employees, which works in health facilities: Inobitec DICOM-viewer, Inobitec DICOM-server");
$APPLICATION->SetPageProperty("keywords", "Download, install, DICOM, dicom, viewer, server, PACS, pacs, documentation, user's guide, Windows, Mac OS, Linux, 2D, 3D");
$APPLICATION->SetPageProperty("title", "Visor y Servidor DICOM de Inobitec para SO: Windows, Mac OS, Linux");
$APPLICATION->SetTitle("Versiones para descargar");
$no_f_links = true;
  use Bitrix\Main\Localization\Loc;
  Loc::loadMessages(__FILE__);
?>

<style>
  .download__description-case>p {
    margin-bottom: 15px;
  }
  .download__description-case>a {
    margin-bottom: 0;
    margin-top: 10px;
  }
</style>

<?
$winItems = [];
$macItems = [];
$linuxItems = [];

$winDistribNames = [];
$macDistribNames = [];
$linuxDistribNames = [];

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
      }

        //echo "<pre>"; var_dump($preItems); echo "</pre>";

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
	


		if($arItem = $result){
			
          $APPLICATION -> SetPageProperty('title', $arItem['NAME'].' / '.$MESS[SITE_ID]['DOWNLOAD_CENTER']);
          $previewPicture = CFile::GetFileArray($arItem['DETAIL_PICTURE']);


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
        <?if($arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT']):?>
        <div class="download__description">
            <div class="download__description-logo <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'] == 'viewer')?'download__description-logo-viewer':''?>">
                <img src="/verstka_new/buy_p/img/inobitec-web/inobitec-web-description-logo-1.svg" alt="">
            </div>
            <div class="download__description-case <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__description-case-'.$arItem['PROPERTY_NEW_DESIGN_VALUE']:''?>">
                <?=$arItem["~PROPERTY_ADD_DESCRIPTION_VALUE"]['TEXT']?>
            </div>
        </div>
        <?endif;?>



        <?
          //WINDOWS DISTRIBUTIVES
          
    if(CModule::IncludeModule('iblock')){
          if($arItem['PROPERTY_WIN_DISTR_VALUE'])
          {
            unset($arFiles);
            $сache = Bitrix\Main\Data\Cache::createInstance();

            if($сache -> initCache(36000, 'downloads_win_'.$arItem['ID'].'_'.SITE_ID)) { 

                $arFiles = $сache -> getVars(); 

            } elseif($сache -> startDataCache()) { 

                  $preFileDistribs = CIBlockElement::GetList(
                      array('sort' => 'asc', 'active_from' => 'desc'),
                      array('IBLOCK_CODE' => 'distribs_s1', "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_WIN_DISTR_VALUE']), // s1 - все файлы - на всех языках - из одного инфлоблока
                      false,
                      array(),
                      array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_SELECT_FILES')
                  );

                    while($distrib = $preFileDistribs -> GetNext()){

                        // echo "<pre>"; var_dump($distrib["NAME"]); echo "</pre>";
                      array_push($winDistribNames, $distrib["NAME"]);

                        foreach ($distrib['PROPERTY_SELECT_FILES_VALUE'] as $key => $file) {

                          $preFilesBlock = CIBlockElement::GetList(
                              array('sort' => 'asc', 'active_from' => 'desc'),
                              array('IBLOCK_CODE' => 'files', "ACTIVE" => "Y", "=ID" => $file),
                              false,
                              array(),
                              array('ID', 'NAME', 'PROPERTY_FILE', 'PROPERTY_LESS_FILENAME', 'PROPERTY_ABOUT')
                          );


                          while($selectedFile = $preFilesBlock -> GetNext()){

                              array_push($winItems, $selectedFile);

                              // echo "selectedFile - ";
                              // echo "<pre>"; var_dump($selectedFile); echo "</pre>";
                          }
                        }
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

                <p class="text-bold text-big"><?=$result["PROPERTY_WIN_TITLE_VALUE"]?></p>

                <? // echo "<pre>"; var_dump($result["PROPERTY_MAC_TITLE_VALUE"]); echo "</pre>"; ?>

                <? foreach ($winItems as $i => $winFile){ ?>
                      <a href="<?=$winFile['PROPERTY_FILE_VALUE']?>" target="_blank"><?=$winFile['PROPERTY_LESS_FILENAME_VALUE']?></a>                
                <?}?>
                
            </div>
        </div>
          
        <?
          } }
        ?>
      
      
        
        
        
      
      
      
      <?
        //MAC DISTRIBUTIVES
        //
    if(CModule::IncludeModule('iblock')){
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

              $preFileDistribs = CIBlockElement::GetList(
                  array('sort' => 'asc', 'active_from' => 'desc'),
                  array('IBLOCK_CODE' => 'distribs_s1', "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_MAC_DISTR_VALUE']),
                  false,
                  array(),
                  array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_SELECT_FILES')
              );

              while($distrib = $preFileDistribs -> GetNext()){

                array_push($macDistribNames, $distrib["NAME"]);

                  foreach ($distrib['PROPERTY_SELECT_FILES_VALUE'] as $key => $file) {

                      $preFilesBlock = CIBlockElement::GetList(
                          array('sort' => 'asc', 'active_from' => 'desc'),
                          array('IBLOCK_CODE' => 'files', "ACTIVE" => "Y", "=ID" => $file),
                          false,
                          array(),
                          array('ID', 'NAME', 'PROPERTY_FILE', 'PROPERTY_LESS_FILENAME', 'PROPERTY_ABOUT')
                      );


                      while($selectedFile = $preFilesBlock -> GetNext()){

                          array_push($macItems, $selectedFile);
                      }
                  }
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

                <p class="text-bold text-big"><?=$result["PROPERTY_MAC_TITLE_VALUE"]?></p>
                
                <? foreach ($macItems as $i => $macFile){ ?>
                      <a href="<?=$macFile['PROPERTY_FILE_VALUE']?>" target="_blank"><?=$macFile['PROPERTY_LESS_FILENAME_VALUE']?></a>                
                <?}?>              
            </div>
        </div>
        <?} }?>
      


    <?
        //LINUX DISTRIBUTIVES

if(CModule::IncludeModule('iblock')){

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
              $preFileDistribs = CIBlockElement::GetList(
                  array('sort' => 'asc', 'active_from' => 'desc'),
                  array('IBLOCK_CODE' => 'distribs_s1', "ACTIVE" => "Y", "=ID" => $arItem['PROPERTY_LINUX_DISTR_VALUE']),
                  false,
                  array(),
                  array('ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PROPERTY_SELECT_FILES')
              );


              while($distrib = $preFileDistribs -> GetNext()){

                array_push($linuxDistribNames, $distrib["NAME"]);

                  foreach ($distrib['PROPERTY_SELECT_FILES_VALUE'] as $key => $file) {

                      $preFilesBlock = CIBlockElement::GetList(
                          array('sort' => 'asc', 'active_from' => 'desc'),
                          array('IBLOCK_CODE' => 'files', "ACTIVE" => "Y", "=ID" => $file),
                          false,
                          array(),
                          array('ID', 'NAME', 'PROPERTY_FILE', 'PROPERTY_LESS_FILENAME', 'PROPERTY_ABOUT')
                      );


                      while($selectedFile = $preFilesBlock -> GetNext()){

                          array_push($linuxItems, $selectedFile);
                      }
                  }
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

                  <p class="text-bold text-big"><?=$result["PROPERTY_LINUX_TITLE_VALUE"]?></p>
                  
                  <? foreach ($linuxItems as $i => $linuxFile){ ?>
                        <a href="<?=$linuxFile['PROPERTY_FILE_VALUE']?>" target="_blank"><?=$linuxFile['PROPERTY_LESS_FILENAME_VALUE']?></a>                
                  <?}?> 
            </div>
        </div>
        <?} }?>


 <?if($arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      </div>
      <?endif;?>  
      
    <?if(!$arItem['PROPERTY_NEW_DESIGN_VALUE']):?> 
      </div>
    <?endif;?>  

    






 
    <? // MANUALS
    
        if($arItem['PROPERTY_MANUALS_VALUE'] || $arItem['PROPERTY_WEB_LINK_VALUE'])
            {
    ?>
    <div class="download__documents <?=($arItem['PROPERTY_NEW_DESIGN_VALUE'])?'download__documents-viewer':''?>">
        <h3><?=Loc::getMessage('DOWNLOADS_GET_ACCESS_GUIDES')?></h3>
        <ul>
          <? if ( count($arItem['PROPERTY_MANUALS_VALUE']) == 1 ) {

                $arItem['PROPERTY_MANUALS_VALUE'] = $arItem['PROPERTY_MANUALS_VALUE'][0];
                $manual = CFile::GetFileArray($arItem['PROPERTY_MANUALS_VALUE']);

                // echo "<pre>"; var_dump($manual); echo "</pre>";

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
          } else {
        
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