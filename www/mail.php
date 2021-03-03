<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>
<?
echo mail('inobitektest@gmail.com, gleb@agrweb.ru', 'Письма отправляются', 'Текст письма', 'From: webmaster@agrweb.ru');
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>