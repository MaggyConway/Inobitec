<?
$newPageCSS = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobitec company offers its own products: Inobitec DICOM Viewer, Inobitec Web DICOM Viewer, Inobitec DICOM Server (PACS)");
$APPLICATION->SetPageProperty("keywords", "DICOM, dicom, viewer, web dicom viewer, dicom viewer for mac, dicom viewer for windows, dicom viewer for linux, PACS, pacs, server, software, medicine, medical, healthcare");
$APPLICATION->SetPageProperty("title", "Productos propios de Inobitec / Inobitec Visor DICOM, Inobitec Web Visor DICOM, DICOM Inobitec Servidor (PACS)");
$APPLICATION->SetTitle("Productos");

// echo "<pre>"; var_dump($_SERVER["DOCUMENT_ROOT"]); echo "</pre>";
?>
  <section class="products" style="margin-top: 0px;">
	<div class="products__text">
		<?
		// включаемая область для раздела
		$APPLICATION->IncludeFile(SITE_DIR."/includes/products_page_text.php", Array(), Array(
		    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
		    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
		    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
		    ));
		?>
	</div>
   <?
      $no_f_links = true;
      require($_SERVER["DOCUMENT_ROOT"]."/includes/mp_solutions.php");
    ?>
  </section>  

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