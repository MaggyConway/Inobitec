<?php
class inobitecLicense
{
  protected $user = "user";
  protected $pass = "user";
  
  protected $url = "http://ttk.inobitec.com:8090/adminServer";
  protected $urlArr = false;
  protected $urlCount = false;
  
  protected $noticeEmail = false;
  
  protected $errorsLog = array();
  
  protected $authToken = false;
  protected $refreshToken = false;
  protected $errorFlagOperation = false;
  
  protected $authTokenDecode = false;
  protected $refreshTokenDecode = false;
  
  public function __construct($user = false, $pass = false, $url = false, $AuthToken = false, $refreshToken = false)
  {
    $this->getInfoFromBD();
    if($this->authToken && $this->refreshToken){
      $this->checkTokens();
    }else{
      $this->generateAuthToken();
    }
  }
  
  public function getOperationFlag(){
    return $this->errorFlagOperation;
  }
  
  protected function getInfoFromBD(){
    $this->authToken = \COption::GetOptionString( "askaron.settings", "UF_LICAUTHTOKEN" );
    $this->refreshToken = \COption::GetOptionString( "askaron.settings", "UF_LICREFRESHTOKEN" );
    //$this->url = \COption::GetOptionString( "askaron.settings", "UF_LICURL" );
    $this->user = \COption::GetOptionString( "askaron.settings", "UF_LICUSER" );
    $this->pass = \COption::GetOptionString( "askaron.settings", "UF_LICPASS" );
    $this->urlArr = \COption::GetOptionString( "askaron.settings", "UF_URL_ARR" );
    $this->noticeEmail = \COption::GetOptionString( "askaron.settings", "UF_NOTICE_MAIL" );
    $this->getNextUrl();
  }
  
  protected function addToLog($msg){

   file_put_contents($_SERVER['DOCUMENT_ROOT']."/ConnToApi/logLicServ.log",
                     date("Y-m-d H:i:s")
                     . $msg . "\r\n",
                     FILE_APPEND);
  } 
  
  protected function getNextUrl(){
    if(false === $this->urlCount)
      $this->urlCount = 0;
    else{
      if(!isset($this->urlArr[$this->urlCount]))
        return false;
      $this->urlCount++;
    }
    if(isset($this->urlArr[$this->urlCount])){
      $this->url = $this->urlArr[$this->urlCount];
    }else{
      return false;
    }
    
    return true;
  }
  
  protected function setInfoToBD(){
    if ( CModule::IncludeModule("askaron.settings") )
    {
      $arUpdateFields = array(
          "UF_LICAUTHTOKEN" => $this->authToken,
          "UF_LICREFRESHTOKEN" => $this->refreshToken,
      );
      $obSettings = new CAskaronSettings;
      $res = $obSettings->Update( $arUpdateFields );
    }
  }
  
  public function getAuthToken(){
    return $this->authToken;
  }
  
  public function getRefreshToken(){
    return $this->refreshToken;
  }
  
  protected function checkTokens(){
    $this->decodeTokens();

    if($this->authTokenDecode["payload"]["exp"] < time()){
      if($this->refreshTokenDecode["payload"]["exp"] < time()){
        $this->generateAuthToken();
      }else{
        $this->refreshTokens();
      }
    }
  }
  
  protected function generateAuthToken(){
    $data = array(
        "operation" => "login");
    $rez = $this->sendRequest($data);
    if(isset($rez["accessToken"])){
      $this->authToken = $rez["accessToken"];
      $this->refreshToken = $rez["refreshToken"];
    }
    $this->setInfoToBD();
    return $rez;
  }
  
  public function refreshTokens(){
    $data = array(
        "operation" => "refresh_token",
        "refresh_token" => $this->refreshToken);
    $rez = $this->sendRequest($data);
    $this->authToken = $rez["accessToken"];
    $this->refreshToken = $rez["refreshToken"];
    if(!$this->authToken)
      $this->generateAuthToken();
    $this->setInfoToBD();
  }
  
  protected function decodeTokens(){
    $this->authTokenDecode = $this->parseJWT($this->authToken);
    $this->refreshTokenDecode = $this->parseJWT($this->refreshToken);
  }
  
