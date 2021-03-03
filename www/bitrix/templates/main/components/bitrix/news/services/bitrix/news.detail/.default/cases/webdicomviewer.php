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
<section class="web">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="web__logo">
      <div class="web__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <div class="web__main-info"><?=$arResult['~DETAIL_TEXT']?></div>
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
    <?foreach($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'] as $key => $imageID):?>
      <div class="web__snap"><img src="<?=CFile::GetPath($imageID)?>" alt="image_<?=$key?>"></div>
    <?endforeach;?>
    
  </div>
</section>

