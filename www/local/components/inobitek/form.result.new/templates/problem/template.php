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


<?//print_r($arResult);?>
<?=$arResult["FORM_HEADER"]?>
  <input type="hidden" name="CUSTOM_AJAX" value="Y" autocomplete="off">
  <input type="hidden" name="web_form_submit" value="Y" autocomplete="off">
  <input type="hidden" name="web_form_apply" value="Y" autocomplete="off">
  <input type="hidden" name="AJAX_CALL" value="Y" autocomplete="off">
    <?
      foreach ($arResult["arQuestions"] as $FIELD_SID => $arQuestion)
      {
        //print_r($arQuestion);
        switch($FIELD_SID){
          case 'NAME':
            ?>
              <div class="input support-form__full-name">
                <label for="full-name"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="full-name" placeholder="" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          case 'PHONE':
            ?>
              <div class="input support-form__phone js-phone-input">
                <label for="phone"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input data-msg="<?= GetMessage("FORM_ERROR_UNCORRECT")?>" type="tel" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="phone" pattern="+7-[0-9]{3}-[0-9]{3}-[0-9]{2}-[0-9]{2}" data-pattern="\+[0-9]{11}" data-msg="<?=GetMessage("FORM_FIELD_UNCORRECT")?>" minlength="12" placeholder="+___________" inputmode="numeric" placeholder="<?=$arResult["arAnswers"][$FIELD_SID][0]['TITLE']?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'PHONE_2':
            ?>
              <div class="input support-form__phone">
                <label for="phone_2"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="phone_2" placeholder="" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?> placeholder="">
              </div>
            <?
            break;
          
          case 'EMPTY_INPUT':
            ?>
              <div class="input">
                <input type="hidden" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>">
              </div>
            <?
            break;
          
          case 'EMAIL':
            ?>
              <div class="input support-form__email js-email-input">
                <label for="email"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input data-msg="<?= GetMessage("FORM_ERROR_UNCORRECT")?>" type="email" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="email" data-msg="<?=GetMessage("FORM_FIELD_UNCORRECT")?>" placeholder="example@email.com" pattern="[^@]+@[^@]+.[a-zA-Z]{2,6}" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'ORG_NAME':
            ?>
              <div class="input support-form__organization-name">
                <label for="organization-name"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="organization-name" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
          case 'ABOUT_COMPANY':
            ?>
              <div class="input support-form__message">
                <label for="message"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <textarea data-msg="<?= GetMessage("FORM_FIELD_REQ")?>"
                          name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>"
                          id="message"
                          rows="6"
                          placeholder="<?=GetMessage("FORM_FIELD_COM_DETAIL")?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
                </textarea>
              </div>
            <?
            break;
          
          case 'PRODUCTS':
            ?>
              <div class="select support-form__products">
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
              <div class="input support-form__licenses-number js-number-input">
                <label for="licenses-number"><?=$arQuestion['TITLE']?></label>
                <div class="distributor-questionnaire__number-container">
                  <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="licenses-number" minlength="1" maxlength="3" placeholder="100" inputmode="numeric"><span><?=  GetMessage("FORM_NUM")?></span>
                </div>
              </div>
            <?
            break;
          
          case 'QUESTION':
            ?>
              <div class="input support-form__message">
                <label for="question-message"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <textarea data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="question-message" rows="2" data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" placeholder="<?= GetMessage("FORM_YOUR_QUESTION")?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>></textarea>
              </div>
            <?
            break;
          
          case 'FILE_1':
            ?>

              <div class="file-input support-form__file js-file-input">
                <div class="file-input__title"><?=$arQuestion['TITLE']?></div>
                <div class="file-input__subtitle"><?= GetMessage("FORM_FILES_DETAILS")?></div>
                <div class="file-input__container">
                  <div class="file-input__item">
                    <label aria-label="<?= GetMessage("FORM_FILES_ADD")?>">
                      <input class="visually-hidden" type="file" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>">
                    </label>
                    <button class="file-input__remove-button" type="button" aria-label="<?= GetMessage("FORM_FILES_DEL")?>"></button>
                  </div>
                  
                  <?if(isset($arResult["arQuestions"]['FILE_2'])):?>
                  <div class="file-input__item">
                    <label aria-label="<?= GetMessage("FORM_FILES_ADD")?>">
                      <input class="visually-hidden" type="file" name="form_<?=$arResult["arAnswers"]['FILE_2'][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"]['FILE_2'][0]['ID']?>">
                    </label>
                    <button class="file-input__remove-button" type="button" aria-label="<?= GetMessage("FORM_FILES_DEL")?>"></button>
                  </div>
                  <?endif;?>
                  
                  <?if(isset($arResult["arQuestions"]['FILE_3'])):?>
                  <div class="file-input__item">
                    <label aria-label="<?= GetMessage("FORM_FILES_ADD")?>">
                      <input class="visually-hidden" type="file" name="form_<?=$arResult["arAnswers"]['FILE_3'][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"]['FILE_3'][0]['ID']?>">
                    </label>
                    <button class="file-input__remove-button" type="button" aria-label="<?= GetMessage("FORM_FILES_DEL")?>"></button>
                  </div>
                  <?endif;?>
                  
                  <?if(isset($arResult["arQuestions"]['FILE_4'])):?>
                  <div class="file-input__item">
                    <label aria-label="<?= GetMessage("FORM_FILES_ADD")?>">
                      <input class="visually-hidden" type="file" name="form_<?=$arResult["arAnswers"]['FILE_4'][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"]['FILE_4'][0]['ID']?>">
                    </label>
                    <button class="file-input__remove-button" type="button" aria-label="<?= GetMessage("FORM_FILES_DEL")?>"></button>
                  </div>
                  <?endif;?>
                  
                  <?if(isset($arResult["arQuestions"]['FILE_5'])):?>
                  <div class="file-input__item">
                    <label aria-label="<?= GetMessage("FORM_FILES_ADD")?>">
                      <input class="visually-hidden" type="file" name="form_<?=$arResult["arAnswers"]['FILE_5'][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"]['FILE_5'][0]['ID']?>">
                    </label>
                    <button class="file-input__remove-button" type="button" aria-label="<?= GetMessage("FORM_FILES_DEL")?>"></button>
                  </div>
                  <?endif;?>
                </div>
              </div>

            <?
            break;
            
            case 'PRODUCT':
            ?>
              <div class="select support-form__product">
                <label for="issue-issue-product-select"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <div class="select__container js-custom-select js-issue-product-select">

                  <select data-msg="<?=GetMessage("FORM_FIELD_REQ")?>"
                          name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$FIELD_SID?>[]"
                          id="issue-issue-product-select"
                          data-msg="<?=GetMessage("FORM_CHOOSE_PRODUCT")?>"
                          <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>

                    <option disabled selected><?=GetMessage("FORM_FIELD_CHOOSE_LIST")?></option>

                    <?foreach($arResult["arAnswers"][$FIELD_SID] as $key => $arAnswer):?>
                      <option value="<?=$arAnswer['ID']?>" <?=($arAnswer['VALUE'] == 'Server')?'data-multicolor="true"':''?>><?=$arAnswer['MESSAGE']?></option>
                    <?endforeach;?>
                  </select>

                  <svg width="32" height="32">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-select-arrow"></use>
                  </svg>
                </div>
              </div>
            <?
            break;
            
            
            case 'PRODUCT_VERSION':
            ?>
              <div class="select support-form__version">
                <label for="issue-issue-version-select"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>

                <div class="select__container js-custom-select js-version-select is-disabled">

                  <select data-msg="<?=GetMessage("FORM_FIELD_REQ")?>" 
                          name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" 
                          id="issue-issue-version-select" 
                          data-product-versions="/ajax/productsversions.php?formId=<?=$arResult['arForm']['ID']?>&fieldCode=PRODUCT" 
                          data-msg="<?= GetMessage("FORM_CHOOSE_VERSION")?>" 
                          <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>

                    <option disabled selected><?=GetMessage("FORM_FIELD_CHOOSE_LIST")?></option>

                  </select>

                  <svg width="32" height="32">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-select-arrow"></use>
                  </svg>
                </div>
              </div>
            <?
            break;
            
            case 'OS_VERSION':
            ?>
              <div class="input support-form__os-version">
                <label for="issue-issue-os-version"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?><span class="tooltip support-form__tooltip">?<span class="tooltip__popup"><a class="link link--blue" href="http://help.inobitec.com/kb/faq.php?id=<?=(SITE_ID == 's1')?52:59?>" target="_blank"><?= GetMessage("FORM_HOW_TO_KNOW")?></a></span></span></label>
                <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="issue-issue-os-version" placeholder="<?= GetMessage("FORM_EXAMPLE")?>: macOS Mojave, 10.14.3 (18D109)" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
            case 'PRODUCT_CODE':
            ?>
              <div class="input support-form__code js-code-input">
                <label for="issue-issue-code"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?><span class="tooltip support-form__tooltip">?<span class="tooltip__popup"><a class="link link--blue" href="http://help.inobitec.com/kb/faq.php?id=<?=(SITE_ID == 's1')?60:61?>" target="_blank"><?= GetMessage("FORM_HOW_TO_KNOW")?></a></span></span></label>
                <input data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" type="text" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="issue-issue-code" data-pattern="[0-9]{4}\-[0-9]{4}\-[0-9]{4}\-[0-9]{4}" data-msg="<?= GetMessage("FORM_FIELD_UNCORRECT")?>" placeholder="0000-0000-0000-0000" inputmode="numeric" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>>
              </div>
            <?
            break;
          
            case 'DESCRIPTION':
            ?>
              <div class="input support-form__message">
                <label for="issue-issue-message"><?=$arQuestion['TITLE']?><?=($arQuestion['REQUIRED'] == "Y")?"<span>*</span>":""?></label>
                <textarea data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" name="form_<?=$arResult["arAnswers"][$FIELD_SID][0]['FIELD_TYPE']?>_<?=$arResult["arAnswers"][$FIELD_SID][0]['ID']?>" id="issue-message" rows="2" data-msg="<?= GetMessage("FORM_FIELD_REQ")?>" placeholder="<?= GetMessage("FORM_DESCRIBE_PROBLEM")?>" <?=($arQuestion['REQUIRED'] == "Y")?"required":""?>></textarea>
				<div class="counter-block"><span id="counter-problem"></span><span id="max-symbols-problem"></span></div>
              </div>
            <?
            break;
            
          
        }
        //print_R($arQuestion);
        //print_r('<br><br>');
      }
    ?>





      <?if(SITE_ID == 's1'):?>
      <p class="support-form__agreement">Отправляя сообщение посредством размещенной выше формы обратной связи, вы автоматически даете своё согласие на обработку ваших персональных данных в соответствии с законом № 152-ФЗ «О персональных данных» от 27.07.2006, принимаете условия <a class="link link--blue" target="_blanck" href="https://inobitec.ru/about/agreement-for-the-processing-of-personal-data.php">Согласия на обработку персональных данных</a> и подтверждаете факт своего ознакомления с <a class="link link--blue" target="_blanck" href="https://inobitec.ru/about/privacypolicy.php">Политикой конфиденциальности</a>.</p>
      <?endif;?>
      <button class="btn btn--light-blue-filled support-form__button" type="submit"><?=  GetMessage("FORM_SEND_BUTTOM")?></button>

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
<?
} //endif (isFormNote)
?>

