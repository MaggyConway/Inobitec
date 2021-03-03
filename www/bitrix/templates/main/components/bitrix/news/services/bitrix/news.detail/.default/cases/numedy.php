<?php
?>
<section class="numedy">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="numedy__logo">
      <div class="numedy__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <?if(count($arResult['PROPERTIES']['ATT_VIDEO']['VALUE'])>0):?>
    <div class="numedy__video">
      <?foreach($arResult['PROPERTIES']['ATT_VIDEO']['VALUE'] as $key => $val):?>
      
        <div class="numedy__video-item">
          <h2><?=$arResult['PROPERTIES']['ATT_VIDEO']['DESCRIPTION'][$key]?></h2><a class="numedy__video-item-js" data-fancybox href="<?=$val?>&amp;feature=emb_title;autoplay=1&amp;amp;rel=0&amp;amp;controls=0&amp;amp;showinfo=0"><img src="<?=($arResult['PROPERTIES']['ATT_VIDEO_PREVIEW']['VALUE'][$key])?CFile::GetPath($arResult['PROPERTIES']['ATT_VIDEO_PREVIEW']['VALUE'][$key]):SITE_TEMPLATE_PATH."/2020/img/electronic.png"?>" alt="video_<?=$key?>"><span></span></a>
        </div>
        
      <?endforeach;?>
    </div>
    <?endif;?>
  </div>
</section>