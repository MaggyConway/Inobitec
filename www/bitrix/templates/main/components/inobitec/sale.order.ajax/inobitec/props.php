<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
if(count($arResult["ORDER_PROP"]["USER_PROPS_Y"]) > 0)
  $users_props = array_merge($arResult["ORDER_PROP"]["USER_PROPS_N"],$arResult["ORDER_PROP"]["USER_PROPS_Y"]);
else
  $users_props = $arResult["ORDER_PROP"]["USER_PROPS_N"];
$auth_users_props = array();
$arrAuthProps = array("EMAIL", "WORK_PHONE", "PASS", "PASS_CONF");
$arrRegProps = array("PASS", "PASS_CONF");
$arrIgnoreProps = array("OFERTA_AGREE");
if(SITE_ID == "s1"){
  $arrIgnoreProps[] = "SWIFT";
}elseif(SITE_ID == "s2"){
  $arrIgnoreProps[] = "BIK";
  $arrIgnoreProps[] = "KOR_ACC";
}
//print_r($arResult["ORDER_PROP"]["USER_PROPS_Y"]);
usort($users_props, function($a, $b){
  return ($a['SORT'] - $b['SORT']);
});
$personTypeID = false;
foreach($arResult["PERSON_TYPE"] as $v){
  if($v["CHECKED"]=="Y"){
    $personTypeID = $v["ID"];
  }
}

/*
$firstBlock = $secondBlock = $thirdBlock = $fourtBlock = $fifthBlock = array();
if(($users_props[0]["PROPS_GROUP_ID"] == 3 || $users_props[0]["PROPS_GROUP_ID"] == 1)){
  $firstBlock[] = $users_props[0];
  unset($users_props[0]);
}*/
foreach($users_props as $key => $prop){
  if(in_array($prop["CODE"], $arrAuthProps)){
    if(!$USER->IsAuthorized() || !in_array($prop["CODE"], $arrRegProps)){
      $auth_users_props[] = $prop;
    }
    unset($users_props[$key]);
  }
  if(in_array($prop["CODE"], $arrIgnoreProps))
    unset($users_props[$key]);
}




?>
<div class="section" style="display:none;">
<h4><?=GetMessage("SOA_TEMPL_PROP_INFO")?></h4>
	<?
	$bHideProps = true;
    
	if (is_array($arResult["ORDER_PROP"]["USER_PROFILES"]) && !empty($arResult["ORDER_PROP"]["USER_PROFILES"])):
		if ($arParams["ALLOW_NEW_PROFILE"] == "Y"):
		?>
			<div class="bx_block r1x3">
				<?=GetMessage("SOA_TEMPL_PROP_CHOOSE")?>
			</div>
			<div class="bx_block r3x1">
				<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
					<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
					<?
					foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
					{
						?>
						<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
						<?
					}
					?>
				</select>
				<div style="clear: both;"></div>
			</div>
		<?
		else:
		?>
			<div class="bx_block r1x3">
				<?=GetMessage("SOA_TEMPL_EXISTING_PROFILE")?>
			</div>
			<div class="bx_block r3x1">
					<?
					if (count($arResult["ORDER_PROP"]["USER_PROFILES"]) == 1)
					{
						foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
						{
							echo "<strong>".$arUserProfiles["NAME"]."</strong>";
							?>
							<input type="hidden" name="PROFILE_ID" id="ID_PROFILE_ID" value="<?=$arUserProfiles["ID"]?>" />
							<?
						}
					}
					else
					{
						?>
						<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
							<?
							foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
							{
								?>
								<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
								<?
							}
							?>
						</select>
						<?
					}
					?>
				<div style="clear: both;"></div>
			</div>
		<?
		endif;
	else:
		$bHideProps = false;
	endif;
	?>
</div>


<script>
  

  
  
