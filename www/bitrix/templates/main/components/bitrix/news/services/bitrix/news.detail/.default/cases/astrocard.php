<?php
//print_r($arResult);
?>
<section class="astrocard">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_BUTTOM")?></a>
    <div class="astrocard__logo">
      <div class="astrocard__logo-icon"><img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>"></div>
      <div class="astrocard__logo-info"><?=$arResult['~DETAIL_TEXT']?></div>
    </div>
    <?if(count($arResult['PROPERTIES']['ATT_FEATURES']['VALUE'])>0):?>
    <h2><?=GetMessage("PRPJECT_DETAILS")?></h2>
    <ul class="list astrocard__list">
      <?foreach($arResult['PROPERTIES']['ATT_FEATURES']['VALUE'] as $val):?>
        <li><?=$val?></li>
      <?endforeach;?>
    </ul>
    <?endif;?>
  </div>
</section>
