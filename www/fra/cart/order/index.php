<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// if($USER->GetID()){
//   $rsUser = CUser::GetByID($USER->GetID());

//   $arUser = $rsUser->Fetch();
//   if($arUser["LID"] != SITE_ID){
//     if(SITE_ID == 's1')
//       LocalRedirect("/eng/cart/order/?".$_SERVER['QUERY_STRING']);
//     else
//       LocalRedirect("/cart/order/?".$_SERVER['QUERY_STRING']);
//   }
// }


$APPLICATION->SetTitle("Complétez la commande");
$no_f_links = true;
?><script type="text/javascript">
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
</script><?

changeDefaultPersonType();

$APPLICATION->IncludeComponent(
	"inobitec:sale.order.ajax", 
	"inobitec", 
	array(
		"COMPONENT_TEMPLATE" => "inobitec",
		"PAY_FROM_ACCOUNT" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"COUNT_DELIVERY_TAX" => "N",
		"ALLOW_AUTO_REGISTER" => "Y",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_AJAX" => "N",
		"DELIVERY_NO_SESSION" => "N",
		"TEMPLATE_LOCATION" => ".default",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"USE_PREPAYMENT" => "N",
		"ALLOW_NEW_PROFILE" => "Y",
		"SHOW_PAYMENT_SERVICES_NAMES" => "Y",
		"SHOW_STORES_IMAGES" => "N",
		"PATH_TO_BASKET" => "/cart/",
		"PATH_TO_PERSONAL" => "/personal/",
		"PATH_TO_PAYMENT" => "cart/payment/",
		"PATH_TO_AUTH" => "/auth/",
		"SET_TITLE" => "N",
		"DISABLE_BASKET_REDIRECT" => "N",
		"PRODUCT_COLUMNS" => array(
		),
		"PROP_1" => array(
		),
		"PROP_3" => array(
		),
		"PROP_2" => array(
		)
	),
	false
);?>&nbsp;<!-- /Yandex.Metrika counter --><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>