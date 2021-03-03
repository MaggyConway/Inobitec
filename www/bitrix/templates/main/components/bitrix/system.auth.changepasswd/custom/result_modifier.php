<?php
global $DB, $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$login = strip_tags($request->get('USER_LOGIN'));
$login = htmlspecialchars($login);

$checword = $request->get('USER_CHECKWORD');
CTimeZone::Disable();
$db_check = $DB->Query(
    "SELECT ID, LID, CHECKWORD, ".$DB->DateToCharFunction("CHECKWORD_TIME", "FULL")." as CHECKWORD_TIME ".
    "FROM b_user ".
    "WHERE LOGIN='".$DB->ForSql($login, 0)."'".
    (
        // $arParams["EXTERNAL_AUTH_ID"] can be changed in the OnBeforeUserChangePassword event
        $arParams["EXTERNAL_AUTH_ID"] <> ''?
            "	AND EXTERNAL_AUTH_ID='".$DB->ForSQL($arParams["EXTERNAL_AUTH_ID"])."' " :
            "	AND (EXTERNAL_AUTH_ID IS NULL OR EXTERNAL_AUTH_ID='') "
    )
);
CTimeZone::Enable();
if(!$res = $db_check->Fetch()){
  $arResult["UNCORREXT_LOGIN"] = "Y";
  $arResult["USER_LOGIN"] = $login;
}else{
  $arGroupPolicy = CUser::GetGroupPolicy($res['ID']);
  $arResult['MIN_LENGTH'] = $arGroupPolicy['PASSWORD_LENGTH'];
  $salt = substr($res["CHECKWORD"], 0, 8);
  if($res["CHECKWORD"] == '' || $res["CHECKWORD"] != $salt.md5($salt.$checword)){
      $arResult["UNCORREXT_CHECKWORD"] = "Y";
      $arResult["USER_LOGIN"] = $login;
  }else
    $arResult["UNCORREXT_CHECKWORD"] = "N";
}