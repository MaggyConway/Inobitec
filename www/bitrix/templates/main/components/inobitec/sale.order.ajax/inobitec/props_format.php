<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
			$res .= "<br/><br/>";
			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
		}

		return $res;
	}
}



if(!function_exists("customCheckIfPattern")){
  function customCheckIfPattern($name, $personTypeID){
    $patterns = array();
    if($personTypeID == 1 && SITE_ID == 's1')
      $patterns = array(
          "OGRN" => '/^\d{13}$/',
          "INN" => '/^\d{10}$/',
          "KPP" => '/^\d{9}$/',
      );
    elseif($personTypeID == 3 && SITE_ID == 's1')
      $patterns = array(
          "OGRN" => '/^\d{13}$/',
          "INN" => '/^\d{12}$/',
          "KPP" => '/^\d{9}$/',
      );
    
    $patterns["PASS"] = '/^[A-Za-z0-9]+$/';
    $patterns["PASS_CONF"] = '/^[A-Za-z0-9]+$/';
    $patterns["EMAIL"] = '/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/';
    
    if(isset($patterns[$name])){
      return $patterns[$name];
    }else{
      return false;
    }
  }
}

if(!function_exists("customCheckIfHidden")){
  function customCheckIfHidden($name){
    $hiddenFields = array("s1" => array("BANK_NAME", "SWIFT"), 's2' => array("BIK", "KOR_ACC"));
    if(in_array($name,$hiddenFields[SITE_ID])){
      return true;
    }else{
      return false;
    }
  }
}

if(!function_exists("customCheckIfMaxLength")){
  function customCheckIfMaxLength($name, $personTypeID){

    switch($name){
      case "INN":
        if($personTypeID == 1)
          return "maxlength='10'";
        elseif($personTypeID == 3)
          return "maxlength='12'";
        else
          return "";
        
      default:
        return "";
    }
    return "";
  }
}

if(!function_exists("customCheckIfHint")){
  function customCheckIfHint($name, $personTypeID){

    switch($name){
       case "INN":
        if($personTypeID == 1 && SITE_ID == 's1')
          return GetMessage("CUSTOM_ORDER_INFO_INN");
        elseif($personTypeID == 3 && SITE_ID == 's1')
          return GetMessage("CUSTOM_ORDER_INFO_INN_IP");
        else
          return "";
    }
    return "";
  }
}


if(!function_exists("customGetUserPropByOrderProp")){
  function customGetOrderPropByUserProp($orderPropname){
    $props = array(
        "WORK_COMPANY" => "UF_COMPANY_NAME",
        "INN" => "UF_TAX_ID",
        "OGRN" => "UF_PSRN",
        "BIK" => "UF_BANK",
        "KPP" => "UF_RRC",
        "KOR_ACC" => "UF_CORR_ACCOUNT",
        "CHECK_ACC" => "UF_BANK_ACCOUNT",
        "DIRECTOR_JOB" => "UF_JOB_TITLE",
        "EVIDENCE" => "UF_BASED",
        "POWER_OF_ATTORNEY" => "UF_PROCURATORY",
        "DIRECTOR_FIO" => "UF_CEO_FULL_NAME",
        "ADDRESS" => "UF_LEGAL_ADDRESS",
        "MAIL_ADDRESS" => "UF_MAILING_ADDRESS",
        "EMAIL" => "EMAIL",
        "WORK_PHONE" => "PERSONAL_PHONE",
        "BANK_NAME" => "UF_BANK_NAME",
        "SWIFT" => "UF_ENBANK_SWIFT",
        "NAME" => array("NAME", "LAST_NAME", "SECOND_NAME"),
        "LEGAL_FORM" => "UF_LEGAL_FORM",
        "OGRNIP" => "UF_PSRNSP",
    );
    if(isset($props[$orderPropname])){
      return $props[$orderPropname];
    }
    return false;
  }
}

