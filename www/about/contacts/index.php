<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Контакты и форма обратной связи компании Инобитек");
$APPLICATION->SetPageProperty("keywords", "Инобитек, компания, контакты, обратная связь");
$APPLICATION->SetPageProperty("title", "Инобитек / Контактная информация и форма обратной связи");
$APPLICATION->SetTitle("Контакты");
?><p align="justify">
 <b>Отправляя сообщение посредством размещенной ниже формы обратной связи вы автоматически даете своё согласие на обработку ваших персональных данных в соответствии с законом № 152-ФЗ «О персональных данных» от 27.07.2006, принимаете условия <a href="/about/agreement-for-the-processing-of-personal-data.php" target="_blank">Согласия на обработку персональных данных</a> и подтверждаете факт своего ознакомления с <a href="http://inobitec.com/about/privacypolicy.php" target="_blank">Политикой конфиденциальности</a>.</b>
</p>
<div class="page-contacts">
	<div class="page-contacts__left">
		<div class="contacts__info">
			 <?
			// включаемая область для раздела
			$APPLICATION->IncludeFile(SITE_DIR."/includes/contacts_text.php", Array(), Array(
			    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
			    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
			    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
			    ));
			?>
		</div>
		<div class="contacts__map">
			 <?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"CONTROLS" => array(0=>"ZOOM",),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:51.65633358050635;s:10:\"yandex_lon\";d:39.1785678454917;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:39.177291700439;s:3:\"LAT\";d:51.657068640884;s:4:\"TEXT\";s:63:\"ул. Бахметьева, 2Б, 5 этаж, офисы 500-511\";}}}",
		"MAP_HEIGHT" => "670",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(0=>"ENABLE_DRAGGING",)
	)
);?>
		</div>
	</div>
	<div class="page-contacts__right">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:form",
	"feedback",
	Array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"COMPONENT_TEMPLATE" => "feedback",
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"NOT_SHOW_FILTER" => array(0=>"",1=>"",),
		"NOT_SHOW_TABLE" => array(0=>"",1=>"",),
		"RESULT_ID" => $_REQUEST[RESULT_ID],
		"SEF_MODE" => "N",
		"SHOW_ADDITIONAL" => "N",
		"SHOW_ANSWER_VALUE" => "N",
		"SHOW_EDIT_PAGE" => "N",
		"SHOW_LIST_PAGE" => "Y",
		"SHOW_STATUS" => "N",
		"SHOW_VIEW_PAGE" => "Y",
		"START_PAGE" => "new",
		"SUCCESS_URL" => "",
		"USE_EXTENDED_ERRORS" => "N",
		"VARIABLE_ALIASES" => array("action"=>"action",),
		"WEB_FORM_ID" => "1"
	)
);?>
	</div>
	<div class="cl">
	</div>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD', {action: 'homepage'}).then(function(token) {
    	$('<input type="hidden" name="recaptcha_response">').val(token).prependTo($('form[name="FEEDBACK_FORM_RUS"]'));
    });
});
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter28963570 = new Ya.Metrika({id:28963570,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<!-- /Yandex.Metrika counter --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>