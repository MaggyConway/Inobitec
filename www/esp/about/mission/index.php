<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Mission of Inobitec LLC");
$APPLICATION->SetPageProperty("keywords", "Inobitec, company, mission");
$APPLICATION->SetPageProperty("title", "Misión de Inobitec / Company");
$APPLICATION->SetTitle("Misión");
?>
</div>

<div class="mission-page">
	<div class="wr">
		<div class="mission-page__text">
			<?
			// включаемая область для раздела
			$APPLICATION->IncludeFile(SITE_DIR."/includes/mission.inc.php", Array(), Array(
			    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
			    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
			    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
			    ));
			?>
		</div>
	</div>
</div>

<div class="wr">

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