if(!function_exists("customGetUserValue")){
  function customGetUserValue($arUser,$fields){
    $val = "";
    if(is_array($fields)){
      $n = 0;
      foreach($fields as $field){
        $n++;
        if(!isset($arUser[$field]) || !$arUser[$field])
          continue;
 
        if($n > 0 && strlen($val) > 0){
          $val .= " ";
        }
        $val .= $arUser[$field];
      }
    }else{
      if(isset($arUser[$fields]) && $arUser[$fields])
        $val = $arUser[$fields];
    }
    return $val;
  }
}

if(!function_exists("customCheckDefaultValue")){
  function customCheckDefaultValue(&$arProperties){
    global $USER;
    $userProp = customGetOrderPropByUserProp($arProperties["CODE"]);
    
    
    if(isset($_POST[$arProperties["FIELD_NAME"]]) && $_POST[$arProperties["FIELD_NAME"]] != "" || !$userProp)
      return;
    
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $userPropVal = customGetUserValue($arUser, $userProp);
    if($arProperties["CODE"] == "WORK_PHONE"){
      if($userPropVal !== false){
        $arProperties["VALUE"] = $userPropVal;
        return;
      }
      return;
    }elseif($arProperties["TYPE"] == "TEXT"){
      if($userPropVal  !== false)
        $arProperties["VALUE"] = htmlspecialchars($userPropVal);
      return;
    }elseif($arProperties["TYPE"] == "SELECT" && $arProperties["CODE"] == "EVIDENCE"){
        $evidanceCode = "regulations";
        switch($userPropVal){
          case 5:
            $evidanceCode = "regulations";
            break;
          case 6:
            $evidanceCode = "powerOfAttorney";
            break;
          default:
            break;
        }
        foreach($arProperties["VARIANTS"] as $key => $variant){
          if($variant["VALUE"] == $evidanceCode){
            $arProperties["VARIANTS"][$key]["SELECTED"] = "Y";
          }else{
            unset($arProperties["VARIANTS"][$key]["SELECTED"]);
          }
        }
    }
    return;
  }
}

if(!function_exists("customGetErrorConditionForAng")){
  function customGetErrorConditionForAng($arProperties){
    if($arProperties["CODE"] == "POWER_OF_ATTORNEY"){
      return "(!ValidateAttorneyFlag)";
    }elseif($arProperties["CODE"] == "EMAIL"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue < 1 || !ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid || !EMAILERROR)";
    }elseif($arProperties["CODE"] == "WORK_PHONE"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue < 1 || (!ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue) || !ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid)";
    }elseif($arProperties["REQUIED_FORMATED"]=="Y"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue < 1 || !ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid)";
    }else{
      return "false";
    }
  }
}


if(!function_exists("customGetValidConditionForAng")){
  function customGetValidConditionForAng($arProperties){
    if($arProperties["CODE"] == "POWER_OF_ATTORNEY"){
      return "(ValidateAttorneyFlag)";
    }elseif($arProperties["CODE"] == "EMAIL"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid && ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue.length > 0 && EMAILERROR) ";
    }elseif($arProperties["CODE"] == "WORK_PHONE"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid && ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue > 0)";
    }elseif($arProperties["REQUIED_FORMATED"]=="Y"){
      return "(ORDER_FORM.".$arProperties["FIELD_NAME"].".\$valid && ORDER_FORM.".$arProperties["FIELD_NAME"].".\$modelValue.length > 0)";
    }else{
      return "true";
    }
  }
}

if(!function_exists("customGetPhoneMask")){
  function customGetPhoneMask(){
    if(LANG == "s2")
      return "";
    return "";
  }
}

function getCorrectPhoneNumber($val){
  $val = preg_replace('/[^0-9]/', '', $val);
  if(LANG == "s1"){
    if(substr($val, 0, 1) == "+"){
      $val = substr($val, 1);
      if(substr($val, 0, 1) == "7" )
        $userPropVal = substr($val, 1);
    }elseif(substr($val, 0, 1) == "8" && strlen($val) > 10){
      $val = substr($val, 1);
    }elseif(substr($val, 0, 1) == "7" && strlen($val) > 10){
      $val = substr($val, 1);
    }
  }else{
      $val = "+".$val;
    }
      
  return $val;
}


