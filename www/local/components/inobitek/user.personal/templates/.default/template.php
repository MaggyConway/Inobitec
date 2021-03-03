<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<div class="lk_title"><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PERSONAL_DATA');?></div>
<div class="page_basket_personal_info">
	<form>
		<div class="page_basket_personal_info_title">
			<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.LICESNEE_INFORMATION');?></span><i></i>
		</div>
		<!--div class="page_basket_personal_info_type js-tab_inputs">
			<label class="radio_tab">
				
				<!--div class="radio_tab_button"></div>
				
				
			</label>
		</div-->
        <?$typeId = $arResult["ENUMS"]["UF_USER_TYPE"][$arResult['USER']['UF_USER_TYPE']]["XML_ID"];?>
        <input style="display:none;" type="radio" name="type" value="1" checked="checked" disabled="disabled" />
        <div class="radio_tab_text radio-disabled-info"><?=(
            $arResult['USER']['UF_USER_TYPE'] ? Loc::getMessage('INOBITEK.USER.PERSONAL.TYPE_' . strtoupper($typeId)) : ''
        );?></div>

      
		<div class="form_table">
			<?if ($typeId == 'legal_entity'): ?>
              <?if(isset($arResult['USER']['UF_LEGAL_FORM']) && $arResult['USER']['UF_LEGAL_FORM']):?>
                <div class="form_table_col">
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.LEGAL_FORM');?></span>
						<div class="val"><?=$arResult['USER']['UF_LEGAL_FORM'];?></div>
					</label>
				</div>
              <?endif;?>
				<div class="form_table_col">
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.WORK_COMPANY');?></span>
						<div class="val"><?=$arResult['USER']['UF_COMPANY_NAME'];?></div>
					</label>
				</div>
			<?endif;?>

			<div class="clear"></div>

			<div class="form_table_col">
				<?if (LANGUAGE_ID != 'en'): ?>
					<?if ($typeId == 'legal_entity'): ?>
						<label class="input valid have_value">
							<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PSRN');?></span>
							<div class="val type-number"><?=$arResult['USER']['UF_PSRN'];?></div>
						</label>
					<?elseif ($typeId == 'individual_entrepreneur'):?>
							<label class="input valid have_value">
								<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PSRNSP');?></span>
								<div class="val type-number"><?=$arResult['USER']['UF_PSRNSP'];?></div>
							</label>
					<?endif;?>
				<?endif;?>
				<?if ($typeId != 'legal_entity'): ?>
					<label class="input valid have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.FULL_NAME');?></span>
						<div class="val">
							<?=$arResult['USER']['NAME'];?>		
						</div>
					</label>
				<?else:?>
					<label class="input valid have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.TAX_ID');?></span>
						<div class="val type-number"><?=$arResult['USER']['UF_TAX_ID'];?></div>
					</label>
				<?endif;?>
				<?if (strlen(trim($arResult['USER']['UF_RRC'])) > 0 && $typeId == 'legal_entity' && LANGUAGE_ID != 'en'): ?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.REG_REASON_CODE');?></span>
						<div class="val type-number"><?=$arResult['USER']['UF_RRC'];?></div>
					</label>
				<?endif;?>
			</div>
			<?if ($typeId != 'individual'): ?>
				<div class="form_table_col">
                  <?if(SITE_ID == 's2'):?>
                    <label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.BANK');?></span>
						<div class="val"><?=$arResult['USER']['UF_BANK_NAME'];?></div>
					</label>
                  <?endif;?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.BANK_ACCOUNT');?></span>
						<div class="val type-number"><?=$arResult['USER']['UF_BANK_ACCOUNT'];?></div>
					</label>
                  <?if(SITE_ID == 's1'):?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.CORR_ACCOUNT');?></span>
						<div class="val type-number"><?=$arResult['USER']['UF_CORR_ACCOUNT'];?></div>
					</label>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.BANK');?></span>
						<div class="val type-number"><?=$arResult['USER']['UF_BANK'];?></div>
					</label>
                  <?endif;?>
                  <?if(SITE_ID == 's2'):?>
                    <label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.SWIFT');?></span>
						<div class="val"><?=$arResult['USER']['UF_ENBANK_SWIFT'];?></div>
					</label>
                  <?endif;?>
				</div>
			<?endif;?>

			<div class="clear"></div>

			<div class="form_table_no_col">
				<?if ($typeId == 'legal_entity'): ?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.JOB_TITLE');?></span>
						<div class="val"><?=$arResult['USER']['UF_JOB_TITLE'];?></div>
					</label>
				<?endif;?>
				<?if ($arResult["USER"]["UF_BASED"] && LANGUAGE_ID != 'en' && $typeId != 'individual'): ?>
					<div class="radio have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.BASED');?></span>
						<div class="radio_box">
							<label class="in_radio">
								<input type="radio" name="osn" checked="checked" disabled="disabled" />
								<!--div class="in_radio_button"></div-->
								<?$basedId = $arResult['ENUMS']["UF_BASED"][$arResult["USER"]["UF_BASED"]]["XML_ID"];?>
								<div class="in_radio_text">
									<?=(
										$arResult["USER"]["UF_BASED"] ? Loc::getMessage('INOBITEK.USER.PERSONAL.BASED_' . $basedId) : ''
									);?>
								</div>
							</label>
						</div>
					</div>
				<?endif;?>
				<?if ($typeId == 'legal_entity'): ?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.CEO_FULL_NAME');?></span>
						<div class="val"><?=$arResult['USER']['UF_CEO_FULL_NAME'];?></div>
					</label>
				<?endif;?>
				<?if (strlen(trim($arResult['USER']['UF_PROCURATORY'])) > 0 && LANGUAGE_ID != 'en' && $typeId != 'individual'): ?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PROCURATORY');?></span>
						<div class="val"><?=$arResult['USER']['UF_PROCURATORY'];?></div>
					</label>
				<?endif;?>
				<?if ($typeId != 'individual'): ?>
					<label class="input have_value">
						<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.LEGAL_ADDRESS');?></span>
						<div class="val"><?=$arResult['USER']['UF_LEGAL_ADDRESS'];?></div>
					</label>
				<?endif;?>
				<label class="input have_value">
					<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.MAILING_ADDRESS');?></span>
					<div class="val"><?=$arResult['USER']['UF_MAILING_ADDRESS'];?></div>
				</label>

				<div class="clear"></div>
			</div>

			<hr/>

			<div class="form_table_col">
				<label class="input have_value">
					<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.EMAIL');?></span>
					<div class="val"><?=$arResult['USER']['EMAIL'];?></div>
				</label>
				<label class="input have_value">
					<span><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PHONE');?></span>
					<div class="val  type-number"><?=$arResult['USER']['PERSONAL_PHONE'];?></div>
				</label>
			</div>
            <div class="clear"></div>
              <button class="btn-blue" onclick="$('#changePassTwo input[type=password]').val('');$('#changePassTwo label').removeClass('error');Popup('changePassTwo')"><?=Loc::getMessage('INOBITEK.USER.CHANGE_PASSWORD');?></button>
            
			
		</div>
	</form>
