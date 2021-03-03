<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(CModule::IncludeModule('iblock')){

        $preItems = CIBlockElement::GetList(
            array('sort' => 'asc', 'active_from' => 'desc'),
            array('IBLOCK_CODE' => 'integration_'.SITE_ID, "ACTIVE" => "Y"),
            false,
            false,
            array('ID', 'NAME', 'DETAIL_TEXT')
        );

        if($preItems->result->num_rows && $preItems->result->num_rows > 0){

            while($arItem = $preItems -> GetNext()){
                $Items[] = $arItem;
            }

        }

        $result = $Items;
}

$preIblock = CIBlock::GetList(
 Array("SORT"=>"ASC"),
 Array("CODE" => 'integration_'.SITE_ID)
);
$ar_res = $preIblock->Fetch();



?>

<section class="integration">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_SERVICE")?></a>
    <div class="section-header integration__section-header">
      <svg width="160" height="160">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-integration"></use>
      </svg>
      <h2><?=GetMessage("H2")?></h2>
      <p><?=$ar_res['DESCRIPTION']?></p>
    </div>
    <div class="integration__accordions">
      <?foreach($result as $key => $item):?>
        <div class="accordion">
          <div class="accordion__header"><?=$item['NAME']?>
            <svg width="64" height="64">
              <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-accordion-arrow"></use>
            </svg>
          </div>
          <div class="accordion__content"> 
            <p><?=$item['~DETAIL_TEXT']?></p>
          </div>
        </div>
      <?endforeach;?>
     
    </div>
  </div>
</section>