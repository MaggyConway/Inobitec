<?php
$arrImages = array();
if($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'] > 0){
  $n = 0;
  foreach($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'] as $key => $val){
    $item = array();
    $item['src'] = CFile::GetPath($val);
    $item['descr'] = $arResult['PROPERTIES']['ATT_IMAGES']['DESCRIPTION'][$key];
    $arrImages[$n][] = $item;
    if($key > 0 && $key % 2 == 0)
      $n++;
  }
}
?>
<section class="vitaworks">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="vitaworks__logo">
      <div class="vitaworks__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <div class="vitaworks__info">
      <?=$arResult['~DETAIL_TEXT']?>
    </div>
    <?foreach($arrImages as $keyb =>  $blockImages):?>
    <div class="vitaworks__body">
      <?foreach($blockImages as $keyI => $image):?>
        <div class="vitaworks__body-icon"><img src="<?=$image['src']?>" alt="image_<?=$keyb.$keyI?>"></div>
      <?endforeach;?>
    </div>
    <?endforeach;?>
     <?=$arResult['PROPERTIES']['ATT_CUSTOMBLOCK']['~VALUE']['TEXT']?>
  </div>
</section>

