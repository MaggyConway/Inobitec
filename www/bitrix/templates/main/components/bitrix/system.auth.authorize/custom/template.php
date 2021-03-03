<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
//$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
//$APPLICATION->AddHeadScript('/verstka_new/js/popup.js');
?>
<?

if ($_POST['AUTH_FORM'] === 'Y') 
{
    if (!strlen(trim($_POST['USER_LOGIN']))) {
        $loginError = GetMessage('AUTH_LOGIN_ENTER_LOGIN');
    }

    if (!strlen(trim($_POST['USER_PASSWORD']))) {
        $passwordError = GetMessage('AUTH_LOGIN_ENTER_PASSWORD');
    }

    if (!$loginError && !$passwordError && $arParams['AUTH_RESULT']['TYPE'] == 'ERROR') {
        $loginError = str_replace('.', '', $arParams['~AUTH_RESULT']['MESSAGE']);
    }
    
    
}

?>
<!-- Авторизация -->
<!-- Страница авторизации -->
<div <?if ($arParams["POPUP"] === "Y"): ?>class="popup" id="authorization"<?else:?>class="authorization-block"<?endif;?>>
    <div class="popup-main">
        <?if ($arParams["POPUP"] == "Y"): ?>
            <div class="close">
                <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                    <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
                </svg>
            </div>
            <p class="authorization"><?=GetMessage('AUTH_AUTHORIZATION');?></p>
        <?endif;?>
        <form name="form_auth" method="post" target="_top" action="<?=($arParams['POPUP'] === 'Y' ? $templateFolder . '/authAjax.php' : $arResult['AUTH_URL']);?>">
            <input type="hidden" name="AUTH_FORM" value="Y" />
            <input type="hidden" name="TYPE" value="AUTH" />
            <?if (strlen($arResult["BACKURL"]) > 0):?>
                <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
            <?endif?>
            <?foreach ($arResult["POST"] as $key => $value):?>
                <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
            <?endforeach?>
            <label class="input <?if ($loginError): ?>error<?endif;?>">
                <input name="USER_LOGIN" id="userLogin" placeholder="<?=GetMessage('AUTH_LOGIN');?>" type="text" value="<?= ($_POST['USER_LOGIN'])? $_POST['USER_LOGIN'] : (($_GET['USER_LOGIN'])? $_GET['USER_LOGIN'] : $arResult["LAST_LOGIN"])?>" >
                <div class="error-text" data-empty-text="<?=GetMessage('AUTH_LOGIN_ENTER_LOGIN');?>"><?=$loginError;?></div>
            </label>
            <label class="input <?if ($passwordError): ?>error<?endif;?>">
                <input placeholder="<?=GetMessage('AUTH_PASSWORD');?>" type="password" name="USER_PASSWORD">
                <div class="error-text" data-empty-text="<?=GetMessage('AUTH_LOGIN_ENTER_PASSWORD');?>"><?=$passwordError;?></div>
            </label>
            <label class="checkbox">
                <input type="checkbox" name="USER_REMEMBER" value="Y"> 
                <div class="checkbox_button"></div>
                <span><?=GetMessage('AUTH_REMEMBER_ME');?></span>
            </label>
            <button class="button btn-blue"><?=GetMessage('AUTH_AUTHORIZE');?></button>
        </form>
        <?if ($arParams["POPUP"] === "Y"): ?><a href="javascript:;" onclick="closePopup(document.getElementById('authorization'));Popup('pass');"><?else:?><a class="js-RestoreRedirect" href="<?=$arResult['AUTH_FORGOT_PASSWORD_URL'];?>"><?endif;?><?=GetMessage('AUTH_FORGOT_PASSWORD_2');?></a>
    </div>
</div>


