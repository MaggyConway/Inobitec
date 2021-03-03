<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?><?php
if(CModule::IncludeModule('iblock')){

        $preItems = CIBlockElement::GetList(
            array('sort' => 'asc', 'active_from' => 'desc'),
            array('IBLOCK_CODE' => 'sla_'.SITE_ID, "ACTIVE" => "Y"),
            false,
            false,
            array('ID', 'NAME')
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
 Array("CODE" => 'sla_'.SITE_ID)
);
$ar_res = $preIblock->Fetch();

?>
<section class="sla">
  <div class="wr"><a class="link link--blue link--back" href="<?=SITE_DIR?>services/">
      <svg width="19" height="8">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-link-arrow"></use>
      </svg><?=GetMessage("RETURN_SERVICE")?></a>
    <div class="section-header sla__section-header">
      <svg width="160" height="160">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-sla"></use>
      </svg>
      <h2><?=GetMessage("H2")?></h2>
      <p><?=$ar_res['DESCRIPTION']?></p>
    </div>
    <ul class="list sla__list">
      <?foreach($result as $key => $item):?>
        <li><?=$item['NAME']?></li>
      <?endforeach;?>
    </ul>
  </div>
</section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>