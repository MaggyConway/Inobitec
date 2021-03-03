<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$result = array();

if ($_POST['auth'] == 'Y') 
{
	$login = trim($_POST['login']);
	$password = trim($_POST['password']);
	$remember = $_POST['remember'];
	global $USER;
	$authRes =  $USER->Login($login, $password, $remember);
 
	if ($authRes['TYPE'] == 'ERROR') {
		$result['message'] = str_replace(['<br>', '.'], ['',''], $authRes['MESSAGE']);
		$result['success'] = false;
	} else {
		$result['success'] = true;
	}
	echo json_encode($result);
	die();
}
?>