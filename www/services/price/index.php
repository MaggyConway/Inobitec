<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Компания Инобитек использует гибкие модели ценообразования при расчете бюджетов разработки программного обеспечения в заказных проектах учреждений здравоохранения");
$APPLICATION->SetPageProperty("keywords", "Разработка, программное обеспечение, здравоохранение, медицина, заказ, на заказ, цены, цена, сроки, бюджет");
$APPLICATION->SetPageProperty("title", "Инобитек / Принципы ценообразования при оценке заказных проектов по разработке программного обеспечения в медицинской сфере");
$APPLICATION->SetTitle("Принципы ценообразования");
?>
<div class="simple-text">

	
	<div class="with-ico">

		<span class="ico"><img src="/bitrix/templates/main/img/price_ico_1.jpg" width="80" height="83" alt="Фиксированное" /></span>
		<h3>Фиксированное</h3>
		Основано на водопадной модели производства функционала, когда все требования четко сформулированы и не меняются до завершения всех работ по проекту
	</div>


	<div class="with-ico">

		<span class="ico"><img src="/bitrix/templates/main/img/price_ico_2.jpg" width="80" height="83" alt="Повременное" /></span>
		<h3>Повременное</h3>
	 	Основано на времени и ресурсах (Time &amp; Material), когда требования могут вырабатываться и изменяться в процессе реализации проекта, а трудозатраты оплачиваются исходя из количества человек, занятых на проекте, и времени, затраченного на производство функциональности разрабатываемого продукта или сервиса
	</div>

	<h3>
	Пример алгоритма работы над проектом, реализуемым с применением повременного ценообразования: </h3>
	<ul class="unstyled">
		<li> <span class="digit">1</span>
		Выявление сроков, цены, а также общих требований к системе, детализация требований, генерация начального прототипа и списка детальных требований системы версии V (где V = 0) </li>
		<li> <span class="digit">2</span>
		Совместное планирование реализации приоритетных требований из общего списка для системы версии V </li>
		<li> <span class="digit">3</span>
		Двухнедельная (или более) реализация системы версии V с требованиями, принятыми на этапе 2, и её передача заказчику </li>
		<li> <span class="digit">4</span>
		Корректирование требований (при необходимости), реорганизация списка детальных требований к системе новой версии V (где V = V + 1) </li>
		<li> <span class="digit">5</span>
		Если список требований не пуст, возврат к этапу 2, если список пуст, — выпуск финальной версии системы и её поддержка в течение согласованного времени </li>
	</ul>
</div>

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