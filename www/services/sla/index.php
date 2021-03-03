<?
$newPageCSS =  true;
$newPage2020 = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Платный сервис корпоративной технической поддержки, предоставляемый Заказчику, содержащий уровни, а также связанные с ними тарифы и соответствующий каждому уровню регламент реагирования Службы технической поддержки Инобитек на обращения корпоративных клиентов");
$APPLICATION->SetPageProperty("keywords", "SLA, корпоративная техническая поддержка, техническая поддержка 24 на 7, поддержка 24 7, техническая поддержка, поддержка клиентов, срочная помощь, поддержка 24 на 7, поддержка 24 7, сервисное обслуживание");
$APPLICATION->SetPageProperty("title", "Инобитек / Корпоративная техническая поддержка с гарантированным уровнем сервиса");
$APPLICATION->SetTitle("SLA");

require_once($_SERVER["DOCUMENT_ROOT"]."/include/sla.php");
?>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'YpxlNpZnHd';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/geo-widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->

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