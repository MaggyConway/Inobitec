<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Contacts and Feedback-form of Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, contacts, feedback");
$APPLICATION->SetPageProperty("title", "Inobitec / Contacts and Feedback-form");
$APPLICATION->SetTitle("Contacts");
?>
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
	array(
		"CONTROLS" => array(
			0 => "ZOOM",
		),
		"INIT_MAP_TYPE" => "MAP",
		"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:51.65623490781554;s:10:\"yandex_lon\";d:39.17906782183819;s:12:\"yandex_scale\";i:16;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:39.17732975039654;s:3:\"LAT\";d:51.657022338702795;s:4:\"TEXT\";s:50:\"Offices 500-511, 5th floor, 2b Bakhmetieva, 394006\";}}}",
		"MAP_HEIGHT" => "670",
		"MAP_ID" => "",
		"MAP_WIDTH" => "100%",
		"OPTIONS" => array(
			0 => "ENABLE_DRAGGING",
		),
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
		</div>
	</div>
	<div class="page-contacts__right">
	<?
	$APPLICATION->IncludeComponent(
	"bitrix:form", 
	"feedback", 
	array(
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHAIN_ITEM_LINK" => "",
		"CHAIN_ITEM_TEXT" => "",
		"EDIT_ADDITIONAL" => "N",
		"EDIT_STATUS" => "N",
		"IGNORE_CUSTOM_TEMPLATE" => "N",
		"NOT_SHOW_FILTER" => array(
			0 => "",
			1 => "",
		),
		"NOT_SHOW_TABLE" => array(
			0 => "",
			1 => "",
		),
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
		"WEB_FORM_ID" => "2",
		"COMPONENT_TEMPLATE" => "feedback",
		"VARIABLE_ALIASES" => array(
			"action" => "action",
		)
	),
	false
);?>
	</div>
	<div class="cl"></div>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD', {action: 'homepage'}).then(function(token) {
    	$('<input type="hidden" name="recaptcha_response">').val(token).prependTo($('form[name="FEEDBACK_FORM_ENG"]'));
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
<noscript><div><img src="//mc.yandex.ru/watch/28963570" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>