<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Результаты СОУТ");
$APPLICATION->SetPageProperty("keywords", "Результаты СОУТ");
$APPLICATION->SetPageProperty("title", "Инобитек / Результаты СОУТ");
$APPLICATION->SetTitle("СОУТ");
?><h1>Результаты СОУТ</h1>
 <br>
 Сводная ведомость результатов проведения специальной оценки условий труда<br>
<ul>
	<li><a href="/upload/pdf/svodnaya-vedomost.pdf" target="_blank">Сводная ведомость проведения СОУТ</a></li>
</ul>
 <br>
 Заключение эксперта<br>
<ul>
	<li><a href="/upload/pdf/zaklyuchenie-experta.pdf" target="_blank">Заключение эксперта</a></li>
</ul>
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