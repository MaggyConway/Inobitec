<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
?>
<div class="subscribe">
    <div class="subscribe__main">
        <p><?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.SUBSCRIBE_AND_TRACK');?> </p>
        <form>
            <div class="subscribe__main-column">
                <label for="subscribe"><?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.EMAIL');?></label>
                <input id="subscribe">
            </div>
            <button type="submit"> <?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.SUBSCRIBE');?></button>
            <div class="subscribe__main-column">
                <div class="verification">
                    <label class="checkbox">
                        <input type="checkbox">
                        <div class="checkbox_button"></div>
                        <span><?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.AGREE_WITH');?> <a href="#"><?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.CONTRACT_OFFER');?></a> <?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.AND');?> <a href="#"><?=Loc::getMessage('INOBITEC.SUBSCRIBE.FORM.PERSONAL_DATA_PROCESSING');?></a></span>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>