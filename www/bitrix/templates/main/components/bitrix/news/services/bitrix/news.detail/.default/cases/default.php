<?php

$arrIcons = array();
if($arResult['PROPERTIES']['ATT_ICONS']['VALUE'] > 0){
  $mediana = ceil(count($arResult['PROPERTIES']['ATT_ICONS']['VALUE'])/2);
  foreach($arResult['PROPERTIES']['ATT_ICONS']['VALUE'] as $key => $val){
    $item = array();
    $item['src'] = CFile::GetPath($val);
    $item['descr'] = $arResult['PROPERTIES']['ATT_ICONS']['DESCRIPTION'][$key];
     
    if( ($key+1) <= $mediana){
      
      $arrIcons['left'][] = $item;
    }else{
      $arrIcons['right'][] = $item;
    }
  }
}
?>
<section class="amycard">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="web__logo">
      <div class="numedy__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <div class="web__main-info"><?=$arResult['~DETAIL_TEXT']?></div>
    <?if(count($arResult['PROPERTIES']['ATT_FEATURES']['VALUE']) > 0):?>
    <br/>
    <ul class="list">
      <?foreach($arResult['PROPERTIES']['ATT_FEATURES']['VALUE'] as $val):?>
        <li><?=$val?></li>
      <?endforeach;?>
    </ul>
    <?endif;?>
    <div class="web__main">
      <div class="web__main-block">
        <?foreach($arrIcons['left'] as $key => $val):?>
          <div class="web__main-item">
            <div class="web__main-item-icon">
              <img src="<?=$val['src']?>"/>
            </div><span><?=$val['descr']?></span>
          </div>
        <?endforeach;?>
      </div>
      <div class="web__main-block">
        <?foreach($arrIcons['right'] as $key => $val):?>
          <div class="web__main-item">
            <div class="web__main-item-icon">
              <img src="<?=$val['src']?>"/>
            </div><span><?=$val['descr']?></span>
          </div>
        <?endforeach;?>
      </div>
    </div>
    <div class="numedy__video">
      <?foreach($arResult['PROPERTIES']['ATT_VIDEO']['VALUE'] as $key => $val):?>
        <div class="numedy__video-item">
          <h2><?=$arResult['PROPERTIES']['ATT_VIDEO']['DESCRIPTION'][$key]?></h2><a class="numedy__video-item-js" data-fancybox href="<?=$val?>&amp;feature=emb_title;autoplay=1&amp;amp;rel=0&amp;amp;controls=0&amp;amp;showinfo=0"><img src="<?=($arResult['PROPERTIES']['ATT_VIDEO_PREVIEW']['VALUE'][$key])?CFile::GetPath($arResult['PROPERTIES']['ATT_VIDEO_PREVIEW']['VALUE'][$key]):SITE_TEMPLATE_PATH."/2020/img/electronic.png"?>" alt="video_<?=$key?>"><span></span></a>
        </div>
        
      <?endforeach;?>
    </div>
    <?if(count($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'])>0):?>
      <br/>
      <?foreach($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'] as $key => $imageID):?>
        <div class="numedy-dm__process"><img src="<?=CFile::GetPath($imageID)?>" alt="image_<?=$key?>"></div>
      <?endforeach;?>
      <br/>  
    <?endif;?>
    <?=$arResult['PROPERTIES']['ATT_CUSTOMBLOCK']['~VALUE']['TEXT']?>
  </div>
</section>