<?php

?>
<section class="amycard">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="amycard__logo">
      <div class="amycard__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
    </div>
    <div class="amycard__description">
      <?=$arResult['~DETAIL_TEXT']?>
    </div>
    
      <?=$arResult['PROPERTIES']['ATT_CUSTOMBLOCK']['~VALUE']['TEXT']?>
    
  </div>
</section>