  public function createClient($legalForm, $name, $email, $taxId = false, $contact = false, $phone = false, $legalAddress = false){
    $data = array(
        "operation" => "add_client",
        "legal_form" => $legalForm,
        "e_mail" => $email,
        "name" => $name);
    if($taxId)
      $data["tax_id"] = $taxId;
    if($contact)
      $data["contact_person"] = $contact;
    if($phone)
      $data["phone"] = $phone;
    if($legalAddress)
      $data["legal_address"] = $legalAddress;
    return $this->sendRequest($data);
  }
  
  public function editClient($id, $legalForm, $name, $email, $taxId = false, $contact = false, $phone = false){
    $data = array(
        "accessToken" => $this->authToken,
        "id" => $id,
        "operation" => "edit_client",
        "legal_form" => $legalForm,
        "e_mail" => $email,
        "name" => $name);
    if($taxId)
      $data["tax_id"] = $taxId;
    if($contact)
      $data["contact_person"] = $contact;
    if($phone)
      $data["phone"] = $phone;
    return $this->sendRequest($data);
  }
  
  public function getProductFeatures($name){
    $data = array(
        "product_name" => $name,
        "operation" => "get_product_features");
    return $this->sendRequest($data);
  }
          
  public function getLlicenseKeyTypes(){
    $data = array(
        "operation" => "get_license_key_types");
    return $this->sendRequest($data);
  }
  
  public function createNewPermanentLicense($clientId, $type, $productName, $features = false, $years = false, $days = false){
    $data = array(
        "operation" => "create_new_permanent_license",
        "client_id" => $clientId,
        "type" => $type, 
        "product_name" => $productName,
        );
    if($features)
      $data["features"] = $features;
    if($years)
      $data["support_year_count"] = $years;
    if($days)
      $data["support_day_count"] = $days;
    return $this->sendRequest($data);
  }
  
  public function createNewOfflinetLicense($clientId, $type, $productName, $deviceKey, $years = false, $connections, $activation_date = false, $days = false){
    $data = array(
        "operation" => "create_new_offline_License",
        "client_id" => $clientId,
        "type" => $type,
        "device_key" => $deviceKey,
        "product_name" => $productName,
        "connection_count" => $connections
        );
    if($activation_date)
      $data["activation_date"] = $activation_date;
    if($years)
      $data["support_year_count"] = $years;
    if($days)
      $data["support_day_count"] = $days;
    return $this->sendRequest($data);
  }
  
   public function updatePermanentLicense($permLicId, $features = false, $addDays = false){
    $data = array(
        "operation" => "update_permanent_license",
        "perm_lic_id" => $permLicId);
    if($features && is_array($features))
      $data["features"] = $features;
    if($addDays)
      $data["support_day_count"] = $addDays;
    return $this->sendRequest($data);
  }
  
  
  
  public function changePermanentLicense($permLicId, $productName, $features = false, $type, $years = false, $days = false){
    $data = array(
        "operation" => "change_permanent_license",
        "perm_lic_id" => $permLicId,
        "product_name" => $productName,
        "features" => $features,
        "type" => $type);
    if($years)
      $data["support_year_count"] = $years;
    if($days)    
      $data["support_day_count"] = $days;
    return $this->sendRequest($data);
  }
  
  public function getClientLicenses($clientId){
    $data = array(
        "operation" => "get_client_licenses",
        "client_id" => $clientId);
    return $this->sendRequest($data);
  }
  
  public function getClients($filters = false){
    $data = array(
        "operation" => "get_clients");
    if(is_array($filters) && count($filters) > 0)
      $data["filters"] = $filters;
    return $this->sendRequest($data);
  }
  
  public function getOfflineLicenseFile($id){
    $data = array(
        "operation" => "get_offline_license_file",
        "license_id" => $id,
        );
    return $this->sendRequest($data);
  }
  
  public function parseJWT($token){
    $tokenParts = explode(".", $token);
    $tokenHeader = json_decode(base64_decode($tokenParts[0]),true);
    $tokenPayload = json_decode(base64_decode($tokenParts[1]),true);
    return array("header" => $tokenHeader, "payload" => $tokenPayload);
  }
  
