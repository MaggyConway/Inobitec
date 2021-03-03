<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$min_length = $arResult['MIN_LENGTH'];
$min_length_message = GetMessage('AUTH_CHANGEPASS_PASSWORD_MIN_LENGTH', array('#symbols#' => $min_length));
?>
<?if ($arParams['AUTH_RESULT']['TYPE'] == 'OK'): ?>
	<div class="success-block">
	    <div class="popup-main">
	        <img src="/img/svg/checkbox.svg">
	        <p class="success"><?=GetMessage('sys_auth_chpass_success');?></p>
	        <!--<p><?=GetMessage('sys_auth_chpass_data_sent');?></p>-->
	    </div>
	</div>
<?elseif(isset($arResult["UNCORREXT_LOGIN"]) && $arResult["UNCORREXT_LOGIN"] == "Y"):?>
  <div class="success-block">
      <div class="popup-main">
          <p class="success"><?=Loc::getMessage('AUTH_CHANGEPASS_UNCORRECT_LOGIN', array('#LOGIN#' => $arResult["USER_LOGIN"]));?></p>
          <p><?=Loc::getMessage('AUTH_CHANGEPASS_UNCORRECT_LOGIN_2', array('#LOGIN#' => $arResult["USER_LOGIN"]));?></p>
      </div>
  </div>
<?elseif(isset($arResult["UNCORREXT_CHECKWORD"]) && $arResult["UNCORREXT_CHECKWORD"] == "Y"):?>
  <div class="success-block">
	    <div class="popup-main">
	        <p class="success"><?=Loc::getMessage('AUTH_CHANGEPASS_UNCORRECT_CHECKWORD', array('#LOGIN#' => $arResult["USER_LOGIN"]));?></p>
	         <p><?=Loc::getMessage('AUTH_CHANGEPASS_UNCORRECT_CHECKWORD_2', array('#LOGIN#' => $arResult["USER_LOGIN"]));?></p>
	    </div>
	</div>
<?else:?>
	<!-- Страница смены пароля (2 поля ввода) -->
	<div class="change-password-block">
	    <div class="popup-main input-width popup-main-height">
	    	<p class="sub-text sub-text-margin"><?=GetMessage('AUTH_CHANGEPASS_ERRORS_PASS');?></p>
	        <form class="popup-padding changepassform" method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
	        	<?if (strlen($arResult["BACKURL"]) > 0): ?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
				<? endif ?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="CHANGE_PWD">
				<input type="hidden" name="USER_LOGIN" value="<?=$arResult["LAST_LOGIN"]?>"/>
				<input type="hidden" name="USER_CHECKWORD" value="<?=$arResult["USER_CHECKWORD"]?>"/>
	            <label class="input <?if ($arParams['AUTH_RESULT']['TYPE'] == 'ERROR'): ?>error<?endif;?>">
	                <input placeholder="<?=GetMessage('AUTH_NEW_PASSWORD');?>" type="password" name="USER_PASSWORD" value="<?=$arResult["USER_PASSWORD"]?>" data-minlength="<?=$min_length;?>" data-minlength-message="<?=$min_length_message;?>" data-empty-text="<?=GetMessage('AUTH_CHANGEPASS_ENTER_PASSWORD');?>" data-pattern-message="<?=Loc::getMessage('AUTH_CHANGEPASS_PASSWORDS_PATTERN_ERROR');?>">
<!--                     <div class="error custom_hint"><?=GetMessage('AUTH_CHANGEPASS_ERRORS_PASS');?></div> -->
	                <?if ($arParams['AUTH_RESULT']['TYPE'] == 'ERROR'): ?>
	               		<div class="error-text"><?=$arParams['~AUTH_RESULT']['MESSAGE'];?></div>
	                <?endif;?>
	            </label>
	            <label class="input">
	                <input placeholder="<?=GetMessage('AUTH_NEW_PASSWORD_CONFIRM');?>" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" data-empty-text="<?=GetMessage('AUTH_CHANGEPASS_REPEAT_PASSWORD');?>" data-incorrect-text="<?=GetMessage('AUTH_CHANGEPASS_PASSWORDS_MISMATCH');?>">
                    <!-- <div class="error custom_hint"><?=GetMessage('AUTH_CHANGEPASS_ERRORS_PASS');?></div> -->
	            </label>
	            <button class="btn-blue media-button"><?=GetMessage('AUTH_CHANGE');?></button>
	        </form>
	    </div>
	</div>
<?endif;?>

