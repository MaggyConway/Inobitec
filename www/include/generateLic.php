<?
//require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if(!CModule::IncludeModule("iblock"))
  return false; 
if(!CModule::IncludeModule("sale"))
  return false; 
if(!CModule::IncludeModule("catalog"))
  return false;

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SALE_MODULE_NOT_INSTALL"));
	return;
}

global $APPLICATION, $USER;

$APPLICATION->RestartBuffer();

$bUseAccountNumber = \Bitrix\Sale\Integration\Numerator\NumeratorOrder::isUsedNumeratorForOrder();

$ORDER_ID = urldecode(urldecode($_REQUEST["ORDER_ID"]));
$hash = isset($_REQUEST["HASH"]) ? $_REQUEST["HASH"] : null;

$registry = \Bitrix\Sale\Registry::getInstance(\Bitrix\Sale\Registry::REGISTRY_TYPE_ORDER);
/** @var \Bitrix\Sale\Order $orderClassName */
$orderClassName = $registry->getOrderClassName();

$arOrder = false;
$checkedBySession = false;
if (!$USER->IsAuthorized() && is_array($_SESSION['SALE_ORDER_ID']) && empty($hash))
{
	$realOrderId = 0;

	if ($bUseAccountNumber)
	{
		$dbRes = $orderClassName::getList([
			'filter' => [
				//"LID" => SITE_ID,
				"ACCOUNT_NUMBER" => $ORDER_ID
			],
			'order' => [
				"DATE_UPDATE" => "DESC"
			]
		]);
		$arOrder = $dbRes->fetch();

		if ($arOrder)
		{
			$realOrderId = intval($arOrder["ID"]);
		}
	}
	else
	{
		$realOrderId = intval($ORDER_ID);
	}

	$checkedBySession = in_array($realOrderId, $_SESSION['SALE_ORDER_ID']);
}

if ($bUseAccountNumber && !$arOrder)
{
	$arFilter = array(
		//"LID" => SITE_ID,
		"ACCOUNT_NUMBER" => $ORDER_ID
	);

	if (empty($hash))
	{
		$arFilter["USER_ID"] = intval($USER->GetID());
	}

	$dbRes = $orderClassName::getList([
		'filter' => $arFilter,
		'order' => [
			"DATE_UPDATE" => "DESC"
		]
	]);

	$arOrder = $dbRes->fetch();
}

