<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobiteс LLC uses flexible pricing models for calculating software development budgets in custom projects of healthcare institutions");
$APPLICATION->SetPageProperty("keywords", "Development, software, healthcare, medicine, ordering, to order, prices, price, terms, budget");
$APPLICATION->SetPageProperty("title", "Inobitec / Principles of pricing for the estimation of custom projects for the development of software in the medical area");
$APPLICATION->SetTitle("Principles of pricing");
?><div class="simple-text">

	
	<div class="with-ico">

		<span class="ico"><img width="80" src="/bitrix/templates/main/img/price_ico_1.jpg" height="83" alt="Fix price"></span>
		<h3>Fix price</h3>
		Based on "the waterfall model", using in software developing, when all requirements are clearly formulated and do not change until the completion of all work on the project
	</div>

	
	<div class="with-ico">
		
		<span class="ico"><img width="80" src="/bitrix/templates/main/img/price_ico_2.jpg" height="83" alt="Time and materials"></span>
		<h3>Time and materials</h3>
	 	Based on "time and materials model", when requirements can be developed and changed in the course of project implementation, and labor costs are paid based on the number of people employed on the project and the time spent on producing the functionality of the developed product
	</div>

	<h3>
	An example of an algorithm for working on a project, implemented with the use of "Time and materials model" pricing: </h3>
	<ul class="unstyled">
		<li> <span class="digit">1</span>
		Identification of terms, prices, as well as general requirements for the system, specification of requirements, generation of the initial prototype and the list of detailed requirements of the system version V (where V = O) </li>
		<li> <span class="digit">2</span>
		Joint planning of implementation of priority requirements from the general list for the V-version system </li>
		<li> <span class="digit">3</span>
		A two-week (or more) implementation of a version V system with the requirements approved in step 2 and send it to the customer </li>
		<li> <span class="digit">4</span>
		Correction of requirements (if necessary), reorganization of the list of detailed requirements to the system of the new version V (where V = V + 1) </li>
		<li> <span class="digit">5</span>
		If the list of requirements is not empty, go back to Step 2, if the list is empty, - we release the final version of the system and support it for the agreed time </li>
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