if(!function_exists("customPrintFormProp")){
  function customPrintFormProp($arProperties, $personTypeID = false){
    customCheckDefaultValue($arProperties);
    if($arProperties["CODE"] == "WORK_PHONE"){
      $phoneValue = getCorrectPhoneNumber($arProperties["VALUE"]);
      
    ?>
      <label class="input" ng-class="{'error' :  ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=customGetErrorConditionForAng($arProperties)?>, 'valid' : ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=customGetValidConditionForAng($arProperties)?>}">
        
          <span><?= GetMessage("CUSTOM_ORDER_FIELD_".$arProperties["CODE"])?></span>
          <input tabindex="<?=$arProperties["SORT"]?>"  id="<?=$arProperties["FIELD_NAME"]?>"  ng-model="userPhone" data-call="<?=$arProperties["CODE"]?>"type="text" <?if (LANGUAGE_ID == 'ru'): ?> placeholder="" ui-mask="+7 (999) 999-99-99" ui-options="{clearOnBlur: false}" <?else:?>placeholder="+9999999.." pattern="^\+[0-9]*$" my-Phonestopinput ng-minlength='9'<?endif;?> placeholder="+7 (___) ___-__-__"  name="<?=$arProperties["FIELD_NAME"]?>" <?if($arProperties["VALUE"]):?>ng-init="userPhone = '<?=$phoneValue?>'"<?else:?>ng-init=" <?if (LANGUAGE_ID != 'ru'): ?>userPhone = '+'<?endif;?>"<?endif;?> <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?>/>
          <div class="error"></div>
      </label>
    <?
    }elseif($arProperties["CODE"] == "PASS" || $arProperties["CODE"] == "PASS_CONF"){
    ?>
      <label  class="input" ng-class="{'error' :  ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && (ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$modelValue.length < 1 || !ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$valid<?=($arProperties["CODE"] == "PASS_CONF")?"|| PASS !=  PASS_CONF":""?>), 'valid' : ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && (ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$valid && ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$modelValue.length > 0)<?=($arProperties["CODE"] == "PASS_CONF")?"&& PASS ==  PASS_CONF":""?>}">
          <span><?= GetMessage("CUSTOM_ORDER_FIELD_".$arProperties["CODE"])?></span>
          <input my-Validatorpass tabindex="<?=$arProperties["SORT"]?>" type="PASSWORD" id="<?=$arProperties["FIELD_NAME"]?>" data-call="<?=$arProperties["CODE"]?>" placeholder="<?=$arProperties["DESCRIPTION"]?>" <?=(customCheckIfPattern($arProperties["CODE"],$personTypeID))?"ng-pattern='".customCheckIfPattern($arProperties["CODE"],$personTypeID)."'":""?> ng-model="<?=$arProperties["CODE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" ng-init="<?=$arProperties["CODE"]?> ='<?=$arProperties["VALUE"]?>'" <?=($arProperties["CODE"] == "PASS" || $arProperties["CODE"] == "PASS_CONF")?"ng-minlength='8'":""?> data-req='Y' />
          <div class="error custom_hint"><?= GetMessage("CUSTOM_ORDER_ERRORS_PASS")?></div>
          <div class="error"></div>
      </label>
      
    <?
    }elseif ($arProperties["TYPE"] == "TEXT"){
      switch ($arProperties['CODE']) {
        case 'POWER_OF_ATTORNEY':
          $add_label_class = 'input-height';
          break;
       
        default:
          $add_label_class = null;
          break;
      }
      //правка для исправления бага с выбором опций
      if ($personTypeID == 1 || $personTypeID == 4) {
        if ($arProperties['CODE'] == 'POWER_OF_ATTORNEY') {
          $add_label_class = 'input-height';
        } 
      }
      //правка для исправления бага с выбором опций окончание
    ?>
        <label <?=(customCheckIfHidden($arProperties["CODE"]))?"style='display:none;'":""?> class="input <?if ($add_label_class): ?><?=$add_label_class;?><?endif;?>" ng-class="{'error' :  ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=customGetErrorConditionForAng($arProperties)?>, 'valid' : ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=customGetValidConditionForAng($arProperties)?> <?if($arProperties['CODE'] == 'MAIL_ADDRESS' && ($personTypeID == 1)):?>, 'input-margin': showAttorney(), 'input-height': !showAttorney() <?endif;?>}" <?if($arProperties['CODE'] == "POWER_OF_ATTORNEY"):?>ng-show="showAttorney()"<?endif;?> >
            <span><?= GetMessage("CUSTOM_ORDER_FIELD_".$arProperties["CODE"])?></span>
            <input tabindex="<?=$arProperties["SORT"]?>" <?=customCheckIfMaxLength($arProperties["CODE"],$personTypeID)?> type="text" id="<?=$arProperties["FIELD_NAME"]?>" data-call="<?=$arProperties["CODE"]?>" placeholder="<?= GetMessage("CUSTOM_ORDER_FIELD_PLACEHOLDER_".$arProperties["CODE"])?>" <?=(customCheckIfPattern($arProperties["CODE"],$personTypeID))?"ng-pattern='".customCheckIfPattern($arProperties["CODE"],$personTypeID)."'":""?> ng-model="<?=$arProperties["CODE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" ng-init="<?=$arProperties["CODE"]?> ='<?=$arProperties["VALUE"]?>'" <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?> <?=($arProperties["CODE"] == "POWER_OF_ATTORNEY")?"ng-change='ValidateAttorney()'":""?>/>
            <div class="error custom_hint"><?=customCheckIfHint($arProperties["CODE"],$personTypeID)?></div>
            <?if($arProperties["CODE"] == "EMAIL"):?>
              <div class="error" ng-show='!EMAILERROR && EMAILERRORCODE=="EXIST"'><?= GetMessage("CUSTOM_ORDER_ERRORS_EMAIL_EXIST")?></div>
              <div class="error" ng-show='!EMAILERROR && EMAILERRORCODE=="NOT_EM"'><?= GetMessage("CUSTOM_ORDER_ERRORS_EMAIL_FORMAT")?></div>
              
            <?endif;?>
            <div class="error"></div>
            
        </label>
    <?
    }elseif($arProperties["TYPE"] == "SELECT" && $arProperties["CODE"] == "EVIDENCE"){
    ?>
      <div class="radio">
          <span><?= GetMessage("CUSTOM_ORDER_FIELD_".$arProperties["CODE"])?></span>
          <div class="radio_box">
    <?        
          foreach($arProperties["VARIANTS"] as $arVariants):
    ?>
            <label class="in_radio">
                <input tabindex="<?=$arProperties["SORT"]?>" type="radio" data-call="<?=$arProperties["CODE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" <?if ($arVariants["SELECTED"] == "Y") echo 'checked="checked"';?> value="<?=$arVariants["VALUE"]?>" <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?> ng-click="ValidateAttorney()"/>
                <div class="in_radio_button"></div>
                <div class="in_radio_text"><?= GetMessage("CUSTOM_ORDER_FIELD_EVIDENCE_VALUE_".$arVariants["VALUE"])?></div>
            </label>
    <?
          endforeach;
    ?>
          </div>
      </div>
    <?  
    }elseif($arProperties["TYPE"] == "CHECKBOX" && $arProperties["CODE"] == "OFERTA_AGREE"){

      $basketInfo = basketInfo();
      
      /*
    ?>  
      <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
      <label  class="checkbox" ng-class="{'error' :  ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && !<?=$arProperties["CODE"]?>, 'valid' : ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=$arProperties["CODE"]?>}">
          <input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" ng-model="<?=$arProperties["CODE"]?>" <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?> />
          <div class="checkbox_button"></div>
          <span>
            <?=GetMessage("CUSTOM_ORDER_FIELD_LIC_AGREE", array("LIC_LINK" => "#", "DATA_LINK" => SITE_DIR."about/privacypolicy.php"))?>
            <span ng-cloak="" style="color: rgb(227, 94, 85);" ng-show="ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && !<?=$arProperties["CODE"]?>"><?=  GetMessage("CUSTOM_ORDER_ERRORS_LIC_AGREE")?></span>
          </span>
          
      </label>
      <? */ ?>
      <input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="" <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?> data-call="<?=$arProperties["CODE"]?>">

      <label tabindex="<?=$arProperties["SORT"]?>"  class="checkbox" ng-class="{'error' :  ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && !<?=$arProperties["CODE"]?>, 'valid' : ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && <?=$arProperties["CODE"]?>}">
            <input  type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" ng-model="<?=$arProperties["CODE"]?>" <?=($arProperties["REQUIED_FORMATED"]=="Y")?"data-req='Y'":""?> id="<?=$arProperties["FIELD_NAME"]?>" value="Y">

            <div class="checkbox_button"></div>
            <span>
              <?
              $viewNum = \COption::GetOptionString( "askaron.settings", "UF_VIEW_LIC_NUM" );
              $servNum = \COption::GetOptionString( "askaron.settings", "UF_SERV_LIC_NUM" );
                if( (count($basketInfo["lite"]) + count($basketInfo["pro"]) + count($basketInfo["listOfExtLic"]["pro"]) + count($basketInfo["listOfExtLic"]["lite"])) > 0 && (count($basketInfo["server"]) + count($basketInfo["listOfExtLic"]["server"]) ) > 0 )
                  echo GetMessage("CUSTOM_ORDER_FIELD_LIC_AGREE_SERV_VIEW", array("#VIEW_LINK#" => SITE_DIR."about/viewerLic/", "#SERV_LINK#" => SITE_DIR."about/serverLic/", "#DATA_LINK#" => SITE_DIR."about/privacypolicy.php", "#VIEWERNUM#" => $viewNum, "#SERVERNUM#" => $servNum));
                elseif( (count($basketInfo["server"]) + count($basketInfo["listOfExtLic"]["server"])) > 0)
                  echo GetMessage("CUSTOM_ORDER_FIELD_LIC_AGREE_SERVER", array("#SERV_LINK#" => SITE_DIR."about/serverLic/", "#DATA_LINK#" => SITE_DIR."about/privacypolicy.php", "#SERVERNUM#" => $servNum));
                elseif((count($basketInfo["lite"]) + count($basketInfo["pro"]) + count($basketInfo["listOfExtLic"]["pro"]) + count($basketInfo["listOfExtLic"]["lite"])) > 0)
                  echo GetMessage("CUSTOM_ORDER_FIELD_LIC_AGREE_VIEWER", array("#VIEW_LINK#" => SITE_DIR."about/viewerLic/", "#DATA_LINK#" => SITE_DIR."about/privacypolicy.php", "#VIEWERNUM#" => $viewNum));
              ?>
              <span class="checkbox-error" ng-cloak="" ng-show="ORDER_FORM.<?=$arProperties["FIELD_NAME"]?>.$dirty && !<?=$arProperties["CODE"]?>"><?=  GetMessage("CUSTOM_ORDER_ERRORS_LIC_AGREE")?></span>
            </span>
        
      </label>
      
    <?
    }
  }
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource))
		{
			?>
				<div>
					<?
					foreach ($arSource as $arProperties)
					{
						?>
						<div data-property-id-row="<?=intval(intval($arProperties["ID"]))?>">

						<?
						if ($arProperties["TYPE"] == "CHECKBOX")
						{
							?>
							<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">

							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r1x3 pt8">
								<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>

							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXT")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" />

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div><br/>
							<?
						}
						elseif ($arProperties["TYPE"] == "SELECT")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
									<?
									endforeach;
									?>
								</select>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "MULTISELECT")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
									<?
									endforeach;
									?>
								</select>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXTAREA")
						{
							$rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<textarea rows="<?=$rows?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "LOCATION")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">

								<?
								$value = 0;
								if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
								{
									foreach ($arProperties["VARIANTS"] as $arVariant)
									{
										if ($arVariant["SELECTED"] == "Y")
										{
											$value = $arVariant["ID"];
											break;
										}
									}
								}

								// here we can get '' or 'popup'
								// map them, if needed
								if(CSaleLocation::isLocationProMigrated())
								{
									$locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
									$locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
								}
								?>

								<?if($locationTemplateP == 'steps'):?>
									<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
								<?endif?>

								<?CSaleLocation::proxySaleAjaxLocationsComponent(array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY",
									"REGION_INPUT_NAME" => "REGION",
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									"LOCATION_VALUE" => $value,
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
									"SIZE1" => $arProperties["SIZE1"],
								),
								array(
									"ID" => $value,
									"CODE" => "",
									"SHOW_DEFAULT_LOCATIONS" => "Y",

									// function called on each location change caused by user or by program
									// it may be replaced with global component dispatch mechanism coming soon
									"JS_CALLBACK" => "submitFormProxy",

									// function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
									// it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
									"JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

									// an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
									// it may be replaced with global component dispatch mechanism coming soon
									"JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

									"DISABLE_KEYBOARD_INPUT" => "Y",
									"PRECACHE_LAST_LEVEL" => "Y",
									"PRESELECT_TREE_TRUNK" => "Y",
									"SUPPRESS_ERRORS" => "Y"
								),
								$locationTemplateP,
								true,
								'location-block-wrapper'
								)?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>

							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "RADIO")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<?
								if (is_array($arProperties["VARIANTS"]))
								{
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<input
											type="radio"
											name="<?=$arProperties["FIELD_NAME"]?>"
											id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
											value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") echo " checked";?> />

										<label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label></br>
									<?
									endforeach;
								}
								?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "FILE")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>

							<div style="clear: both;"></div><br/>
							<?
						}
						?>
						</div>

						<?if(CSaleLocation::isLocationProEnabled()):?>

							<?
							$propertyAttributes = array(
								'type' => $arProperties["TYPE"],
								'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form' // value taken from property DEFAULT_VALUE or it`s a user-typed value?
							);

							if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
								$propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

							if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
								$propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

							if($arProperties['IS_ZIP'] == 'Y')
								$propertyAttributes['isZip'] = true;
							?>

							<script>

								<?// add property info to have client-side control on it?>
								(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
									'id' => intval($arProperties["ID"]),
									'attributes' => $propertyAttributes
								))?>);

							</script>
						<?endif?>

						<?
					}
					?>
				</div>
			<?
		}
	}
}





