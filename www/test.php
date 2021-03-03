<?print_r("TEST2");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Sale;
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");

//$prop=CIBlockElement::GetByID(1628)->GetNextElement()->GetProperties();
         // print "<pre>"; print_r($prop); print "</pre>";
		 
print_r(CSaleLang::GetLangCurrency('s2'));
print_r(Sale\Internals\SiteCurrencyTable::getSiteCurrency('s1'));
exit();
customCatalog::updateBasketCurrency('s2');

$arBasketItems = array();
    $dbBasketItems = CSaleBasket::GetList(
      array(),
      array(
          "FUSER_ID" => CSaleBasket::GetBasketUserID(),
          "ORDER_ID" => "NULL",
          //"LID" => SITE_ID
        ),
      false,
      false,
      array()
    );

    print_r('<pre>');
    while ($arItem = $dbBasketItems->GetNext()) {
      
      print_r($arItem);
    }
    print_r('</pre>');

exit;