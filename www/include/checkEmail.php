<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
global $USER;
$actualUserId = $USER->GetID();
$UserId = false;
$Flag = true;

header('Content-Type: application/json');
$mail = htmlspecialchars($_GET['mail']);


$arFilterLogin = Array(
  "LOGIN"=>$mail
);
$arFilterEmail = Array(
  "EMAIL"=>$mail
);

if(!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL)){
  echo json_encode(array('rez' => "NOT_EM"), JSON_HEX_QUOT | JSON_HEX_TAG);
  return false;
}

$dbUsersLogin = CUser::GetList(($by = "NAME"), ($order = "desc"), $arFilterLogin);
$dbUsersEmail = CUser::GetList(($by = "NAME"), ($order = "desc"), $arFilterEmail);
while ($arUser = $dbUsersLogin->Fetch())
{
  //print_r($arUser["LOGIN"] . " " . $arUser['EMAIL'] . " " . $arUser["ID"] . "<br/>");
  if($arUser["LOGIN"] == $mail && $actualUserId != $arUser["ID"]){
    $Flag = false;
  }
}

while ($arUser = $dbUsersEmail->Fetch())
{
  //print_r($arUser["LOGIN"] . " " . $arUser['EMAIL'] . " " . $arUser["ID"] . "<br/>");
  if($arUser["EMAIL"] == $mail && $actualUserId != $arUser["ID"]){
    $Flag = false;
  }
}

if(!$Flag){
  $regMail = "EXIST";    
}else{
  $regMail = "YES";
}

echo json_encode(array('rez' => $regMail), JSON_HEX_QUOT | JSON_HEX_TAG);


require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');