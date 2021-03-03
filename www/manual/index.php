<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Документация");
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
LocalRedirect("/manual/dicomviewer/");
// подключение служебной части эпилога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>