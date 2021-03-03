<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
global $USER;
$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
if(count($arResult["PERSON_TYPE"]) > 1)
{
	?>
    <?if(!$USER->IsAuthorized()):?>
    <button type="button" ng-controller="redirectController" class="btn-blue" ng-click="redirect('<?=SITE_DIR?>login/?returnUrl=<?=SITE_DIR?>cart/')"><?=GetMessage('CUSTOM_ORDER_AUTH_BUTTON');?></button>
    <br><br><br>
    <?endif;?>
    <div class="page_basket_personal_info_title"><span><?=GetMessage("CUSTOM_ORDER_PERSON_IINFO")?></span><i></i></div>
    <?
       if(!($USER->IsAuthorized() && $arUser["UF_USER_TYPE"] > 0)){
    ?>
    
    
    <div class="page_basket_personal_info_type js-tab_inputs">
      <?  foreach($arResult["PERSON_TYPE"] as $v){
        
      ?>
              <label class="radio_tab">
                <input type="radio" id="PERSON_TYPE_<?=$v["ID"]?>" name="PERSON_TYPE" value="<?=$v["ID"]?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()" />
                <div class="radio_tab_button 1"></div>
                <div class="radio_tab_text"><?= GetMessage("CUSTOM_ORDER_PERSON_TYPE_NAME_".$v["ID"])?></div>
              </label>  
      <?
           
        }
      ?>
        <input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>" />
      </div>
      <?
      }else{
        foreach($arResult["PERSON_TYPE"] as $v){
          if( ((SITE_ID == 's2' && $arUser["UF_USER_TYPE"] == $v["ID"]) ||
              (getPersonTypeFromUser($arUser["UF_USER_TYPE"]) == $v["ID"])) && $v["CHECKED"]=="Y" ){
      ?>
            <input style="display:none;" type="radio" id="PERSON_TYPE_<?=$v["ID"]?>" name="PERSON_TYPE" value="<?=$v["ID"]?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()" />
            <div class="radio_tab_text radio-disabled-info"><?= GetMessage("CUSTOM_ORDER_PERSON_TYPE_NAME_".$v["ID"])?></div>
            
      <?
          }}
      ?><input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>" /> <?      
            
            
        }
      ?>
        
	<?
}
else
{ /*
	if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
	{
		//for IE 8, problems with input hidden after ajax
		?>
		<span style="display:none;">
		<input type="text" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>" />
		<input type="text" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>" />
		</span>
		<?
	}
	else
	{
		foreach($arResult["PERSON_TYPE"] as $v)
		{
			?>
			<input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>" />
			<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>" />
			<?
		}
	}*/
}
?>