<script>
  jQuery(document).ready(function($) {

    
    
    $('form[name="TROUBLE_FORM_ES"]').submit(function(event) {

        $(this).find('.select__container').each(function(index, el) {

            if ( $(el).find('select').val() == null) {
                let selectMsg = $(el).find('select').attr('data-msg');
                let selectId = $(el).find('select').attr('id');

                $(el).append('<label class="error" for="'+ selectId +'">'+ selectMsg +'</label>');
                $(el).find('.js-Dropdown').addClass('error');
           
            } else {
                $(el).find('.js-Dropdown').removeClass('error');
            }
        });
    });

    $('label[for="issue"]').click(function(event) {
      setTimeout(function() {
        // console.log( $('.support-form__os-version label').width() );

        let lblTxt = $('.support-form__os-version label').text();
        lblTxt = lblTxt.split(' ');

        lblTxt[10] = lblTxt[10].split('*');
        lblTxt[10] = lblTxt[10][0];
        lblTxt[11] = '';
        // console.log( lblTxt[10] );

        let newArr = '';

        $(lblTxt).each(function(index, el) {
            
            el = '<span style="color: #000;">'+el+' </span>';
            // console.log( el );
            newArr += el;
        });
        // console.log( 'newArr - ', newArr );

        newArr += '<span>*</span>'+'<span class="tooltip support-form__tooltip">?<span class="tooltip__popup"><a class="link link--blue" href="http://help.inobitec.com/kb/faq.php?id=59" target="_blank">¿Cómo averiguarlo?</a></span></span>';

        $('.support-form__os-version label').html(' ').append(newArr);
      }, 50);
    });



    
  });
</script>