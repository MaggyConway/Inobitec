<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

?>
<!-- Страница востановления пароля -->
<?if ($arParams['AUTH_RESULT']['TYPE'] == 'OK'): ?>
<?$APPLICATION->SetTitle(GetMessage("sys_forgot_pass_pagetitle"));?>
	<div class="success-block">
	    <div class="popup-main">
	        <img src="/img/svg/checkbox.svg">
	        <p class="success"><?=GetMessage('sys_forgot_pass_data_sent');?></p>
	        <p><?=GetMessage('sys_forgot_pass_email_check');?></p>
            <?if ($arParams["POPUP"] == "Y"): ?>
	        	<a href="javascript:;" onclick="closePopup(document.getElementById('pass'));Popup('authorization');">	
	        <?else: ?>
	        	<a href="<?=$arResult['AUTH_AUTH_URL'];?>">
	        <?endif;?><?=GetMessage('AUTH_AUTH');?></a>
	    </div>
	</div>
<?else: ?>
	<div <?if ($arParams["POPUP"] === "Y"): ?>class="popup" id="pass"<?else:?>class="reset-password-block"<?endif;?>>
	    <div class="popup-main">
	    	<?if ($arParams["POPUP"] === "Y"): ?>
		    	<div class="close">
		            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
		                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
		            </svg>
	        	</div>
        		<p class="authorization"><?=GetMessage('sys_forgot_pass_pagetitle');?></p>
        		<?
        			$formAction = (SITE_ID == 's2') ? '/eng/login/forgotPassAjax.php' : '/login/forgotPassAjax.php' ;

        			switch (SITE_ID) {
        				case 's2':
        					$formAction = '/eng/login/forgotPassAjax.php';
        					break;

        				case 's3':
        					$formAction = '/fra/login/forgotPassAjax.php';
        					break;

        				case 's4':
        					$formAction = '/deu/login/forgotPassAjax.php';
        					break;
        				
        				default:
        					$formAction = '/login/forgotPassAjax.php';
        					break;
        			}
        		?>
        	<?else:?>
        		<?$formAction = $arResult["AUTH_URL"];?>
	    	<?endif;?>
	        <form name="bform" method="post" target="_top" action="<?=$formAction;?>">
	        	<?
                    //$APPLICATION->SetTitle(GetMessage("sys_forgot_pass_pagetitle"));
	        		if ($arParams["POPUP"] != "Y"){ 
                      $APPLICATION->SetTitle(GetMessage("sys_forgot_pass_pagetitle"));
                    }
	        	?>
	        	<?
				if (strlen($arResult["BACKURL"]) > 0)
				{
				?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
				<?
				}
				?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="SEND_PWD">

	            <label class="input <?if ($arParams['AUTH_RESULT']['TYPE'] == 'ERROR'): ?>error<?endif;?>">
	                <input type="text" id="userLogin" name="USER_LOGIN" value="<?= ($request->get('USER_LOGIN')) ? $request->get('USER_LOGIN') : $arResult["LAST_LOGIN"]?>" placeholder="<?=GetMessage('sys_forgot_pass_reg_email');?>" type="email" />
	                <input type="hidden" name="USER_EMAIL" />
	                <!--<label class="input">
	                    <div class="error-text">Поле заполнено не верно</div>
	                </label>-->
	                <?if ($arParams['AUTH_RESULT']['TYPE'] == 'ERROR'): ?>
	                	<div class="error-text test"><?=str_replace('.','',$arParams['~AUTH_RESULT']['MESSAGE']);?></div>
	                <?elseif ($arParams['POPUP'] == 'Y'): ?>
	                	<div class="error-text" data-empty-text="<?=GetMessage('sys_forgot_pass_reg_email');?>"></div>
	                <?endif;?>
	            </label>
	            <p><?=GetMessage('sys_forgot_pass_link_will_send');?></p>
	            <button class="btn-blue media-button"><?=GetMessage('sys_forgot_recover_pass_label');?></button>
	        </form>
	        <?if ($arParams["POPUP"] == "Y"): ?>
	        	<a href="javascript:;" onclick="closePopup(document.getElementById('pass'));Popup('authorization');">	
	        <?else: ?>
	        	<a class="js-RestoreRedirect" href="<?=$arResult['AUTH_AUTH_URL'];?>">
	        <?endif;?><?=GetMessage('AUTH_AUTH');?></a>
	    </div>
	</div>
	<?if ($arParams["POPUP"] == "Y"): ?>
		<div class="popup" id="new-pass-success">
		    <div class="popup-main">
		        <div class="close">
		            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
		                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
		            </svg>
		        </div>
		        <p class="authorization"><?=GetMessage('sys_forgot_pass_new_pass_created');?></p>
		        <form>
		            <button class="btn-blue authorization-button" onclick="closePopup(document.getElementById('new-pass-success'));">OK</button>
		        </form>
		    </div>
		</div>
	<?endif;?>
<?endif;?>
<script type="text/javascript">
	document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
	document.bform.USER_LOGIN.focus();
</script>



