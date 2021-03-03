<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

if ($_POST['change'] == 'Y') 
{
	if ($USER->IsAuthorized()) {
		$USER_ID = $USER->GetID();
		$obUser = new CUser;
		$arFields = array(
			"PASSWORD" => $_POST["password"],
			"CONFIRM_PASSWORD" => $_POST["confirm_password"]
		);

		if ($obUser->Update($USER_ID, $arFields)) {
			$result["success"] = true;
		} else {
			$result["success"] = false;
			$result["message"] = strip_tags($obUser->LAST_ERROR);
		}
	}
}
echo json_encode($result);
die();
?>