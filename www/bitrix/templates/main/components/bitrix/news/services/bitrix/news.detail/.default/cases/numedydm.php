<?php
?>
<section class="numedy-dm">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/services.html">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="numedy-dm__logo">
      <div class="numedy-dm__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <div class="numedy-dm__info">
      <?=$arResult['~DETAIL_TEXT']?>
    </div>
    <?if(count($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'])>0):?>
      <?foreach($arResult['PROPERTIES']['ATT_IMAGES']['VALUE'] as $key => $imageID):?>
        <div class="numedy-dm__process"><img src="<?=CFile::GetPath($imageID)?>" alt="image_<?=$key?>"></div>
      <?endforeach;?>
    <?endif;?>
  </div>
</section>