  protected function sendRequest($data){
    $rez = $this->sendCurlRequest($data);
    
    //Проверка на доступность сервера (если ответа нет, и еще не перебранны все сервера)
    while(!$rez["success"] && $this->getNextUrl()){
      $this->addToLog(print_r($rez,true));
      $rez = $this->sendCurlRequest($data);
    }
    
    //Если есть ошибки - собрать 
    if(isset($rez["answer"]["error"])){
      $this->errorsLog[$this->urlCount]["url"] = $this->url;
      $this->errorsLog[$this->urlCount]["errors"]["errorsFromServer"][] = array("data" => $data, "errorMsg" => $rez["answer"]["error"]);
    }
      
    //Если неверный токен - попробовать еще раз переавторизоваться
    //if($rez["answer"]["error"] == "Token is incorrect" || $rez["answer"]["error"] == "Refresh token is not defined"){
    if($rez["answer"]["error"] == "Token is incorrect"){
      $this->addToLog(print_r($rez,true));
      $this->generateAuthToken();
      $rez = $this->sendCurlRequest($data);
    }
    
    //Ставим флаг ощибки выполнения операции
    if(!$rez["success"] || isset($rez["answer"]["error"])){
      $this->errorFlagOperation = true;
    }else{
      $this->errorFlagOperation = false;
    }
    $this->addToLog(print_r($data,true));
    $this->addToLog(print_r($rez,true));
    return $rez["answer"];
    
  }
  
  protected function sendCurlRequest($data){
    $data_string = json_encode($data);
    $ch = curl_init($this->url);
    
    $headers = [];
    
    if($data["operation"] == 'login'){
      curl_setopt($ch, CURLOPT_USERPWD, $this->user . ":" . $this->pass);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
      );
    }elseif($data["operation"] == 'refresh_token'){
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
      );
    }else{
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string),
        "Authorization: Bearer ".$this->authToken)
      );
    }
    
    
    
    
    /*print_r($data);
    print_R('<br>');*/
    
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // this function is called by curl for each header received
    curl_setopt($ch, CURLOPT_HEADERFUNCTION,
      function($curl, $header) use (&$headers)
      {
        $len = strlen($header);
        $header = explode(':', $header, 2);
        if (count($header) < 2) // ignore invalid headers
          return $len;

        $headers[strtolower(trim($header[0]))][] = trim($header[1]);

        return $len;
      }
    );

    $result = curl_exec($ch);
    if (curl_error($ch)) {
      //print_r('Ошибка');
      //print_R('<br>');
      $error_msg = curl_error($ch);
      $this->errorsLog[$this->urlCount]["url"] = $this->url;
      $this->errorsLog[$this->urlCount]["errors"]["connectToServer"][] = array("data" => $data, "errorMsg" => $error_msg);
      return(array("success" => false, "error" => $error_msg));
      
    }else{
      //print_r('Нет Ошибки');
      //print_R('<br>');
    }
    /*print_r("ответ сервера: <<<   ");
    print_r($result);
    print_r("   >>>");
    print_R('<br>');*/

    //print_r("<br/>");
    curl_close($ch);
    if($data["operation"] == 'get_offline_license_file')
      return array("success" => true, "answer" => array('binary-data' => $result, 'headers' => $headers));
    else 
      return array("success" => true, "answer" => json_decode($result, true));
  }
  
  
  public function prepareErrorInfo(){
    $errorMsg = "";
    foreach($this->errorsLog as $keyServer => $serverErrors){
      $errorMsg .= "<br><b>Ошибки при попытке подключится к серверу: " . $serverErrors["url"] . ":</b><br>";
      if(count($serverErrors["errors"]["connectToServer"]) > 0){
        $errorMsg .= "  Ошибки соединения:<br>";
        foreach($serverErrors["errors"]["connectToServer"] as $keyConErr => $conErr){
          $errorMsg .= "  ". $keyConErr .") Ошибка: ##".$conErr["errorMsg"]."## <br> Данные передачи: ##".print_r($conErr["data"],true)."##";
        }
      }
      if(count($serverErrors["errors"]["errorsFromServer"]) > 0){
        $errorMsg .= "  Ошибки полученные от сервера:<br>";
        foreach($serverErrors["errors"]["errorsFromServer"] as $keyServErr => $ServErr){
          $errorMsg .= "  ". $keyConErr .") Ошибка: ##".$ServErr["errorMsg"]."## <br> Данные передачи: ##".print_r($ServErr["data"],true)."##";
        }
      }
    }
    return array("email" => $this->noticeEmail, "errorMsg" => $errorMsg);
  }
  
  
  
}


