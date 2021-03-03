<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<? 
if($_POST['SESS_PARAM'] && $_POST['SESS_PARAM'] !='' && $_POST['SESS_PARAM_VALUE'] && $_POST['SESS_PARAM_VALUE'] !=''){

	$_SESSION[$_POST['SESS_PARAM']] = $_POST['SESS_PARAM_VALUE'];
	echo 'ok';
}else{
	echo 'error';
} ?>