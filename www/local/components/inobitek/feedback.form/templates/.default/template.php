<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<div class="contact-us__form contact-us__form-active">
    <h4><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.CONTACT_US');?></h4>
    <p><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.NAME');?></p>
    <p><?=$arResult["USER"]["NAME"];?></p>
    <p><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.PHONE');?></p>
    <p class="phone"><?=$arResult["USER"]["PERSONAL_PHONE"];?></p>
    <p><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.EMAIL');?></p>
    <p class="mail"><?=$arResult["USER"]["EMAIL"];?></p>
    <form method="POST" action="<?=$templateFolder . '/ajax.php';?>">
        <label class="input"> <!-- Добавить инпуту класс "error", что бы отметить поле ошибкой -->
            <span><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.TEXT');?></span>
            <textarea id="message"></textarea>
            <div class="error-text"><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.TEXT');?></div>
        </label>
        <?if(SITE_ID == 's1'):?>
        <div class="verification">
            <label class="checkbox">
                <input type="checkbox" id="agree">
                <div class="checkbox_button"></div>
                <span>
                	<?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.AGREE_WITH');?>
                	<a target="_blanck" href="<?=SITE_DIR?>about/privacypolicy.php"><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.LICENSE_CONTRACT');?></a><br>
                	<?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.OR');?>
                    <a target="_blanck" href="<?=SITE_DIR?>about/agreement-for-the-processing-of-personal-data.php">
                        <?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.PERSONAL_DATA_PROCESSING');?>
                    </a>
                    <span id="checkbox_err" style="display: none"></span>
                </span>
            </label>
        </div>
        <?else:?>
        <div class="verification" style="display:none;">
            <label class="checkbox">
                <input type="checkbox" id="agree" checked="checked">
                <div class="checkbox_button"></div>
                <span>
                	<?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.AGREE_WITH');?>
                	<a target="_blanck" href="<?=SITE_DIR?>about/privacypolicy.php"><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.LICENSE_CONTRACT');?></a><br>
                	<?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.OR');?>
                    <a target="_blanck" href="<?=SITE_DIR?>about/agreement-for-the-processing-of-personal-data.php">
                        <?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.PERSONAL_DATA_PROCESSING');?>
                    </a>
                    <span id="checkbox_err" style="display: none"></span>
                </span>
            </label>
        </div>
        <?endif;?>
        <input type="hidden" name="EMAIL" value="<?=$arParams['EMAIL_TO'];?>">
        <input type="hidden" name="site" value="<?=SITE_ID;?>">
        <div id="globalError" style="display: none">
          <span>Тут должна быть ошибка</span>
        </div>
        <button id="feedBackSend"><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.SEND');?></button>
    </form>
</div>
<!-- для активации блока успешной отправки убрать сверху "contact-us__form-active"-->
<div class="contact-us__form contact-us__form-success <?/*contact-us__form-success-active*/?>">
    <h4><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.CONTACT_US');?></h4>
    <img src="/img/svg/letter.svg">
    <p><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.THANKYOU');?></p>
    <p><?=Loc::getMessage('INOBITEC.FEEDBACK.FORM.REPLY_SOON');?></p>
</div>
<!-- для активации блока успешной отправки добавить сверху "contact-us__form-success-active"-->