</script>  

  <? /*
	<h4>
		<?=GetMessage("SOA_TEMPL_BUYER_INFO")?>
		<?
		if (array_key_exists('ERROR', $arResult) && is_array($arResult['ERROR']) && !empty($arResult['ERROR']))
		{
			$bHideProps = false;
		}

		if ($bHideProps && $_POST["showProps"] != "Y"):
		?>
			<a href="#" class="slide" onclick="fGetBuyerProps(this); return false;">
				<?=GetMessage('SOA_TEMPL_BUYER_SHOW');?>
			</a>
		<?
		elseif (($bHideProps && $_POST["showProps"] == "Y")):
		?>
			<a href="#" class="slide" onclick="fGetBuyerProps(this); return false;">
				<?=GetMessage('SOA_TEMPL_BUYER_HIDE');?>
			</a>
		<?
		endif;
		?>
		<input type="hidden" name="showProps" id="showProps" value="<?=($_POST["showProps"] == 'Y' ? 'Y' : 'N')?>" />
	</h4>
   * 
   */?>
    <input type="hidden" name="showProps" id="showProps" value="<?=($_POST["showProps"] == 'Y' ? 'Y' : 'N')?>" />
		<?
          //$users_props = array_merge($arResult["ORDER_PROP"]["USER_PROPS_N"],$arResult["ORDER_PROP"]["USER_PROPS_Y"]);
        
          
          if (!empty($users_props)){
            
        ?>
          <div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1">
            <div class="form_table">
        <?
            if(($users_props[0]["PROPS_GROUP_ID"] == 3 || $users_props[0]["PROPS_GROUP_ID"] == 1 || $users_props[0]["PROPS_GROUP_ID"] == 4)){
        ?>
              <div class="form_table_col">  
        <?        
                customPrintFormProp($users_props[0], $personTypeID);
        ?>
              </div>
              <div class="clear"></div> 
        <?
            }
        ?>
              <div class="form_table_col">
        <?    
            foreach ($users_props as $key => $arProperties){
              if(($key%2 == 1 && ($users_props[0]["PROPS_GROUP_ID"] == 3 || $users_props[0]["PROPS_GROUP_ID"] == 1 || $users_props[0]["PROPS_GROUP_ID"] == 4)) || ($key%2 == 0 && $users_props[0]["PROPS_GROUP_ID"] == 2)){
                customPrintFormProp($arProperties, $personTypeID);
              }
            }
        ?>
              </div>
              <div class="form_table_col">
        <?
            foreach ($users_props as $key => $arProperties){
              if(($key != 0 &&  $key%2 == 0 && ($users_props[0]["PROPS_GROUP_ID"] == 3 || $users_props[0]["PROPS_GROUP_ID"] == 1 || $users_props[0]["PROPS_GROUP_ID"] == 4) ) || ($key%2 == 1 && $users_props[0]["PROPS_GROUP_ID"] == 2)){
                customPrintFormProp($arProperties, $personTypeID);
              }
            }
        ?>
              </div>
              <div class="clear"></div>
        <?   
            if(count($auth_users_props) > 0){
        ?>
              <hr/>

              <div class="form_table_col">
        <?
                foreach ($auth_users_props as $key => $arProperties){
                  if($key%2 == 0){
                    customPrintFormProp($arProperties, $personTypeID);
                  }
                }
        ?>
              </div>
              <div class="form_table_col">
                <?
                foreach ($auth_users_props as $key => $arProperties){
                  if($key%2 == 1){
                    customPrintFormProp($arProperties, $personTypeID);
                  }
                }
        ?>      
              </div>
            </div>
          </div>    
        <?       
          }
        }
         
          //print_r($users_props);
        /*
        ?>
          <div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1">
        <?
        
          if(count($firstBlock) > 0){
        ?>
              <div class="form_table_col">  
        <?        
                customPrintPropsForm($firstBlock, $arParams["TEMPLATE_LOCATION"]);
        ?>
              </div>
              <div class="clear"></div>
        <?    
          }
        ?>
              <div class="form_table_col">  
        <?        
                customPrintPropsForm($secondBlock, $arParams["TEMPLATE_LOCATION"]);
        ?>
              </div>
              <div class="form_table_col">  
        <?        
                customPrintPropsForm($thirdBlock, $arParams["TEMPLATE_LOCATION"]);
        ?>
              </div>
              <div class="clear"></div>
        <?      
          if(count($fourtBlock) > 0){
        ?>
              <hr/>
              <div class="form_table_col">
        <?    
                customPrintPropsForm($fourtBlock, $arParams["TEMPLATE_LOCATION"]);
        ?>
              </div>
              <div class="form_table_col">  
        <?        
                customPrintPropsForm($fifthBlock, $arParams["TEMPLATE_LOCATION"]);
        ?>
              </div>
              <div class="clear"></div>
        <?      
          }      
		//PrintPropsForm($users_props, $arParams["TEMPLATE_LOCATION"]);
		//PrintPropsForm($auth_users_props, $arParams["TEMPLATE_LOCATION"]);
		?>
          </div>  

<script type="text/javascript">
	function fGetBuyerProps(el)
	{
		var show = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW')?>';
		var hide = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE')?>';
		var status = BX('sale_order_props').style.display;
		var startVal = 0;
		var startHeight = 0;
		var endVal = 0;
		var endHeight = 0;
		var pFormCont = BX('sale_order_props');
		pFormCont.style.display = "block";
		pFormCont.style.overflow = "hidden";
		pFormCont.style.height = 0;
		var display = "";

		if (status == 'none')
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_HIDE');?>';

			startVal = 0;
			startHeight = 0;
			endVal = 100;
			endHeight = pFormCont.scrollHeight;
			display = 'block';
			BX('showProps').value = "Y";
			el.innerHTML = hide;
		}
		else
		{
			el.text = '<?=GetMessageJS('SOA_TEMPL_BUYER_SHOW');?>';

			startVal = 100;
			startHeight = pFormCont.scrollHeight;
			endVal = 0;
			endHeight = 0;
			display = 'none';
			BX('showProps').value = "N";
			pFormCont.style.height = startHeight+'px';
			el.innerHTML = show;
		}

		(new BX.easing({
			duration : 700,
			start : { opacity : startVal, height : startHeight},
			finish : { opacity: endVal, height : endHeight},
			transition : BX.easing.makeEaseOut(BX.easing.transitions.quart),
			step : function(state){
				pFormCont.style.height = state.height + "px";
				pFormCont.style.opacity = state.opacity / 100;
			},
			complete : function(){
					BX('sale_order_props').style.display = display;
					BX('sale_order_props').style.height = '';

					pFormCont.style.overflow = "visible";
			}
		})).animate();
	}
</script>

<?if(!CSaleLocation::isLocationProEnabled()):?>
	<div style="display:none;">

		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.ajax.locations",
			$arParams["TEMPLATE_LOCATION"],
			array(
				"AJAX_CALL" => "N",
				"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
				"REGION_INPUT_NAME" => "REGION_tmp",
				"CITY_INPUT_NAME" => "tmp",
				"CITY_OUT_LOCATION" => "Y",
				"LOCATION_VALUE" => "",
				"ONCITYCHANGE" => "submitForm()",
			),
			null,
			array('HIDE_ICONS' => 'Y')
        );?>

	</div>
<?endif?>