if (!$arOrder)
{
	$arFilter = array(
		//"LID" => SITE_ID,
		"ID" => $ORDER_ID
	);
	if (!$checkedBySession && empty($hash))
		$arFilter["USER_ID"] = intval($USER->GetID());

	$dbRes = $orderClassName::getList([
		'filter' => $arFilter,
		'order' => [
			"DATE_UPDATE" => "DESC"
		]
	]);

	$arOrder = $dbRes->fetch();
}
$currencyType = ($arOrder["CURRENCY"] == "RUB")?"&#8381;":"$";
if ($arOrder && $arOrder["PAYED"] == "Y"){
  //собираем pdf
  
  /*$db_ptype = CSalePaySystem::GetList($arOrder = Array("SORT"=>"ASC", "PSA_NAME"=>"ASC"), Array("ID"=>2), array() , array(), array("BILL_PATH_TO_STAMP", "SELLER_COMPANY_DIR_SIGN", "SELLER_COMPANY_ACC_SIGN"));
  
  while ($ptype = $db_ptype->Fetch()){
    print_r($ptype);
  }
  exit;*/
  $arrOrderProps = array();
  $db_props = CSaleOrderPropsValue::GetOrderProps($arOrder["ID"]);
  while($db_prop = $db_props->Fetch()){
    $arrOrderProps[$db_prop["CODE"]] = $db_prop;
    if($db_prop["IS_PAYER"] == "Y"){
      $Name = $db_prop["VALUE"];
    }
  }
  if($arrOrderProps["LEGAL_FORM"]["VALUE"] && $arrOrderProps["LEGAL_FORM"]["VALUE"] != '' )
    $licenseeName = $arrOrderProps["LEGAL_FORM"]["VALUE"] . " " . getFirmName($Name, $arrOrderProps["LEGAL_FORM"]["VALUE"]);
  else
    $licenseeName = $Name;
  $licenseeAddress = '';
  $licenseeEmail = "Email: " . $arrOrderProps["EMAIL"]["VALUE"];

  switch($arOrder["PERSON_TYPE_ID"]){
    case 1:
      $licenseeName .= ", " . Loc::getMessage("GENERATELIC_CUSTOM_BILL_INN", array("#INN#" => $arrOrderProps["INN"]["VALUE"])) . ", " . Loc::getMessage("GENERATELIC_CUSTOM_BILL_KPP", array("#KPP#" => $arrOrderProps["KPP"]["VALUE"]));
      $licenseeAddress = $arrOrderProps["ADDRESS"]["VALUE"];
      break;
    case 2:
      $licenseeAddress = $arrOrderProps["MAIL_ADDRESS"]["VALUE"];
      break;
    case 3:
      $licenseeName .= ", " . Loc::getMessage("GENERATELIC_CUSTOM_BILL_INN", array("#INN#" => $arrOrderProps["INN"]["VALUE"]));
      $licenseeAddress = $arrOrderProps["ADDRESS"]["VALUE"];
      break;
    case 4:
      $licenseeName .= ", " . Loc::getMessage("GENERATELIC_CUSTOM_BILL_INN", array("#INN#" => $arrOrderProps["INN"]["VALUE"]));
      $licenseeAddress = $arrOrderProps["ADDRESS"]["VALUE"];
      break;
  }

  $orderCount = 300 + $arOrder["ID"];
  $dateCreate = (SITE_ID == "s1")? $arOrder["DATE_INSERT"]->format('d.m.Y'): $arOrder["DATE_INSERT"]->format('F d, Y');
  if($arOrder["PAYED"] == "Y")
    $datePayed = (SITE_ID == "s1")? $arOrder["DATE_PAYED"]->format('d.m.Y'): $arOrder["DATE_PAYED"]->format('F d, Y');
  else{
    LocalRedirect(SITE_DIR . 'personal/');
    $datePayed = "неоплачено, TODO: игнор";
  }
  
  $INN = \COption::GetOptionString( "askaron.settings", "UF_INN");
  $KPP = \COption::GetOptionString( "askaron.settings", "UF_KPP");
  $orderOptionsArr = [
      's1' => [
          'FIRM_NAME' => \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME"),
          'ADDRESS' => \COption::GetOptionString( "askaron.settings", "UF_ADDRESS"),
          'LICENSE_1ST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LICENSE_1ST_TEXT"),
          'LIC_NOTICE' => \COption::GetOptionString( "askaron.settings", "UF_LIC_NOTICE"),
          'LIC_LAST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_LAST_TEXT"),
          'CURRENCY' => 'RUB',
      ],
      's2' => [
          'FIRM_NAME' => \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_EN"),
          'ADDRESS' => \COption::GetOptionString( "askaron.settings", "UF_ADDRESS_EN"),
          'LICENSE_1ST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_1ST_TEXT_EN"),
          'LIC_NOTICE' => \COption::GetOptionString( "askaron.settings", "UF_LIC_NOTICE_EN"),
          'LIC_LAST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_LAST_TEXT_EN"),
          'CURRENCY' => 'USD',
      ],
      's3' => [
          'FIRM_NAME' => \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_FR"),
          'ADDRESS' => \COption::GetOptionString( "askaron.settings", "UF_ADDRESS_FR"),
          'LICENSE_1ST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_1ST_TEXT_FR"),
          'LIC_NOTICE' => \COption::GetOptionString( "askaron.settings", "UF_LIC_NOTICE_FR"),
          'LIC_LAST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_LAST_TEXT_FR"),
          'CURRENCY' => 'EUR',
      ],
      's4' => [
          'FIRM_NAME' => \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_DE"),
          'ADDRESS' => \COption::GetOptionString( "askaron.settings", "UF_ADDRESS_DE"),
          'LICENSE_1ST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_1ST_TEXT_DE"),
          'LIC_NOTICE' => \COption::GetOptionString( "askaron.settings", "UF_LIC_NOTICE_DE"),
          'LIC_LAST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_LAST_TEXT_DE"),
          'CURRENCY' => 'EUR',
      ],
      's5' => [
          'FIRM_NAME' => \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_ES"),
          'ADDRESS' => \COption::GetOptionString( "askaron.settings", "UF_ADDRESS_ES"),
          'LICENSE_1ST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_1ST_TEXT_ES"),
          'LIC_NOTICE' => \COption::GetOptionString( "askaron.settings", "UF_LIC_NOTICE_ES"),
          'LIC_LAST_TEXT' => \COption::GetOptionString( "askaron.settings", "UF_LIC_LAST_TEXT_ES"),
          'CURRENCY' => 'EUR',
      ],
  ];
  $FIRMNAME = $orderOptionsArr[SITE_ID]['FIRM_NAME'];
  $FIRMADDRESS = $orderOptionsArr[SITE_ID]['ADDRESS'];
  
  $TEXT1 = $orderOptionsArr[SITE_ID]['LICENSE_1ST_TEXT'];
  $NOTICE = $orderOptionsArr[SITE_ID]['LIC_NOTICE'];
  $TEXT2 = $orderOptionsArr[SITE_ID]['LIC_LAST_TEXT'];

  $basketItems = array();
  $rsBasketItems = CSaleBasket::GetList(array(), array("ORDER_ID" => $arOrder["ID"]));
  while ($arBasketItem = $rsBasketItems->Fetch()) {
      $arBasketItem["PROPS"] = Array();
      $dbProp = CSaleBasket::GetPropsList(Array(), array("BASKET_ID" => $arBasketItem["ID"]));
      while($arProp = $dbProp -> GetNext()){
       $arBasketItem["PROPS"][] = $arProp;
      }
      $basketItems[] = $arBasketItem;
  }
  $basketItems = sortOrder($basketItems);
 
  if (!CSalePdf::isPdfAvailable())
	die();

  /** @var CSaleTfpdf $pdf */
  $pdf = new CSalePdf('P', 'pt', 'A4');
  
  $pageWidth  = $pdf->GetPageWidth();
  $pageHeight = $pdf->GetPageHeight();

  $pdf->AddFont('Font', '', 'pt_sans-regular.ttf', true);
  $pdf->AddFont('Font', 'B', 'pt_sans-bold.ttf', true);

  $fontFamily = 'Font';
  $fontSize   = 10.5;

  $margin = array(
      'top' => intval($params['BILL_MARGIN_TOP'] ?: 15) * 72/25.4,
      'right' => intval($params['BILL_MARGIN_RIGHT'] ?: 15) * 72/25.4,
      'bottom' => intval($params['BILL_MARGIN_BOTTOM'] ?: 15) * 72/25.4,
      'left' => intval($params['BILL_MARGIN_LEFT'] ?: 20) * 72/25.4
  );

  $width = $pageWidth - $margin['left'] - $margin['right'];

  $pdf->SetDisplayMode(100, 'continuous');
  $pdf->SetMargins($margin['left'], $margin['top'], $margin['right']);
  $pdf->SetAutoPageBreak(true, $margin['bottom']);
  //$pdf->SetFont($fontFamily, '', $fontSize);

  $pdf->AddPage();
  $pdf->SetFont($fontFamily, 'B', $fontSize * 0.75);
  $y0 = $pdf->GetY();
  ///------------------------------------------------------------------------///
  $pdf->Cell(85, 12, CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_LICENSOR")));
  $x1 = $pdf->GetX();
  $y1 = $pdf->GetY();
  $pdf->SetFont($fontFamily, '', $fontSize * 0.75);
  $str = CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_LICENSOR_INFO", array("#FIRMNAME#" => $FIRMNAME, "#INN#" => $INN, "#KPP#" => $KPP, "#ADDRESS#" => $FIRMADDRESS)));
  $n = 0;
  while ($pdf->GetStringWidth($str))
  {
      if($n > 0)
        $pdf->Cell(85, 12, '');
      list($string, $str) = $pdf->splitString($str, 395);
      $pdf->Cell(395, 12, $string, 0, 0, 'L');
      $x2 = $pdf->GetX();
      $pdf->Ln();
      $n++;
  }
  $y2 = $pdf->GetY();
  $pdf->Line($x1, $y2, $x2, $y2);
  
  $pdf->SetFont($fontFamily, 'B', $fontSize * 0.75);
  $pdf->Cell(85, 12, CSalePdf::prepareToPdf(CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_LICENSEE"))));
  $x1 = $pdf->GetX();
  $y1 = $pdf->GetY();
  $pdf->SetFont($fontFamily, '', $fontSize * 0.75);
  $str2 = CSalePdf::prepareToPdf($licenseeName);
  $n = 0;
  while ($pdf->GetStringWidth($str2))
  {
      if($n > 0)
        $pdf->Cell(85, 12, '');
      list($string2, $str2) = $pdf->splitString($str2, 395);
      $pdf->Cell(395, 12, $string2, 0, 0, 'L');
      $pdf->Ln();
      $n++;
  }
  $pdf->Cell(85, 12, CSalePdf::prepareToPdf(''));
  $str3 = CSalePdf::prepareToPdf($licenseeAddress);
  $n = 0;
  while ($pdf->GetStringWidth($str3))
  {
      if($n > 0)
        $pdf->Cell(85, 12, '');
      list($string3, $str3) = $pdf->splitString($str3, 395);
      $pdf->Cell(395, 12, $string3, 0, 0, 'L');
      $pdf->Ln();
      $n++;
  }
  $pdf->Cell(85, 12, CSalePdf::prepareToPdf(''));
  $str4 = CSalePdf::prepareToPdf($licenseeEmail);
  $n = 0;
  while ($pdf->GetStringWidth($str4))
  {
      if($n > 0)
        $pdf->Cell(85, 12, '');
      list($string4, $str4) = $pdf->splitString($str4, 395);
      $pdf->Cell(395, 12, $string4, 0, 0, 'L');
      $x2 = $pdf->GetX();
      $pdf->Ln();
      $n++;
  }
  $y2 = $pdf->GetY();
  $pdf->Line($x1, $y2, $x2, $y2);

  $pdf->SetFont($fontFamily, 'B', $fontSize * 0.75);
  $height_str5 = (SITE_ID == 's1')?12:10;
  $str5_0 = CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_BASIS"));
  $n = 0;
  $Xtmp = $pdf->GetX();
  $Ytmp = $pdf->GetY();
  while ($pdf->GetStringWidth($str5_0))
  {
      list($string5_0, $str5_0) = $pdf->splitString($str5_0, 85);
      $pdf->Cell(85, $height_str5, $string5_0, 0, 0, 'L');
      $x1 = $pdf->GetX();
      $pdf->Ln();
      $n++;
  }
  $pdf->SetFont($fontFamily, '', $fontSize * 0.75);
  $y2_0 = $pdf->GetY();
  //$pdf->Cell(50, 12, CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_BASIS")));
  //$pdf->setX(50);
  $pdf->setY($Ytmp);
  //$x1 = $pdf->GetX();
  $y1 = $pdf->GetY();
  $str5 = CSalePdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_TITLE", array('#NUM#' => $orderCount, '#DATE#' => $dateCreate)));
  $n = 0;
  if(SITE_ID == 's2')
    $pdf->Ln();
  while ($pdf->GetStringWidth($str5))
  {
      //if($n > 0)
        $pdf->Cell(85, $height_str5, '');
      list($string5, $str5) = $pdf->splitString($str5, 395);
      $pdf->Cell(395, $height_str5, $string5, 0, 0, 'L');
      $x2 = $pdf->GetX();
      $pdf->Ln();
      $n++;
  }
  $y2 = max($pdf->GetY(),$y2_0);
  $pdf->Line($x1, $y2, $x2, $y2);
  
  $pdf->Ln();
  $pdf->Ln();
  ///------------------------------------------------------------------------///
  
 
  ///------------------------------------------------------------------------///
  $pdf->SetFont($fontFamily, 'B', $fontSize * 1.5);
  
  $billNo_tmp = CSalePdf::prepareToPdf(loc::getMessage("GENERATELIC_CUSTOM_BILL_LIC_NAME", array('#NUM#' => $orderCount, '#DATE#' => $datePayed)));

  $billNo_width = $pdf->GetStringWidth($billNo_tmp);
  $pdf->Cell(0, 20, $billNo_tmp, 0, 0, 'C');
  
  $pdf->Ln();
  $pdf->Ln();
  
  $pdf->SetFont($fontFamily, '', $fontSize);
  $pdf->Write(12, HTMLToTxt(preg_replace(
    array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
    CSalePdf::prepareToPdf($TEXT1)
  ), '', array(), 0));
  ///------------------------------------------------------------------------///
  $pdf->Ln();
  $pdf->Ln();
  
  //ADDTESTLINE 1
  $pdf->Ln();
  
  $pdf->SetFont($fontFamily, '', $fontSize * 0.8);
  ///------------------------------------------------------------------------///
  $tableSize = array("NUM" => 20, "PRODUCT_NAME" => 310, "AMOUNT" => 40, "PRICE" => 60, "SUMM" => 60);
  $num = 0;
  $x0 = $pdf->getX();
  $y0 = $pdf->getY();
  $y2 = $pdf->getY();
  $pdf->Line($x0, $y0, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $y0);
  $pdf->Cell($tableSize["NUM"], 12, "№", 0, 0, 'C');
  $titleNameStr = $pdf::prepareToPdf(loc::getMessage("GENERATELIC_CUSTOM_PRODUCT_NAME"));
  $n = 0;
  while ($pdf->GetStringWidth($titleNameStr))
  {
      if($n > 0){
        $pdf->Ln();
        $pdf->Cell($tableSize["NUM"], 12, '');
      }
      list($titleStringName, $titleNameStr) = $pdf->splitString($titleNameStr, $tableSize["PRODUCT_NAME"]);
      $pdf->Cell($tableSize["PRODUCT_NAME"], 12, $titleStringName, 0, 0, 'C');
      $n++;
  }
  $y2 = max(array($pdf->getY(), $y2));
  $pdf->setXY($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"], $y0);
  
  
  $titleCountStr = $pdf::prepareToPdf(loc::getMessage("GENERATELIC_CUSTOM_PRODUCT_NUM"));
  $n = 0;
  while ($pdf->GetStringWidth($titleCountStr))
  {
      if($n > 0){
        $pdf->Ln();
        $pdf->Cell($tableSize["NUM"]+$tableSize["PRODUCT_NAME"], 12, '');
      }
      list($titleStringCount, $titleCountStr) = $pdf->splitString($titleCountStr, $tableSize["AMOUNT"]);
      $pdf->Cell($tableSize["AMOUNT"], 12, $titleStringCount, 0, 0, 'C');
      $n++;
  }
  $y2 = max(array($pdf->getY(), $y2));
  $pdf->setXY($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], $y0);
  
  $titlePriceStr = $pdf::prepareToPdf(loc::getMessage("GENERATELIC_CUSTOM_PRODUCT_PRICE").$currencyType);
  $n = 0;
  while ($pdf->GetStringWidth($titlePriceStr))
  {
      if($n > 0){
        $pdf->Ln();
        $pdf->Cell($tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], 12, '');
      }
      list($titleStringPrice, $titlePriceStr) = $pdf->splitString($titlePriceStr, $tableSize["PRICE"]);
      $pdf->Cell($tableSize["PRICE"], 12, $titleStringPrice, 0, 0, 'C');
      $n++;
  }
  $y2 = max(array($pdf->getY(), $y2));
  $pdf->setXY($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $y0);
  
  
  $titleSummStr = $pdf::prepareToPdf(loc::getMessage("GENERATELIC_CUSTOM_PRODUCT_SUMM").$currencyType);
  $n = 0;
  while ($pdf->GetStringWidth($titleSummStr))
  {
      if($n > 0){
        $pdf->Ln();
        $pdf->Cell($tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], 12, '');
      }
      list($titleStringSumm, $titleSummStr) = $pdf->splitString($titleSummStr, $tableSize["SUMM"]);
      $pdf->Cell($tableSize["SUMM"], 12, $titleStringSumm, 0, 0, 'C');
      $n++;
  }
  $pdf->Ln();
  $y2 = max(array($pdf->getY(), $y2));
  $pdf->setXY($pdf->getX(), $y2);
  
  $pdf->Line($x0, $y2, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $y2);
  
  $pdf->Line($x0, $y0, $x0, $pdf->getY());
  $pdf->Line($x0+$tableSize["NUM"], $y0, $x0+$tableSize["NUM"], $pdf->getY());
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"], $y0, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"], $pdf->getY());
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], $y0, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], $pdf->getY());
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $y0, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $pdf->getY());
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $y0, $x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $pdf->getY());
  
  foreach($basketItems as $item){
    //print_r($item);
    //print_r("<br><br>");
    $num++;
    $strName = $pdf::prepareToPdf($item["NAME"]);
    $strArr = explode("#br#", $strName);
    $arrProps = array();
    foreach($item["PROPS"] as $prop){
      if($prop["CODE"] != "YEARS")
        continue;
      $basketitemPropertyName = $prop["NAME"];
      if($prop['CODE'] == 'YEARS' && SITE_ID == "s2"){
        $basketitemPropertyName = "Subscription extension (number of years)";
      }
      $arrProps[] = $pdf::prepareToPdf(sprintf("%s: %s", $basketitemPropertyName, $prop["VALUE"]));
    }
    
    $firstX = $pdf->getX();
    $firstY = $pdf->getY();
    $testVal = ((int)$firstY + (13 * (count($strArr) + count($arrProps))));
    if($testVal > 800 ){
      while($firstY <= $pdf->getY())
        $pdf->Ln(); 
      $firstY = $pdf->getY();
      $pdf->Line($firstX, $firstY, $lastX, $firstY);
    }
    $pdf->Cell($tableSize["NUM"], 12, $num, 0, 0, 'C');
    $strY = $pdf->getY();
    
    
    $n = 0;
    foreach($strArr as $strName){
      while ($pdf->GetStringWidth($strName))
      {
          if($n > 0){
            $pdf->Ln();
            $pdf->Cell($tableSize["NUM"], 12, '');
          }
          list($stringName, $strName) = $pdf->splitString($strName, $tableSize["PRODUCT_NAME"]);
          $pdf->Cell($tableSize["PRODUCT_NAME"], 12, $stringName);
          $n++;
      }
    }
    if(count($arrProps) > 0 ){
      foreach($arrProps as $propToWrite){
        while ($pdf->GetStringWidth($propToWrite))
        {
            $pdf->Ln();
            $pdf->Cell($tableSize["NUM"], 12, '');
            list($stringProp, $propToWrite) = $pdf->splitString($propToWrite, $tableSize["PRODUCT_NAME"]);
            $pdf->Cell($tableSize["PRODUCT_NAME"], 12, $stringProp);
            $n++;
        }
      }
    }
    $strMaxY = $pdf->getY();
    $strMaxX = $pdf->getx();
    $pdf->setXY($strMaxX, $strY);
    $pdf->Cell($tableSize["AMOUNT"], 12, $item["QUANTITY"], 0 , 0 , 'C');
    $pdf->Cell($tableSize["PRICE"], 12, number_format($item["PRICE"], 0, ',', ' '), 0 , 0 , 'C');
    $pdf->Cell($tableSize["SUMM"], 12, number_format($item["PRICE"]*$item["QUANTITY"], 0, ',', ' '), 0 , 0 , 'C');
    $lastX = $pdf->getX();
    $pdf->setY($strMaxY);
    $pdf->Ln();
    if($firstY > $pdf->getY() ){
      $firstY = $margin['top'];
      $pdf->Line($firstX, $margin['top'], $lastX, $margin['top']);
    }
    $pdf->Line($firstX, $pdf->getY(), $lastX, $pdf->getY());
    
    $pdf->Line($firstX, $firstY, $firstX, $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"], $firstY, $firstX+$tableSize["NUM"], $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"], $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"], $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $pdf->getY());
  }
  $firstX = $pdf->getX();
  $firstY = $pdf->getY();
  $pdf->Cell($tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], 12, $pdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_TOTAL_VAT_RATE")), 0 , 0 , 'R');
  $pdf->Cell($tableSize["SUMM"], 12, $pdf::prepareToPdf("-"), 0 , 0 , 'C');
  
  $pdf->Ln();
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $pdf->getY(), $lastX, $pdf->getY());
  $pdf->Cell($tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], 12, $pdf::prepareToPdf(Loc::getMessage("GENERATELIC_CUSTOM_BILL_TOTAL_SUM")), 0 , 0 , 'R');
  $pdf->Cell($tableSize["SUMM"], 12, $pdf::prepareToPdf(number_format($arOrder['PRICE'], 0, '.', ' ')), 0 , 0 , 'C');
  $pdf->Ln();
  $pdf->Line($x0+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $pdf->getY(), $lastX, $pdf->getY());
  
  $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"], $pdf->getY());
    $pdf->Line($firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $firstY, $firstX+$tableSize["NUM"]+$tableSize["PRODUCT_NAME"]+$tableSize["AMOUNT"]+$tableSize["PRICE"]+$tableSize["SUMM"], $pdf->getY());
  
  
  ///------------------------------------------------------------------------///
  $pdf->Ln();
  
  $pdf->SetFont($fontFamily, 'B', $fontSize);
  if(SITE_ID == 's1'){
      $pdf->Write(15, CSalePdf::prepareToPdf($arOrder['PRICE']." ".$currencyType));
  }else{
    if($currencyType == "$")
      $pdf->Write(15, CSalePdf::prepareToPdf(number_format($arOrder['PRICE'], 0, '.', '')));
    else
      $pdf->Write(15, CSalePdf::prepareToPdf(number_format($arOrder['PRICE'], 0, '.', ' ')." ".$currencyType));
  }
	//$pdf->Write(15, CSalePdf::prepareToPdf(Number2Word_Rus($arOrder['PRICE'])));
	//}
  
  $pdf->Ln();
  $pdf->Ln();
  $pdf->SetFont($fontFamily, '', $fontSize);
  
  
  ///------------------------------------------------------------------------///
  $pdf->Write(12, HTMLToTxt(preg_replace(
    array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
    CSalePdf::prepareToPdf($NOTICE)
  ), '', array(), 0));
  $pdf->Ln();
  
  $pdf->Write(12, HTMLToTxt(preg_replace(
    array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>', '<b>', '</b>'),
    CSalePdf::prepareToPdf($TEXT2)
  ), '', array(), 0));
  ///------------------------------------------------------------------------///
  $pdf->Ln();
  $pdf->Ln();
  
  ///------------------------------------------------------------------------///
  $pathToStampID = \Bitrix\Sale\BusinessValue::get(
    "BILL_PATH_TO_STAMP",
    "PAYSYSTEM_2"
  );

  $sellerAccSignID = \Bitrix\Sale\BusinessValue::get(
    "SELLER_COMPANY_ACC_SIGN",
    "PAYSYSTEM_2"
  );

  $sellerAccName = (SITE_ID == "s1") ? \COption::GetOptionString( "askaron.settings", "UF_ACC_FIO") : \COption::GetOptionString( "askaron.settings", "UF_ACC_FIO_EN");

  $sellerAccPosition = \Bitrix\Sale\BusinessValue::get(
    "SELLER_COMPANY_ACCOUNTANT_POSITION",
    "PAYSYSTEM_2"
  );

  $sellerDirSignID = \Bitrix\Sale\BusinessValue::get(
    "SELLER_COMPANY_DIR_SIGN",
    "PAYSYSTEM_2"
  );

  $sellerDirName = (SITE_ID == "s1") ? \COption::GetOptionString( "askaron.settings", "UF_FIO_DIR") : \COption::GetOptionString( "askaron.settings", "UF_FIO_DIR_EN");

  $sellerDirPosition = \Bitrix\Sale\BusinessValue::get(
    "SELLER_COMPANY_DIRECTOR_POSITION",
    "PAYSYSTEM_2"
  );

  
  

      if ($pathToStampID)
      {
          $pathToStampSrc = CFile::GetPath($pathToStampID);
          $filePath = $pdf->GetImagePath($pathToStampSrc);

          if ($filePath != '' && !$blank && \Bitrix\Main\IO\File::isFileExists($filePath))
          {
              list($stampHeight, $stampWidth) = $pdf->GetImageSize($pathToStampSrc);
              if ($stampHeight && $stampWidth)
              {
                  if ($stampHeight > 120 || $stampWidth > 120)
                  {
                      $ratio = 120 / max($stampHeight, $stampWidth);
                      $stampHeight = $ratio * $stampHeight;
                      $stampWidth = $ratio * $stampWidth;
                  }

                  if ($pdf->GetY() + $stampHeight > $pageHeight)
                      $pdf->AddPage();

                  $pdf->Image(
                          $pathToStampSrc,
                          $margin['left'] + 40, $pdf->GetY(),
                          $stampWidth, $stampHeight
                  );
              }
          }
      }

      $pdf->SetFont($fontFamily, 'B', $fontSize);

      if ($sellerDirPosition)
      {
          $sellerDirSignSrc = CFile::GetPath($sellerDirSignID);
          $isDirSign = false;
          if (!$blank && $sellerDirSignSrc)
          {
              list($signHeight, $signWidth) = $pdf->GetImageSize($sellerDirSignSrc);

              if ($signHeight && $signWidth)
              {
                  $ratio = min(37.5/$signHeight, 150/$signWidth);
                  $signHeight = $ratio * $signHeight;
                  $signWidth  = $ratio * $signWidth;

                  $isDirSign = true;
              }
          }

          $sellerDirPos = CSalePdf::prepareToPdf(Loc::getMessage('SELLER_COMPANY_DIRECTOR_POSITION'));
          if ($isDirSign && $pdf->GetStringWidth($sellerDirPos) <= 160)
              $pdf->SetY($pdf->GetY() + min($signHeight, 30) - 15);
          $pdf->MultiCell(150, 15, $sellerDirPos, 0, 'L');
          $pdf->SetXY($margin['left'] + 150, $pdf->GetY() - 15);

          if ($isDirSign)
          {
              $pdf->Image(
                      $sellerDirSignSrc,
                  $pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
                  $signWidth, $signHeight
              );
          }

          $x1 = $pdf->GetX();
          $pdf->Cell(160, 15, '');
          $x2 = $pdf->GetX();

          if ($sellerDirName)
              $pdf->Write(15, CSalePdf::prepareToPdf('('.$sellerDirName.')'));
          $pdf->Ln();

          $y2 = $pdf->GetY();
          $pdf->Line($x1, $y2, $x2, $y2);

          $pdf->Ln();
      }

      if ($sellerAccPosition)
      {
          $sellerAccSignSrc = CFile::GetPath($sellerAccSignID);
          $isAccSign = false;
          if (!$blank && $sellerAccSignSrc)
          {
              list($signHeight, $signWidth) = $pdf->GetImageSize($sellerAccSignSrc);

              if ($signHeight && $signWidth)
              {
                  $ratio = min(37.5/$signHeight, 150/$signWidth);
                  $signHeight = $ratio * $signHeight;
                  $signWidth  = $ratio * $signWidth;

                  $isAccSign = true;
              }
          }

          $sellerAccPos = CSalePdf::prepareToPdf(Loc::getMessage('SELLER_COMPANY_ACCOUNTANT_POSITION'));
          if ($isAccSign && $pdf->GetStringWidth($sellerAccPos) <= 160)
              $pdf->SetY($pdf->GetY() + min($signHeight, 30) - 15);
          $pdf->MultiCell(150, 15, $sellerAccPos, 0, 'L');
          $pdf->SetXY($margin['left'] + 150, $pdf->GetY() - 15);

          if ($isAccSign)
          {
              $pdf->Image(
                  $sellerAccSignSrc,
                  $pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
                  $signWidth, $signHeight
              );
          }

          $x1 = $pdf->GetX();
          $pdf->Cell(($sellerDirName) ? $x2-$x1 : 160, 15, '');
          $x2 = $pdf->GetX();

          if ($sellerAccName)
              $pdf->Write(15, CSalePdf::prepareToPdf('('.$sellerAccName.')'));
          $pdf->Ln();

          $y2 = $pdf->GetY();
          $pdf->Line($x1, $y2, $x2, $y2);
      }
  $dest = 'I';
  if ($_REQUEST['GET_CONTENT'] == 'Y')
      $dest = 'S';
  else if ($_REQUEST['DOWNLOAD'] == 'Y')
      $dest = 'D';    
  ///------------------------------------------------------------------------///
  $fileName = sprintf(
      'LIC_No_%s__%s.pdf',
      str_replace(
          array(
              chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11),
              chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22),
              chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31),
              '"', '*', '/', ':', '<', '>', '?', '\\', '|'
          ),
          '_',
          $ORDER_ID
      ),
      $datePayed
  );
  $trFileName = CUtil::translit($fileName, 'ru', array('max_len' => 1024, 'safe_chars' => '.', 'replace_space' => '-'));
  header("Content-type:application/pdf");
  header('Content-Disposition: attachment; filename="'.$fileName.'"'); 
  $pdf->Output($trFileName, $dest, $fileName);
  exit;
  
}else{
  print_r("Указанный заказ ен найден, возможно вы пытаетесь открыть не свой заказ TODO: переделать на редирект в ЛК");
  LocalRedirect(SITE_DIR . 'personal/');
  //die();
  //ShowError(GetMessage('SOP_ORDER_NOT_FOUND'));
}