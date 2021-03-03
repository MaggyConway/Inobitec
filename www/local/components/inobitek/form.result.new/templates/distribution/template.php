<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$isAjax = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$isAjax = (
		(isset($_POST['AJAX_CALL']) && $_POST['AJAX_CALL'] == 'Y')
        && $_POST['WEB_FORM_ID'] == $arResult['arForm']['ID']
	);
}

if ($isAjax)
{
	$APPLICATION->RestartBuffer();
    $data['ERRORS'] = $arResult['FORM_ERRORS'];
    $data['RESULT'] = (isset($arResult['FORM_RESULT']) && $arResult['FORM_RESULT']  == 'addok' )? 'added' : 'error';
    $data['RESULT_FORM_ID'] = isset($arResult["FORM_RESULT_ID"]) ? $arResult["FORM_RESULT_ID"] : '';
    $data['TEST_DATA'] = $arResult["TEST"];
    $jsonData = \Bitrix\Main\Web\Json::encode($data);
    print_r($jsonData);
    die();
}
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<div class="distributor-questionnaire" id="distributor-questionnaire">
  <div class="wr">
    <h2><?=$arResult["FORM_TITLE"]?></h2>

<?//print_r($arResult);?>
<?=$arResult["FORM_HEADER"]?>
    <input type="hidden" name="CUSTOM_AJAX" value="Y" autocomplete="off">
    <input type="hidden" name="web_form_submit" value="Y" autocomplete="off">
    <input type="hidden" name="web_form_apply" value="Y" autocomplete="off">
    <input type="hidden" name="AJAX_CALL" value="Y" autocomplete="off">
    <?
      foreach ($arResult["arQuestions"] as $FIELD_SID => $arQuestion)
      {
        switch($FIELD_SID){
          case 'NAME':
            ?>
              <div class="input distributor-questionnaire__full-name">
                <label for="full-name"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="full-name" placeholder="" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?> data-msg="<?=GetMessage("FORM_ERROR_REQ")?>">
              </div>
            <?
            break;
          case 'PHONE':
            ?>
              <div class="input distributor-questionnaire__phone js-phone-input">
                <label for="phone"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input type="tel" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="phone" pattern="+7-[0-9]{3}-[0-9]{3}-[0-9]{2}-[0-9]{2}" data-pattern="\+[0-9]{11}" data-msg="<?=GetMessage("FORM_FIELD_UNCORRECT")?>" minlength="12" placeholder="+___________" inputmode="numeric" placeholder="<?=$arResult["arAnswers"][$FIELD_SID][0]['TITLE']?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'EMAIL':
            ?>
              <div class="input distributor-questionnaire__email js-email-input">
                <label for="email"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input type="email" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="email" data-msg="<?=GetMessage("FORM_FIELD_UNCORRECT")?>" placeholder="example@email.com" pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'ORG_NAME':
            ?>
              <div class="input distributor-questionnaire__organization-name">
                <label for="organization-name"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input type="text" data-msg="<?=GetMessage("FORM_ERROR_REQ")?>" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="organization-name" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'ABOUT_COMPANY':
            ?>
              <div class="input distributor-questionnaire__message">
                <label for="message"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <textarea  name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="message" rows="6" data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" placeholder="<?=GetMessage("FORM_FIELD_COM_DETAIL")?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>></textarea>
              </div>
            <?
            break;
          
          case 'PRODUCTS':
            ?>
              <div class="select distributor-questionnaire__products">
                <label><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <div class="select__container distributor-questionnaire__products-container js-custom-select js-questionnaire-product-select">
                  <select name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$FIELD_SID?>[]">
                    <option disabled selected><?=  GetMessage("FORM_FIELD_CHOOSE_LIST")?></option>
                    <?foreach($arResult["arAnswers"][$FIELD_SID] as $key => $arAnswer):?>
                      <option value="<?=$arAnswer['ID']?>"><?=$arAnswer['MESSAGE']?></option>
                    <?endforeach;?>
                  </select>
                  <svg width="32" height="32">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-select-arrow"></use>
                  </svg>
                </div>
                <button class="btn btn--light-blue-outline distributor-questionnaire__add-button js-add-select-button" type="button"><span><?=  GetMessage("FORM_MORE")?></span></button>
              </div>
            <?
            break;
          
          case 'LIC_NUMS':
            ?>
              <div class="input distributor-questionnaire__licenses-number js-number-input">
                <label for="licenses-number"><?=$arQuestion['TITLE']?></label>
                <div class="distributor-questionnaire__number-container">
                  <input type="text"  data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="licenses-number" minlength="1" maxlength="3" placeholder="100" inputmode="numeric"><span><?=  GetMessage("FORM_NUM")?></span>
                </div>
              </div>
            <?
            break;
          
        }
        //print_R($arQuestion);
        //print_r('<br><br>');
      }
    ?>
      
      
      
      
      
      <?if(SITE_ID == 's1'):?>
      <p class="distributor-questionnaire__agreement">Отправляя сообщение посредством размещенной выше формы обратной связи, вы автоматически даете своё согласие на обработку ваших персональных данных в соответствии с законом № 152-ФЗ «О персональных данных» от 27.07.2006, принимаете условия <a class="link link--blue" target="_blanck" href="https://inobitec.ru/about/agreement-for-the-processing-of-personal-data.php">Согласия на обработку персональных данных</a> и подтверждаете факт своего ознакомления с <a class="link link--blue" target="_blanck" href="https://inobitec.ru/about/privacypolicy.php">Политикой конфиденциальности</a>.</p>
      <?endif;?>
      <button class="btn btn--light-blue-filled distributor-questionnaire__button" type="submit"><?=  GetMessage("FORM_SEND_BUTTOM")?></button>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
/*
?>
      <table class="form-table data-table">
          <thead>
              <tr>
                  <th colspan="2">&nbsp;</th>
              </tr>
          </thead>
          <tbody>
          <?
          foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
          {
              if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
              {
                  echo $arQuestion["HTML_CODE"];
              }
              else
              {
          ?>
              <tr>
                  <td>
                      <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
                      <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
                      <?endif;?>
                      <?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"] == "Y"):?><?=$arResult["REQUIRED_SIGN"];?><?endif;?>
                      <?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>
                  </td>
                  <td><?=$arQuestion["HTML_CODE"]?></td>
              </tr>
          <?
              }
          } //endwhile
          ?>

          </tbody>

      </table>
*/?>
<?=$arResult["FORM_FOOTER"]?>
  </div>
</div>
<?
} //endif (isFormNote)
?>