if (!function_exists("customPrintPropsForm"))
{
	function customPrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource))
		{
			?>
					<?
					foreach ($arSource as $arProperties)
					{
						?>
						<div data-property-id-row="<?=intval(intval($arProperties["ID"]))?>">

						<?
						if ($arProperties["TYPE"] == "CHECKBOX")
						{
							?>
							<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">

							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r1x3 pt8">
								<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>

							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXT")
						{
							?>
                            
                            <label class="input">
                                
                                <span><?=$arProperties["NAME"]?></span>
                                <input type="text" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" placeholder="<?=$arProperties["DESCRIPTION"]?>" ng-model="<?=$arProperties["FIELD_NAME"]?>" ng-init="<?=$arProperties["FIELD_NAME"]?> ='<?=$arProperties["VALUE"]?>'" />
                                <div class="error">Поле заполнено не верно</div>
                            </label>
                            
					
							<?
						}
						elseif ($arProperties["TYPE"] == "SELECT")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
									<?
									endforeach;
									?>
								</select>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "MULTISELECT")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
									<?
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
									<?
									endforeach;
									?>
								</select>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "TEXTAREA")
						{
							$rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<textarea rows="<?=$rows?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "LOCATION")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">

								<?
								$value = 0;
								if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
								{
									foreach ($arProperties["VARIANTS"] as $arVariant)
									{
										if ($arVariant["SELECTED"] == "Y")
										{
											$value = $arVariant["ID"];
											break;
										}
									}
								}

								// here we can get '' or 'popup'
								// map them, if needed
								if(CSaleLocation::isLocationProMigrated())
								{
									$locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
									$locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
								}
								?>

								<?if($locationTemplateP == 'steps'):?>
									<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
								<?endif?>

								<?CSaleLocation::proxySaleAjaxLocationsComponent(array(
									"AJAX_CALL" => "N",
									"COUNTRY_INPUT_NAME" => "COUNTRY",
									"REGION_INPUT_NAME" => "REGION",
									"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
									"CITY_OUT_LOCATION" => "Y",
									"LOCATION_VALUE" => $value,
									"ORDER_PROPS_ID" => $arProperties["ID"],
									"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
									"SIZE1" => $arProperties["SIZE1"],
								),
								array(
									"ID" => $value,
									"CODE" => "",
									"SHOW_DEFAULT_LOCATIONS" => "Y",

									// function called on each location change caused by user or by program
									// it may be replaced with global component dispatch mechanism coming soon
									"JS_CALLBACK" => "submitFormProxy",

									// function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
									// it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
									"JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

									// an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
									// it may be replaced with global component dispatch mechanism coming soon
									"JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

									"DISABLE_KEYBOARD_INPUT" => "Y",
									"PRECACHE_LAST_LEVEL" => "Y",
									"PRESELECT_TREE_TRUNK" => "Y",
									"SUPPRESS_ERRORS" => "Y"
								),
								$locationTemplateP,
								true,
								'location-block-wrapper'
								)?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>

							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "RADIO")
						{
							?>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<?
								if (is_array($arProperties["VARIANTS"]))
								{
									foreach($arProperties["VARIANTS"] as $arVariants):
									?>
										<input
											type="radio"
											name="<?=$arProperties["FIELD_NAME"]?>"
											id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
											value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") echo " checked";?> />

										<label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label></br>
									<?
									endforeach;
								}
								?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>
							<div style="clear: both;"></div>
							<?
						}
						elseif ($arProperties["TYPE"] == "FILE")
						{
							?>
							<br/>
							<div class="bx_block r1x3 pt8">
								<?=$arProperties["NAME"]?>
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="bx_sof_req">*</span>
								<?endif;?>
							</div>

							<div class="bx_block r3x1">
								<?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>

								<?
								if (strlen(trim($arProperties["DESCRIPTION"])) > 0):
								?>
								<div class="bx_description">
									<?=$arProperties["DESCRIPTION"]?>
								</div>
								<?
								endif;
								?>
							</div>

							<div style="clear: both;"></div><br/>
							<?
						}
						?>
						</div>

						<?if(CSaleLocation::isLocationProEnabled()):?>

							<?
							$propertyAttributes = array(
								'type' => $arProperties["TYPE"],
								'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form' // value taken from property DEFAULT_VALUE or it`s a user-typed value?
							);

							if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
								$propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

							if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
								$propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

							if($arProperties['IS_ZIP'] == 'Y')
								$propertyAttributes['isZip'] = true;
							?>

							<script>

								<?// add property info to have client-side control on it?>
								(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
									'id' => intval($arProperties["ID"]),
									'attributes' => $propertyAttributes
								))?>);

							</script>
						<?endif?>

						<?
					}
					?>
				
			<?
		}
	}
}
?>