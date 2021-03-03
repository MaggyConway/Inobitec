<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if ($_POST['send'] == 'Y') 
{
	$login = trim($_POST['login']);
	$res = $USER->SendPassword($login, $login);
	echo json_encode($res);
	die();
}
?>