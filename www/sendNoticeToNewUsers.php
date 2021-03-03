<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
exit();
print_r(time());

addToLog("WTF");
function echoScriptPath() {
    list($scriptPath) = get_included_files();
    return $scriptPath;
}
//echoScriptPath();
$endTime = 1566386149 + 180;
$firstUser = 0;
$maxNums = 20;
if(isset($_GET['firstUser']) && $_GET['firstUser'] > 0 ){
  $firstUser = $_GET['firstUser'];
}
/*if(time() > $endTime)
  exit;*/

addToLog(" firstUser: " . $firstUser);
$filter = array(
   ">ID" => $firstUser,
    "UF_SEND_NOTICE" => 1,
);
$userParams = array(
    'SELECT' => array('UF_SEND_NOTICE', 'UF_USER_TYPE', 'UF_COMPANY_NAME'),
    'NAV_PARAMS' => array(
        'nTopCount' => $maxNums
    ),
);
$lastUser = $firstUser;
$usersResult = CUser::GetList(($by="ID"), ($order="ASC"), $filter, $userParams);
$usersNums = $usersResult->SelectedRowsCount();
$password_chars = array(
                    "abcdefghijklnmopqrstuvwxyz",
                    "ABCDEFGHIJKLNMOPQRSTUVWXYZ",
                    "0123456789",
                );
$password_min_length = 8;

while ($user = $usersResult->getNext())
{
    if(in_array(1, CUser::GetUserGroup($user["ID"])))
      continue;
    $pass = randString($password_min_length, $password_chars);
    addToLog(" USER_ID: " . $user["ID"]);
    print_r("<br>");
    print_r($user);
    print_r("<br>");
    $lastUser = $user["ID"];
    print_r($pass);
    print_r("<br>");
    $CUser = new CUser;
    
    
    $fields = Array(
      "PASSWORD"          => $pass,
      "CONFIRM_PASSWORD"  => $pass,
      "UF_SEND_NOTICE"    => 0,  
      );
    $CUser->Update($user["ID"], $fields);
    $oSite = CSite::GetByID($user["LID"]);
    $arrSite = $oSite->Fetch();
    
    $rsUser = CUser::GetByID($user["ID"]);
    $arUser = $rsUser->Fetch();
    
    $arEventFields = array(
        "NAME"                => $user["UF_USER_TYPE"] == 2 ? $user["~UF_COMPANY_NAME"] : $user["~NAME"],
        "SITE_NAME"             => $arrSite["SITE_NAME"],
        "SERVER_NAME"            => $_SERVER['SERVER_NAME'],
        "LOGIN"         => $user["LOGIN"],
        "EMAIL"           => $user["EMAIL"],
        "PASSWORD_GENERATED"          => $pass,
        "CHECKWORD"          => $arUser["CHECKWORD"],
        "URL_LOGIN"         => $user["LOGIN"],
        "SALE_EMAIL"                 => COption::GetOptionString("sale","order_email"),
        
        );
    $eventId = CEvent::Send("USER_INFO_WELCOME", $user["LID"], $arEventFields);
    usleep(500000);
    
}
if($usersNums > 0){
  $http = 'http://';
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') { $http = "https://"; } 
  //$output = shell_exec('php ' . echoScriptPath());
  //echo "<pre>$output</pre>";
  print_r("<br>");
  print_r($http . $_SERVER['HTTP_HOST'] . "/sendNoticeToNewUsers.php?firstUser=".$lastUser);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $http . $_SERVER['HTTP_HOST'] . "/sendNoticeToNewUsers.php?firstUser=".$lastUser);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);
  print_r($output);
  
}