</div>
<?
//Определяем минимальную длину пароля из настроек безопасности и сообщение, которое должно показываться
$arGroupPolicy = CUser::GetGroupPolicy($arResult['USER']['ID']);
$min_length = $arGroupPolicy['PASSWORD_LENGTH'];
$min_length_message = Loc::getMessage('INOBITEK.USER.PERSONAL.PASSWORD_MIN_LENGTH', ['#symbols#' => $min_length]);
?>
<!-- Смена пароля (2 поля ввода) -->
<div class="popup" id="changePassTwo">
    <div class="popup-main input-width popup-main-height">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization"><?=Loc::getMessage('INOBITEK.USER.PERSONAL.NEW_PASSWORD');?></p>
        <p class="sub-text sub-text-margin"><?=Loc::getMessage('INOBITEK.USER.PERSONAL.PASSWORD_REQUIREMENTS');?></p>
        <form class="popup-padding" name="changepass" action="<?=$templateFolder . '/changePassAjax.php';?>">
            <label class="input">
                <input placeholder="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.NEW_PASSWORD');?>" type="password" name="password" data-minlength="<?=$min_length;?>" data-minlength-message="<?=$min_length_message;?>" data-message-pattern="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.PASSWORDS_PATTERN_ERROR');?>">
                <div class="error-text" data-empty-text="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.ENTER_PASSWORD');?>"></div>
            </label>
            <label class="input">
                <input placeholder="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.CONFIRM_PASSWORD');?>" type="password" name="confirm_password">
                <div class="error-text" data-empty-text="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.REPEAT_PASSWORD');?>" data-incorrect-text="<?=Loc::getMessage('INOBITEK.USER.PERSONAL.PASSWORDS_MISMATCH');?>"></div>
            </label>
            <button class="btn-blue media-button" id="changePassword"><?=Loc::getMessage('INOBITEK.USER.CHANGE_PASSWORD');?></button>
        </form>
    </div>
</div>
<!--Сообщение об успешной смене пароля-->
<div class="popup" id="pass-success-changed">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization"><?=Loc::getMessage('INOBITEK.USER.PERSONAL.CONFIRM_PASSWORD_CHANGED');?></p>
        <form>
            <button class="btn-blue authorization-button" onclick="closePopup(document.getElementById('pass-success-changed'));">OK</button>
        </form>
    </div>
</div>