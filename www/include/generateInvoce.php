<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"].'/lang.php'); // Подключили языковой файл
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


if ($arOrder){
  $arrProps = array();

  $db_props = CSaleOrderPropsValue::GetOrderProps($arOrder["ID"]);
  $iGroup = -1;
  while ($arProps = $db_props->Fetch())
  {
     if ($iGroup!=IntVal($arProps["PROPS_GROUP_ID"]))
     {
        $iGroup = IntVal($arProps["PROPS_GROUP_ID"]);
     }
     $arrProps[$arProps["CODE"]] = "";

     if ($arProps["TYPE"]=="CHECKBOX")
     {
        if ($arProps["VALUE"]=="Y")
           $arrProps[$arProps["CODE"]] = "Да";
        else
           $arrProps[$arProps["CODE"]] = "Нет";
     }
     elseif ($arProps["TYPE"]=="TEXT" || $arProps["TYPE"]=="TEXTAREA")
     {
        $arrProps[$arProps["CODE"]] = htmlspecialchars($arProps["VALUE"]);
     }
     elseif ($arProps["TYPE"]=="SELECT" || $arProps["TYPE"]=="RADIO")
     {
        $arVal = CSaleOrderPropsVariant::GetByValue($arProps["ORDER_PROPS_ID"], $arProps["VALUE"]);
        $arrProps[$arProps["CODE"]] = htmlspecialchars($arVal["NAME"]);
     }
     elseif ($arProps["TYPE"]=="MULTISELECT")
     {
        $curVal = split(",", $arProps["VALUE"]);
        for ($i = 0; $i<count($curVal); $i++)
        {
           $arVal = CSaleOrderPropsVariant::GetByValue($arProps["ORDER_PROPS_ID"], $curVal[$i]);
           if ($i>0) $arrProps[$arProps["CODE"]] .= ", ";
           $arrProps[$arProps["CODE"]] .= htmlspecialchars($arVal["NAME"]);
        }
     }
     elseif ($arProps["TYPE"]=="LOCATION")
     {
        $arVal = CSaleLocation::GetByID($arProps["VALUE"], LANGUAGE_ID);
        $arrProps[$arProps["CODE"]] = htmlspecialchars($arVal["COUNTRY_NAME"]." - ".$arVal["CITY_NAME"]);
     }
     
     if($arProps["IS_PAYER"] == "Y"){
        $arrProps["NAME"] = $arProps["VALUE"];
      }

     //echo "<br>";
  }

  $arrOrderProps = array();
  $db_vals = CSaleOrderPropsValue::GetList(
      array(),
      array(
              "ORDER_ID" => $arOrder["ID"],
          )
  );
  while($db_prop = $db_vals->Fetch()){
    $arrOrderProps[$db_prop["CODE"]] = $db_prop;
  }
  
  $obOrder = CSaleOrder::GetByID($arOrder["ID"]);
  $payerName = "";
  $licenseeAddress = $arrOrderProps["ADDRESS"]["VALUE"];
  switch($obOrder["PERSON_TYPE_ID"]){
    case 2:
      $payerName = $arrProps["NAME"];
      $licenseeAddress = $arrOrderProps["MAIL_ADDRESS"]["VALUE"];
      break;
    case 3:
      $payerName = Loc::getMessage('SALE_HPS_BILL_IP').$arrProps["NAME"].', '.Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $arrProps["INN"]));
      if(SITE_ID == 's1')
        if($arrProps["KPP"] != '')
          $payerName .= ', '.Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $arrProps["KPP"]));
      break;
    default:
      $firmName = $arrProps["NAME"];
      $legalForm = $arrProps["LEGAL_FORM"];
      if($legalForm && $legalForm!='')
        $firmName = $legalForm . " " . getFirmName($arrProps["NAME"],$legalForm);
      $payerName = $firmName.', '.Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $arrProps["INN"]));
      if(SITE_ID == 's1')
        if($arrProps["KPP"] != '')
          $payerName .= ', '.Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $arrProps["KPP"]));
      break;
  }


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


    $subParamsFN=[
        's1'=> [
            'FIRM_NAME' => COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME" ),
            'ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ADDRESS" ),
            'BILL_COMMENT' => COption::GetOptionString( "askaron.settings", "UF_BILL_COMMENT" ),
            'BILL_OFERTA' => COption::GetOptionString( "askaron.settings", "UF_BILL_OFERTA_RU" ),
        ],
        's2'=> [
            'FIRM_NAME' => COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_EN" ),
            'ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ADDRESS_EN" ),
            'BILL_COMMENT' => COption::GetOptionString( "askaron.settings", "UF_BILL_COMMENT_EN" ),
            'BANK_COMPNAME' => COption::GetOptionString( "askaron.settings", "UF_ENBANK_COMPNAME" ),
            'BANK_ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ENBANK_ADDRESS" ),
            'BILL_OFERTA' => COption::GetOptionString( "askaron.settings", "UF_BILL_OFERTA_EN" ),
        ],
        's3'=> [
            'FIRM_NAME' => COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_FR" ),
            'ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ADDRESS_FR" ),
            'BILL_COMMENT' => COption::GetOptionString( "askaron.settings", "UF_BILL_COMMENT_FR" ),
            'BANK_COMPNAME' => COption::GetOptionString( "askaron.settings", "UF_FRBANK_COMPNAME" ),
            'BANK_ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_FRBANK_ADDRESS" ),
            'BILL_OFERTA' => COption::GetOptionString( "askaron.settings", "UF_BILL_OFERTA_FR" ),
        ],
        's4'=> [
            'FIRM_NAME' => COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_DE" ),
            'ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ADDRESS_DE" ),
            'BILL_COMMENT' => COption::GetOptionString( "askaron.settings", "UF_BILL_COMMENT_DE" ),
            'BANK_COMPNAME' => COption::GetOptionString( "askaron.settings", "UF_DEBANK_COMPNAME" ),
            'BANK_ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_DEBANK_ADDRESS" ),
            'BILL_OFERTA' => COption::GetOptionString( "askaron.settings", "UF_BILL_OFERTA_DE" ),
        ],
        's5'=> [
            'FIRM_NAME' => COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_ES" ),
            'ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ADDRESS_ES" ),
            'BILL_COMMENT' => COption::GetOptionString( "askaron.settings", "UF_BILL_COMMENT_ES" ),
            'BANK_COMPNAME' => COption::GetOptionString( "askaron.settings", "UF_ESBANK_COMPNAME" ),
            'BANK_ADDRESS' => COption::GetOptionString( "askaron.settings", "UF_ESBANK_ADDRESS" ),
            'BILL_OFERTA' => COption::GetOptionString( "askaron.settings", "UF_UF_BILL_OFERTA_ES" ),
        ],
    ];

    $oferta = $subParamsFN[SITE_ID]['BILL_OFERTA'];

  $params = Array ( 
      "PAYMENT_DATE_INSERT" => (SITE_ID == "s1")? $arOrder["DATE_INSERT"]->format('d.m.Y'): $arOrder["DATE_INSERT"]->format('F d, Y'), 
      "PAYMENT_DATE_PAY_BEFORE" => false,
      "BILL_SIGN_SHOW" => "Y", 
      "SELLER_COMPANY_NAME" =>$subParamsFN[SITE_ID]['FIRM_NAME'], //(SITE_ID == "s1") ? \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME") : \COption::GetOptionString( "askaron.settings", "UF_FIRM_NAME_EN"),
      "SELLER_COMPANY_ADDRESS" => $subParamsFN[SITE_ID]['ADDRESS'],
      "SELLER_COMPANY_PHONE" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_PHONE","PAYSYSTEM_2"), 
      "SELLER_COMPANY_INN" => \COption::GetOptionString( "askaron.settings", "UF_INN"), 
      "SELLER_COMPANY_KPP" => \COption::GetOptionString( "askaron.settings", "UF_KPP"), 
      "SELLER_COMPANY_BANK_ACCOUNT" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_BANK_ACCOUNT","PAYSYSTEM_2"), 
      "SELLER_COMPANY_BANK_NAME" => "Центрально-черноземный банк ПАО Сбербанк",
      "SELLER_COMPANY_BANK_CITY" => "Воронеж", 
      "SELLER_COMPANY_BANK_ACCOUNT_CORR" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_BANK_ACCOUNT_CORR","PAYSYSTEM_2"), 
      "SELLER_COMPANY_BANK_BIC" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_BANK_BIC","PAYSYSTEM_2"),
      "SELLER_COMPANY_DIRECTOR_POSITION" => "Генеральный директор", 
      "SELLER_COMPANY_ACCOUNTANT_POSITION" => "Главный бухгалтер", 
      "SELLER_COMPANY_DIRECTOR_NAME" => (SITE_ID == "s1") ? \COption::GetOptionString( "askaron.settings", "UF_FIO_DIR") : \COption::GetOptionString( "askaron.settings", "UF_FIO_DIR_EN"),
      "SELLER_COMPANY_ACCOUNTANT_NAME" => (SITE_ID == "s1") ? \COption::GetOptionString( "askaron.settings", "UF_ACC_FIO") : \COption::GetOptionString( "askaron.settings", "UF_ACC_FIO_EN"), 
      "BILL_PAYER_SHOW" => "Y", 
      "BUYER_PERSON_COMPANY_NAME" => $arrOrderProps["NAME"]["VALUE"],
      "BUYER_PERSON_COMPANY_INN" => $arrOrderProps["INN"]["VALUE"], 
      "BUYER_PERSON_COMPANY_ADDRESS" => $licenseeAddress, 
      "BUYER_PERSON_COMPANY_PHONE" => false,
      "BUYER_PERSON_COMPANY_FAX" => false,
      "BUYER_PERSON_COMPANY_NAME_CONTACT" => false,
      "BILL_HEADER" => "СЧЕТ", 
      "BILL_ORDER_SUBJECT" => false,
      "BILL_HEADER_SHOW" => "Y", 
      "BILL_TOTAL_SHOW" => "Y", 
      "BILL_COMMENT1" => true,
      "BILL_COMMENT2" => false,
      "BILL_PATH_TO_LOGO" => false,
      "BILL_LOGO_DPI" => false,
      "BILL_PATH_TO_STAMP" => \Bitrix\Sale\BusinessValue::get("BILL_PATH_TO_STAMP","PAYSYSTEM_2"), 
      "SELLER_COMPANY_DIR_SIGN" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_DIR_SIGN","PAYSYSTEM_2"),
      "SELLER_COMPANY_ACC_SIGN" => \Bitrix\Sale\BusinessValue::get("SELLER_COMPANY_ACC_SIGN","PAYSYSTEM_2"), 
      "BILL_BACKGROUND" => false,
      "BILL_BACKGROUND_STYLE" => false,
      "BILL_MARGIN_TOP" => 15,
      "BILL_MARGIN_RIGHT" => 15, 
      "BILL_MARGIN_BOTTOM" => 15, 
      "BILL_MARGIN_LEFT" => 20, 
      "BILL_COLUMN_NUMBER_TITLE" => "№", 
      "BILL_COLUMN_NUMBER_SORT" => 100, 
      "BILL_COLUMN_NUMBER_SHOW" => "Y", 
      "BILL_COLUMN_NAME_TITLE" => "Наименование товара", 
      "BILL_COLUMN_NAME_SORT" => 200, 
      "BILL_COLUMN_NAME_SHOW" => "Y", 
      "BILL_COLUMN_QUANTITY_TITLE" => "Кол-во", 
      "BILL_COLUMN_QUANTITY_SORT" => 300, 
      "BILL_COLUMN_QUANTITY_SHOW" => "Y", 
      "BILL_COLUMN_MEASURE_TITLE" => "Ед.", 
      "BILL_COLUMN_MEASURE_SORT" => 400, 
      "BILL_COLUMN_MEASURE_SHOW" => "Y", 
      "BILL_COLUMN_PRICE_TITLE" => "Цена", 
      "BILL_COLUMN_PRICE_SORT" => 500, 
      "BILL_COLUMN_PRICE_SHOW" => "Y", 
      "BILL_COLUMN_VAT_RATE_TITLE" => "Ставка НДС", 
      "BILL_COLUMN_VAT_RATE_SORT" => 600, 
      "BILL_COLUMN_VAT_RATE_SHOW" => "Y", 
      "BILL_COLUMN_SUM_TITLE" => "Сумма", 
      "BILL_COLUMN_SUM_SORT" => 700,
      "BILL_COLUMN_SUM_SHOW" => "Y", 
      "ACCOUNT_NUMBER" => 300 + $arOrder["ID"], 
      "CURRENCY" => $arOrder["CURRENCY"], 
      "DATE_BILL" => $arOrder["DATE_INSERT"],
      "SUM" => $arOrder['PRICE'], 
      "SUM_PAID" => $arOrder['SUM_PAID'], 
      "DISCOUNT_PRICE" => $arOrder['DISCOUNT_VALUE'], 
      "TAXES" => false,
      "DELIVERY_NAME" => "Без доставки", 
      "DELIVERY_PRICE" => $arOrder['PRICE_DELIVERY'], 
      "DELIVERY_VAT_RATE" => 0,
      "BASKET_ITEMS" => $basketItems,
      );
//echo "<pre>";print_r( $params);echo "</pre>";
  $arPaySysAction["ENCODING"] = "";

  if (!CSalePdf::isPdfAvailable())
      die();

  if ($_REQUEST['BLANK'] == 'Y')
      $blank = true;

  if((substr($arrProps["WORK_PHONE"], 0, 1) != "+") ){
    if(SITE_ID == "s1") {
        $arrProps["WORK_PHONE"] = "+7" . $arrProps["WORK_PHONE"];
    }else{
      $arrProps["WORK_PHONE"] = "+".$arrProps["WORK_PHONE"];
    }
  }
  
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

  $pdf->AddPage();


  $y0 = $pdf->GetY();
  $logoHeight = 0;
  $logoWidth = 0;

  if ($params['BILL_HEADER_SHOW'] == 'Y')
  {
    if(SITE_ID == "s1"){

      if ($params["SELLER_COMPANY_BANK_NAME"])
      {
          $sellerBankCity = '';
          if ($params["SELLER_COMPANY_BANK_CITY"])
          {
              $sellerBankCity = $params["SELLER_COMPANY_BANK_CITY"];
              if (is_array($sellerBankCity))
                  $sellerBankCity = implode(', ', $sellerBankCity);
              else
                  $sellerBankCity = str_replace(array("\r\n", "\n", "\r"), ', ', strval($sellerBankCity));
          }
          $sellerBank = sprintf(
              "%s %s",
              $params["SELLER_COMPANY_BANK_NAME"],
              $sellerBankCity
          );
          unset($sellerBankCity);
          $sellerRs = $params["SELLER_COMPANY_BANK_ACCOUNT"];
      }
      else
      {
          $rsPattern = '/\s*\d{10,100}\s*/';

          $sellerBank = trim(preg_replace($rsPattern, ' ', $params["SELLER_COMPANY_BANK_ACCOUNT"]));

          preg_match($rsPattern, $params["SELLER_COMPANY_BANK_ACCOUNT"], $matches);
          $sellerRs = trim($matches[0]);
      }

      $pdf->SetFont($fontFamily, '', $fontSize);

      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();

      $pdf->Cell(
          150, 18,
          ($params["SELLER_COMPANY_INN"])
              ? CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $params["SELLER_COMPANY_INN"])))
              : ''
      );
      $x1 = $pdf->GetX();
      $pdf->Cell(
          150, 18,
          ($params["SELLER_COMPANY_KPP"])
              ? CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"])))
              : ''
      );
      $x2 = $pdf->GetX();
      $pdf->Cell(50, 18);
      $x3 = $pdf->GetX();
      $pdf->Cell(0, 18);
      $x4 = $pdf->GetX();

      $pdf->Line($x0, $y0, $x4, $y0);

      $pdf->Ln();
      $y1 = $pdf->GetY();

      $pdf->Line($x1, $y0, $x1, $y1);

      $pdf->Cell(300, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_NAME')));
      $pdf->Cell(50, 18);
      $pdf->Cell(0, 18);

      $pdf->Line($x0, $y1, $x2, $y1);

      $pdf->Ln();
      $y2 = $pdf->GetY();

      $text = CSalePdf::prepareToPdf($params["SELLER_COMPANY_NAME"]);
      while ($pdf->GetStringWidth($text) > 0)
      {
          list($string, $text) = $pdf->splitString($text, 300-5);

      $pdf->Cell(300, 18, $string);
          if ($text)
              $pdf->Ln();
      }
      $pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_ACC')));
      $size = $pdf->GetPageWidth()-$pdf->GetX()-$margin['right'];
      $sellerRs = CSalePdf::prepareToPdf($sellerRs);
      while ($pdf->GetStringWidth($sellerRs) > 0)
      {
          list($string, $sellerRs) = $pdf->splitString($sellerRs, $size-5);

          $pdf->Cell(0, 18, $string);
          if ($sellerRs)
          {
              $pdf->Ln();
              $pdf->Cell(300, 18, '');
              $pdf->Cell(50, 18, '');
          }
      }

      $pdf->Ln();
      $y3 = $pdf->GetY();

      $pdf->Cell(300, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_NAME')));
      $pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_BIK')));
      $pdf->Cell(0, 18, CSalePdf::prepareToPdf($params["SELLER_COMPANY_BANK_BIC"]));

      $pdf->Line($x0, $y3, $x4, $y3);

      $pdf->Ln();
      $y4 = $pdf->GetY();

      $text = CSalePdf::prepareToPdf($sellerBank);
      while ($pdf->GetStringWidth($text) > 0)
      {
          list($string, $text) = $pdf->splitString($text, 300-5);

          $pdf->Cell(300, 18, $string);
          if ($text)
              $pdf->Ln();
      }
      $pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_ACC_CORR')));

      $bankAccountCorr = CSalePdf::prepareToPdf($params["SELLER_COMPANY_BANK_ACCOUNT_CORR"]);
      while ($pdf->GetStringWidth($bankAccountCorr) > 0)
      {
          list($string, $bankAccountCorr) = $pdf->splitString($bankAccountCorr, $size-5);

          $pdf->Cell(0, 18, $string);
          if ($bankAccountCorr)
          {
              $pdf->Ln();
              $pdf->Cell(300, 18, '');
              $pdf->Cell(50, 18, '');
          }
      }

      $pdf->Ln();
      $y5 = $pdf->GetY();

      $pdf->Line($x0, $y5, $x4, $y5);

      $pdf->Line($x0, $y0, $x0, $y5);
      $pdf->Line($x2, $y0, $x2, $y5);
      $pdf->Line($x3, $y0, $x3, $y5);
      $pdf->Line($x4, $y0, $x4, $y5);

      $pdf->Cell(300,18,CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE')));
      $pdf->Ln();
      
      $textPurposeData = Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE_DATA', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]));
      while ($pdf->GetStringWidth($textPurposeData) > 0)
      {
          list($string, $textPurposeData) = $pdf->splitString($textPurposeData, 500-5);

          $pdf->Cell(500, 18, $string);
              if ($textPurposeData)
                  $pdf->Ln();
      }
      $pdf->Ln();
      $y5 = $pdf->GetY();

      $pdf->Line($x0, $y5, $x4, $y5);

      $pdf->Line($x0, $y0, $x0, $y5);
      $pdf->Line($x4, $y0, $x4, $y5);
      /*$pdf->Cell(300,18,CSalePdf::prepareToPdf(
               Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE_DATA', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]))
            )
      );*/

      $pdf->Ln();
      $pdf->Ln();
    }else{
      if ($params["SELLER_COMPANY_BANK_NAME"])
      {
          $sellerBankCity = '';
          if ($params["SELLER_COMPANY_BANK_CITY"])
          {
              $sellerBankCity = $params["SELLER_COMPANY_BANK_CITY"];
              if (is_array($sellerBankCity))
                  $sellerBankCity = implode(', ', $sellerBankCity);
              else
                  $sellerBankCity = str_replace(array("\r\n", "\n", "\r"), ', ', strval($sellerBankCity));
          }
          $sellerBank = sprintf(
              "%s %s",
              $params["SELLER_COMPANY_BANK_NAME"],
              $sellerBankCity
          );
          unset($sellerBankCity);
          $sellerRs = $params["SELLER_COMPANY_BANK_ACCOUNT"];
      }
      else
      {
          $rsPattern = '/\s*\d{10,100}\s*/';

          $sellerBank = trim(preg_replace($rsPattern, ' ', $params["SELLER_COMPANY_BANK_ACCOUNT"]));

          preg_match($rsPattern, $params["SELLER_COMPANY_BANK_ACCOUNT"], $matches);
          $sellerRs = trim($matches[0]);
      }

      $pdf->SetFont($fontFamily, '', $fontSize);

      $x0 = $x0tab = $pdf->GetX();
      $y0 = $y0tab = $pdf->GetY();

      $buyerBankName = $arrOrderProps["BANK_NAME"]["VALUE"];
      $buyerBankACC = $arrOrderProps["CHECK_ACC"]["VALUE"];
      $buyerBankSWIFT = $arrOrderProps["SWIFT"]["VALUE"];
      
      $companyName = $subParamsFN[SITE_ID]['BANK_COMPNAME'];
      $companyBankNameShort = \COption::GetOptionString( "askaron.settings", "UF_ENBANK_BANKNAME" );
      $companyBankNameLong = $subParamsFN[SITE_ID]['BANK_ADDRESS'];
      $companyIBAN = \COption::GetOptionString( "askaron.settings", "UF_ENBANK_IBAN" );
      $companyBankSWIFT = \COption::GetOptionString( "askaron.settings", "UF_ENBANK_SWIFT" );
      
      /// BEGIN ///1st row INVOCE-OFFER EN-HEADER
      
      $startY = $currY = $pdf->GetY();
      if(! ($obOrder["PERSON_TYPE_ID"] == 2 || $obOrder["PERSON_TYPE_ID"] == 3)){
      $textBuyerBankName = Loc::getMessage('SELLER_HPS_BILL_INTERMEDIARY', array('#buyerBankName#' => $buyerBankName));
      while ($pdf->GetStringWidth($textBuyerBankName) > 0)
      {
          list($string, $textBuyerBankName) = $pdf->splitString($textBuyerBankName, 250);

          $pdf->Cell(260, 18, $string);
          $x1 = $pdf->GetX();
              if ($textBuyerBankName)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      $textBuyerBankACC = Loc::getMessage('SELLER_HPS_BILL_INTERMEDIARY_BANK_ACC');
      while ($pdf->GetStringWidth($textBuyerBankACC) > 0)
      {
          list($string, $textBuyerBankACC) = $pdf->splitString($textBuyerBankACC, 105);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(110, 18, $string);
          $x2 = $pdf->GetX();
              if ($textBuyerBankACC)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      while ($pdf->GetStringWidth($buyerBankACC) > 0)
      {
          list($string, $buyerBankACC) = $pdf->splitString($buyerBankACC, 125);
          $pdf->Cell(370, 18, '');
          $pdf->Cell(130, 18, $string);
          $x3 = $pdf->GetX();
              if ($buyerBankACC)
                  $pdf->Ln();
      }
      
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      $pdf->Line($x0, $y0, $x3, $y0);
      $pdf->Ln();
      $y1 = $pdf->GetY();
      //$pdf->Line($x1, $y0, $x1, $y1);
      $pdf->Line($x2, $y0, $x2, $y1);
      /// END ///1st row INVOCE-OFFER EN-HEADER
      
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      
      /// BEGIN ///2nd row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $textBuyerBankSWIFT = Loc::getMessage('SELLER_HPS_BILL_INTERMEDIARY_BANK_SWIFT', array('#buyerBankSWIFT#' => $buyerBankSWIFT));
      while ($pdf->GetStringWidth($textBuyerBankSWIFT) > 0)
      {
          list($string, $textBuyerBankSWIFT) = $pdf->splitString($textBuyerBankSWIFT, 250);

          $pdf->Cell(260, 18, $string);
          $x1 = $pdf->GetX();
              if ($textBuyerBankSWIFT)
                  $pdf->Ln();
      }
      $pdf->Ln();
      $y1 = $pdf->GetY();
      $pdf->Line($x0, $y0, $x3, $y0);
      /// END ///2nd row INVOCE-OFFER EN-HEADER
      }
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      
      
      /// BEGIN ///3rd row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $textCompanyName = Loc::getMessage('SELLER_HPS_BILL_BENEFICIRY', array('#COMPANY_NAME#' => $companyName));
      while ($pdf->GetStringWidth($textCompanyName) > 0)
      {
          list($string, $textCompanyName) = $pdf->splitString($textCompanyName, 250);

          $pdf->Cell(260, 18, $string);
          $x1 = $pdf->GetX();
              if ($textCompanyName)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      $textCompanyBankNAME1 = Loc::getMessage('SELLER_HPS_BILL_BENEFICIRY_BANK_NAME1');
      while ($pdf->GetStringWidth($textCompanyBankNAME1) > 0)
      {
          list($string, $textCompanyBankNAME1) = $pdf->splitString($textCompanyBankNAME1, 105);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(110, 18, $string);
          $x2 = $pdf->GetX();
              if ($textCompanyBankNAME1)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      while ($pdf->GetStringWidth($companyBankNameShort) > 0)
      {
          list($string, $companyBankNameShort) = $pdf->splitString($companyBankNameShort, 125);
          $pdf->Cell(370, 18, '');
          $pdf->Cell(130, 18, $string);
          $x3 = $pdf->GetX();
              if ($companyBankNameShort)
                  $pdf->Ln();
      }
      
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      $pdf->Line($x0, $y0, $x3, $y0);
      $pdf->Ln();
      $y1 = $pdf->GetY();
      $pdf->Line($x2, $y0, $x2, $y1);
      /// END ///3rd row INVOCE-OFFER EN-HEADER
      
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      
      
      /// BEGIN ///4th row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      
      $pdf->Cell(260, 18, Loc::getMessage('SELLER_HPS_BILL_BENEFICIRY_BANK_NAME2'));
      $x1 = $pdf->GetX();
      $pdf->Ln();
      $pdf->Ln();
      while ($pdf->GetStringWidth($companyBankNameLong) > 0)
      {
          list($string, $companyBankNameLong) = $pdf->splitString($companyBankNameLong, 250);

          $pdf->Cell(260, 18, $string);
          
              if ($companyBankNameLong)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      $pdf->Cell(260, 18, '');
      $pdf->Cell(110, 18, Loc::getMessage('SELLER_HPS_BILL_BENEFICIRY_BANK_IBAN'));
      $x2 = $pdf->GetX();
      $pdf->Ln();
      $pdf->Ln();
      
      $textCompanyBankSWIFT = Loc::getMessage('SELLER_HPS_BILL_BENEFICIRY_BANK_SWIFT');
      while ($pdf->GetStringWidth($textCompanyBankSWIFT) > 0)
      {
          list($string, $textCompanyBankSWIFT) = $pdf->splitString($textCompanyBankSWIFT, 105);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(110, 18, $string);
          
              if ($textCompanyBankSWIFT)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      
      
      $pdf->Cell(370, 18, '');
      $pdf->Cell(130, 18, $companyIBAN);
      $x3 = $pdf->GetX();
      $pdf->Ln();
      $pdf->Ln();
      $pdf->Cell(370, 18, '');
      $pdf->Cell(130, 18, $companyBankSWIFT);
      $pdf->Ln();
      
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      $pdf->Line($x0, $y0, $x3, $y0);
      $pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      $pdf->Line($x2, $y0, $x2, $currY);
      $pdf->Line($x0, $currY, $x3, $currY);
      /// END ///4th row INVOCE-OFFER EN-HEADER

/// BEGIN ///5th row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $pdf->Cell(260, 18, Loc::getMessage("INTERMEDIARY"));
      $x1 = $pdf->GetX();
      //$pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $textIntermidBank = \COption::GetOptionString( "askaron.settings", "UF_INTER" );
      while ($pdf->GetStringWidth($textIntermidBank) > 0)
      {
          list($string, $textIntermidBank) = $pdf->splitString($textIntermidBank, 235);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(240, 18, $string);
              if ($textIntermidBank)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      $pdf->Line($x0, $y0, $x3, $y0);
      $pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      //$pdf->Line($x2, $y0, $x2, $currY);
      /// END ///5th row INVOCE-OFFER EN-HEADER
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      /// BEGIN ///6th row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $pdf->Cell(260, 18, Loc::getMessage("INTERMEDIARY_BANK_SWIFT"));
      $x1 = $pdf->GetX();
      $pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $textIntermidBankSWIFT = \COption::GetOptionString( "askaron.settings", "UF_INTER_SWIFT" );
      while ($pdf->GetStringWidth($textIntermidBankSWIFT) > 0)
      {
          list($string, $textIntermidBankSWIFT) = $pdf->splitString($textIntermidBankSWIFT, 235);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(240, 18, $string);
              if ($textIntermidBankSWIFT)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      //$pdf->Line($x0, $y0, $x3, $y0);
      //$pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      //$pdf->Line($x2, $y0, $x2, $currY);
      /// END ///6th row INVOCE-OFFER EN-HEADER
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      /// BEGIN ///7th row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $pdf->Cell(260, 18, Loc::getMessage("INTERMEDIARY_BANK_ADDRESS"));
      $x1 = $pdf->GetX();
      $pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $textIntermidBankAddress = \COption::GetOptionString( "askaron.settings", "UF_INTER_ADDRES" );
      while ($pdf->GetStringWidth($textIntermidBankAddress) > 0)
      {
          list($string, $textIntermidBankAddress) = $pdf->splitString($textIntermidBankAddress, 235);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(240, 18, $string);
              if ($textIntermidBankAddress)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      //$pdf->Line($x0, $y0, $x3, $y0);
      //$pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      //$pdf->Line($x2, $y0, $x2, $currY);
      /// END ///7th row INVOCE-OFFER EN-HEADER
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      /// BEGIN ///8th row INVOCE-OFFER EN-HEADER
      $startY = $currY = $pdf->GetY();
      $pdf->Cell(260, 18, Loc::getMessage("INTERMEDIARY_BANK_ACCOUNT"));
      $x1 = $pdf->GetX();
      $pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $textIntermidBankAcc = \COption::GetOptionString( "askaron.settings", "UF_INTER_ACC" );
      while ($pdf->GetStringWidth($textIntermidBankAcc) > 0)
      {
          list($string, $textIntermidBankAcc) = $pdf->splitString($textIntermidBankAcc, 235);
          $pdf->Cell(260, 18, '');
          $pdf->Cell(240, 18, $string);
              if ($textIntermidBankAcc)
                  $pdf->Ln();
      }
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($startY);
      $currY = max($pdf->GetY(), $currY);
      $pdf->SetY($currY);
      //$pdf->Line($x0, $y0, $x3, $y0);
      //$pdf->Ln();
      $currY = max($pdf->GetY(), $currY);
      //$pdf->Line($x2, $y0, $x2, $currY);
      /// END ///8th row INVOCE-OFFER EN-HEADER

      $pdf->Line($x0tab, $y0tab, $x0tab, $currY);
      $pdf->Line($x1, $y0tab, $x1, $currY);
      $pdf->Line($x3, $y0tab, $x3, $currY);
      $pdf->Line($x0tab, $currY, $x3, $currY);
      
      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();
      
      $y5 = $pdf->GetY();
      $pdf->Cell(300,18,CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE')));
      $pdf->Ln();
      
      $textPurposeData = Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE_DATA', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]));
      while ($pdf->GetStringWidth($textPurposeData) > 0)
      {
          list($string, $textPurposeData) = $pdf->splitString($textPurposeData, 500-5);

          $pdf->Cell(500, 18, $string);
              if ($textPurposeData)
                  $pdf->Ln();
      }
      $pdf->Ln();
      $y5 = $pdf->GetY();

      $pdf->Line($x0, $y5, $x3, $y5);

      $pdf->Line($x0tab, $y0tab, $x0tab, $y5);
      $pdf->Line($x3, $y0tab, $x3, $y5);
      /*$pdf->Cell(300,18,CSalePdf::prepareToPdf(
               Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE_DATA', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]))
            )
      );*/
      $x4 = $x3;
      $pdf->Ln();
      $pdf->Ln();
      
    }  
  }
  
  if ($params['BILL_HEADER'])
  {
      $pdf->SetFont($fontFamily, 'B', $fontSize * 1.25);
      $billNo_tmp = CSalePdf::prepareToPdf(
          Loc::getMessage('SALE_HPS_BILL_TITLE').' '.Loc::getMessage('SALE_HPS_BILL_SELLER_TITLE', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]))
      );

      $billNo_width = $pdf->GetStringWidth($billNo_tmp);
      $pdf->Cell(0, 20, $billNo_tmp, 0, 0, 'C');
      $pdf->Ln();
  }
  $pdf->SetFont($fontFamily, '', $fontSize);

  if ($params["BILL_ORDER_SUBJECT"])
  {
      $pdf->Cell($width/2-$billNo_width/2-2, 15, '');
      $pdf->MultiCell(0, 15, CSalePdf::prepareToPdf($params["BILL_ORDER_SUBJECT"]), 0, 'L');
  }

  if ($params["PAYMENT_DATE_PAY_BEFORE"])
  {
      $pdf->Cell($width/2-$billNo_width/2-2, 15, '');
      $pdf->MultiCell(0, 15, CSalePdf::prepareToPdf(
              Loc::getMessage('SALE_HPS_BILL_SELLER_DATE_END', array('#PAYMENT_DATE_END#' => ConvertDateTime($params["PAYMENT_DATE_PAY_BEFORE"], FORMAT_DATE) ?: $params["PAYMENT_DATE_PAY_BEFORE"]))), 0, 'L');
  }
  $pdf->Ln();


  // ADD LICENSIAR / LICENSEE TABLE
  $x0 = $pdf->GetX();
  $y0 = $pdf->GetY();

  $x2 = $pdf->GetX();

  $title = Loc::getMessage('SALE_HPS_BILL_LICENSOR');
  $text = $params["SELLER_COMPANY_NAME"].', '
                .Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $params["SELLER_COMPANY_INN"]))
                .(SITE_ID == 's1' ? ', '.Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"])) : '');

  $text2 = $params["SELLER_COMPANY_ADDRESS"];

    while(true){
        if ($pdf->GetStringWidth($title) > 0 || $pdf->GetStringWidth($text) > 0 || $pdf->GetStringWidth($text2) > 0){
            list($titleString, $title) = $pdf->splitString($title, 80-5);
            $pdf->Cell(80, 18, $titleString, 0, 0, "C");

            if ($pdf->GetStringWidth($text) > 0){
                list($string, $text) = $pdf->splitString($text, 420-5);
                $pdf->Cell(420, 18, $string);
            }elseif($pdf->GetStringWidth($text2) > 0){
                list($string, $text2) = $pdf->splitString($text2, 420-5);
                $pdf->Cell(420, 18, $string);
            }
            $pdf->Ln();
        }else{
            break;
        }
    }

  $y5 = $pdf->GetY();

  $pdf->Line($x0, $y5, $x4, $y5);
  $pdf->Line($x0, $y0, $x4, $y0);

  $pdf->Cell(80,18,CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_LICENSEE')), 0, 0, "C");
  $x2 = $pdf->GetX();
  $text = $payerName;
  while ($pdf->GetStringWidth($text) > 0)
  {
      list($string, $text) = $pdf->splitString($text, 420-5);

      $pdf->Cell(420, 18, $string);
          if ($text){
              $pdf->Ln();
              $pdf->Cell(80,18,'');
          }
  }
  $pdf->Ln();
  $text = $licenseeAddress.', '.$arrProps["WORK_PHONE"].', '.$arrProps["EMAIL"];
  if($text){
    $pdf->Cell(80,18,'');
  }
  while ($pdf->GetStringWidth($text) > 0)
  {
      list($string, $text) = $pdf->splitString($text, 420-5);

      $pdf->Cell(420, 18, $string);
          if ($text){
              $pdf->Ln();
              $pdf->Cell(80,18,'');
          }
  }
  $pdf->Ln();
  $y5 = $pdf->GetY();

  $pdf->Line($x0, $y5, $x4, $y5);
  $pdf->Line($x0, $y0, $x0, $y5);
  $pdf->Line($x4, $y0, $x4, $y5);
  $pdf->Line($x2, $y0, $x2, $y5);

  $pdf->Ln();

  $pdf->Ln();
  
  $arCurFormat = CCurrencyLang::GetCurrencyFormat($params['CURRENCY']);
  $currency = preg_replace('/(^|[^&])#/', '${1}', $arCurFormat['FORMAT_STRING']);
      $currency = strip_tags($currency);

  $columnList = array('NUMBER', 'NAME', 'QUANTITY', 'MEASURE', 'PRICE', 'VAT_RATE', 'SUM');
  $arCols = array();
  $vatRateColumn = 0;
  foreach ($columnList as $column)
  {
      if ($params['BILL_COLUMN_'.$column.'_SHOW'] == 'Y')
      {
          $captionCustom = Loc::getMessage('SALE_HPS_BILL_COLUMN_'.$column.'_VALUE');
          $caption = (strlen($captionCustom) > 0) ? $captionCustom : $params['BILL_COLUMN_'.$column.'_TITLE'];
          if (in_array($column, array('PRICE', 'SUM')))
              $caption .= ', '.$currency;

          $arCols[$column] = array(
              'NAME' => CSalePdf::prepareToPdf($caption),
              'SORT' => $params['BILL_COLUMN_'.$column.'_SORT']
          );
      }
  }
  if ($params['USER_COLUMNS'])
  {
      $columnList = array_merge($columnList, array_keys($params['USER_COLUMNS']));
      foreach ($params['USER_COLUMNS'] as $id => $val)
      {
          $arCols[$id] = array(
              'NAME' => CSalePdf::prepareToPdf($val['NAME']),
              'SORT' => $val['SORT']
          );
      }
  }
//ADDTESTLINES 1
//$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();
  uasort($arCols, function ($a, $b) {return ($a['SORT'] < $b['SORT']) ? -1 : 1;});
  $arColumnKeys = array_keys($arCols);
  $columnCount = count($arColumnKeys);

  if (count($params['BASKET_ITEMS']) > 0)
  {
      $arCells = array();
      $arProps = array();

      $n = 0;
      $sum = 0.00;
      $vat = 0;
      foreach ($params['BASKET_ITEMS'] as $basketItem)
      {
          $productName = $basketItem["NAME"];
          if ($productName == "OrderDelivery")
              $productName = Loc::getMessage('SALE_HPS_BILL_DELIVERY');
          else if ($productName == "OrderDiscount")
              $productName = Loc::getMessage('SALE_HPS_BILL_DISCOUNT');

          if ($basketItem['IS_VAT_IN_PRICE'])
              $basketItemPrice = $basketItem['PRICE'];
          else
              $basketItemPrice = $basketItem['PRICE']*(1 + $basketItem['VAT_RATE']);

          $arCells[++$n] = array();
          foreach ($arCols as $columnId => $col)
          {
              $data = null;

              switch ($columnId)
              {
                  case 'NUMBER':
                      $data = CSalePdf::prepareToPdf($n);
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  case 'NAME':
                      $data = CSalePdf::prepareToPdf($productName);
                      break;
                  case 'QUANTITY':
                      $data = CSalePdf::prepareToPdf(roundEx($basketItem['QUANTITY'], SALE_VALUE_PRECISION));
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  case 'MEASURE':
                      $data = CSalePdf::prepareToPdf($basketItem["MEASURE_NAME"] ? $basketItem["MEASURE_NAME"] : Loc::getMessage('SALE_HPS_BILL_BASKET_MEASURE_DEFAULT'));
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  case 'PRICE':
                      $data = CSalePdf::prepareToPdf(SaleFormatCurrency($basketItem['PRICE'], $basketItem['CURRENCY'], true));
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  case 'VAT_RATE':
                      $data = CSalePdf::prepareToPdf(roundEx($basketItem['VAT_RATE']*100, SALE_VALUE_PRECISION)."%");
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  case 'SUM':
                      $data = CSalePdf::prepareToPdf(SaleFormatCurrency($basketItemPrice * $basketItem['QUANTITY'], $basketItem['CURRENCY'], true));
                      $arCols[$columnId]['IS_DIGIT'] = true;
                      break;
                  default:
                      if (preg_match('/[^0-9 ,\.]/', $basketItem[$columnId]) === 0)
                      {
                          if (!array_key_exists('IS_DIGIT', $arCols[$columnId]))
                              $arCols[$columnId]['IS_DIGIT'] = true;
                      }
                      else
                      {
                          $arCols[$columnId]['IS_DIGIT'] = false;
                      }
                      $data = ($basketItem[$columnId]) ? CSalePdf::prepareToPdf($basketItem[$columnId]) : '';
              }
              if ($data !== null)
                  $arCells[$n][$columnId] = $data;
          }

          $arProps[$n] = array();
          foreach ($basketItem['PROPS'] as $basketPropertyItem)
          {

              if($basketPropertyItem['CODE'] != 'YEARS')
                continue;
              if ($basketPropertyItem['CODE'] == 'CATALOG.XML_ID' || $basketPropertyItem['CODE'] == 'PRODUCT.XML_ID')
                  continue;
              $basketitemPropertyName = $basketPropertyItem["NAME"];
              if($basketPropertyItem['CODE'] == 'YEARS' /*&& SITE_ID != "s1"*/){
                $basketitemPropertyName = $MESS[SITE_ID]["BILL_YEAR_GARANTY"];//"Subscription extension (number of years)";
              }

              $arProps[$n][] = $pdf::prepareToPdf(sprintf("%s: %s", $basketitemPropertyName, $basketPropertyItem["VALUE"]));
          }

          $sum += doubleval($basketItem['PRICE'] * $basketItem['QUANTITY']);
          $vat = max($vat, $basketItem['VAT_RATE']);
      }

      if ($vat <= 0)
      {
          unset($arCols['VAT_RATE']);
          $columnCount = count($arCols);
          $arColumnKeys = array_keys($arCols);
          foreach ($arCells as $i => $cell)
              unset($arCells[$i]['VAT_RATE']);
      }

      if ($params['DELIVERY_PRICE'] > 0)
      {
          $sDeliveryItem = Loc::getMessage('SALE_HPS_BILL_DELIVERY');
          if ($params['DELIVERY_NAME'])
              $sDeliveryItem .= sprintf(" (%s)", $params['DELIVERY_NAME']);
          $arCells[++$n] = array();
          foreach ($arCols as $columnId => $col)
          {
              $data = null;

              switch ($columnId)
              {
                  case 'NUMBER':
                      $data = CSalePdf::prepareToPdf($n);
                      break;
                  case 'NAME':
                      $data = CSalePdf::prepareToPdf($sDeliveryItem);
                      break;
                  case 'QUANTITY':
                      $data = CSalePdf::prepareToPdf(1);
                      break;
                  case 'MEASURE':
                      $data = CSalePdf::prepareToPdf('');
                      break;
                  case 'SUM':
                  case 'PRICE':
                      $data = CSalePdf::prepareToPdf(SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true));
                      break;
                  case 'VAT_RATE':
                      $data = CSalePdf::prepareToPdf(roundEx($params['DELIVERY_VAT_RATE']*100, SALE_VALUE_PRECISION)."%");
                      break;
                  default:
                      $data = '';
              }
              if ($data !== null)
                  $arCells[$n][$columnId] = $data;
          }

          $sum += doubleval($params['DELIVERY_PRICE']);
      }

      $cntBasketItem = $n;
      if ($params['BILL_TOTAL_SHOW'] == 'Y')
      {
          $eps = 0.0001;
          if ($params['SUM'] - $sum > $eps)
          {
              $arCells[++$n] = array();
              for ($i = 0; $i < $columnCount; $i++)
                  $arCells[$n][$arColumnKeys[$i]] = null;

              $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SUBTOTAL'));
              $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($sum, $params['CURRENCY'], true));
          }

          if ($params['TAXES'])
          {
              foreach ($params['TAXES'] as $tax)
              {
                  $arCells[++$n] = array();
                  for ($i = 0; $i < $columnCount; $i++)
                      $arCells[$n][$arColumnKeys[$i]] = null;

                  $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(sprintf(
                      "%s%s%s:",
                      ($tax["IS_IN_PRICE"] == "Y") ? Loc::getMessage('SALE_HPS_BILL_INCLUDING') : "",
                      $tax["TAX_NAME"],
                      ($vat <= 0 && $tax["IS_PERCENT"] == "Y") ? sprintf(' (%s%%)', roundEx($tax["VALUE"], SALE_VALUE_PRECISION)) : ""
                  ));
                  $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($tax["VALUE_MONEY"], $params['CURRENCY'], true));
              }
          }

          if (!$params['TAXES'])
          {
              $arCells[++$n] = array();
              for ($i = 0; $i < $columnCount; $i++)
                  $arCells[$n][$arColumnKeys[$i]] = null;

              $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE'));
              $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE_NO'));
          }

          /*if ($params['SUM_PAID'] > 0)
          {
              $arCells[++$n] = array();
              for ($i = 0; $i < $columnCount; $i++)
                  $arCells[$n][$arColumnKeys[$i]] = null;

              $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_PAID'));
              $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['SUM_PAID'], $params['CURRENCY'], true));
          }*/

          if ($params['DISCOUNT_PRICE'] > 0)
          {
              $arCells[++$n] = array();
              for ($i = 0; $i < $columnCount; $i++)
                  $arCells[$n][$arColumnKeys[$i]] = null;

              $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_DISCOUNT'));
              $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['DISCOUNT_PRICE'], $params['CURRENCY'], true));
          }


          $arCells[++$n] = array();
          for ($i = 0; $i < $columnCount; $i++)
              $arCells[$n][$arColumnKeys[$i]] = null;

          $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_SUM'));
          $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['SUM'], $params['CURRENCY'], true));
      }

      $rowsInfo = $pdf->calculateRowsWidth($arCols, $arCells, $cntBasketItem, $width);
      $arRowsWidth = $rowsInfo['ROWS_WIDTH'];
      $arRowsContentWidth = $rowsInfo['ROWS_CONTENT_WIDTH'];
  }
  $pdf->Ln();

  $x0 = $pdf->GetX();
  $y0 = $pdf->GetY();
//ADDTESTLINES 2
//$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();$pdf->Ln();
  $k = 0;
  do
  {
      $newLine = false;
      foreach ($arCols as $columnId => $column)
      {
          list($string, $arCols[$columnId]['NAME']) = $pdf->splitString($column['NAME'], $arRowsContentWidth[$columnId]);
          if ($vat > 0 || $columnId !== 'VAT_RATE')
              $pdf->Cell($arRowsWidth[$columnId], 20, $string, 0, 0, $k ? 'L' : 'C');

          if ($arCols[$columnId]['NAME'])
          {
              $k++;
              $newLine = true;
          }

          $i = array_search($columnId, $arColumnKeys);
          ${"x".($i+1)} = $pdf->GetX();
      }

      $pdf->Ln();
  }
  while($newLine);

  $y5 = $pdf->GetY();

  $pdf->Line($x0, $y0, ${"x".$columnCount}, $y0);
  for ($i = 0; $i <= $columnCount; $i++)
  {
      if ($vat > 0 || $arColumnKeys[$i] != 'VAT_RATE')
          $pdf->Line(${"x$i"}, $y0, ${"x$i"}, $y5);
  }
  $pdf->Line($x0, $y5, ${'x'.$columnCount}, $y5);

  $rowsCnt = count($arCells);
  for ($n = 1; $n <= $rowsCnt; $n++)
  {
      $arRowsWidth_tmp = $arRowsWidth;
      $arRowsContentWidth_tmp = $arRowsContentWidth;
      $accumulated = 0;
      $accumulatedContent = 0;
      foreach ($arCols as $columnId => $column)
      {
          if (is_null($arCells[$n][$columnId]))
          {
              $accumulated += $arRowsWidth_tmp[$columnId];
              $arRowsWidth_tmp[$columnId] = null;
              $accumulatedContent += $arRowsContentWidth_tmp[$columnId];
              $arRowsContentWidth_tmp[$columnId] = null;
          }
          else
          {
              $arRowsWidth_tmp[$columnId] += $accumulated;
              $arRowsContentWidth_tmp[$columnId] += $accumulatedContent;
              $accumulated = 0;
              $accumulatedContent = 0;
          }
      }

      $x0 = $pdf->GetX();
      $y0 = $pdf->GetY();

      $pdf->SetFont($fontFamily, '', $fontSize*0.95);

      $l = 0;
      do
      {
          $newLine = false;
          foreach ($arCols as $columnId => $column)
          {
              $yAddLines = $pdf->GetY();
              $string = '!';
              $strArr = explode("#br#", $arCells[$n][$columnId]);
              foreach($strArr as $key => $strValue){
                if($key > 0){
                  $pdf->Ln();
                  $pdf->Cell($arRowsWidth_tmp['NUMBER'], 15, ($l == 0) ? '' : '', 0, 0, 'C');
                }
                $yAddLinesName = $pdf->GetY();
                if (!is_null($arCells[$n][$columnId]))
                    list($string, $arCells[$n][$columnId]) = $pdf->splitString($strValue, $arRowsContentWidth_tmp[$columnId]);

                $rowWidth = $arRowsWidth_tmp[$columnId];

                if (in_array($columnId, array('QUANTITY', 'MEASURE', 'PRICE', 'SUM')))
                {
                    if (!is_null($arCells[$n][$columnId]))
                    {
                        $pdf->Cell($rowWidth, 15, $string, 0, 0, 'R');
                    }
                }
                elseif ($columnId == 'NUMBER')
                {
                    if (!is_null($arCells[$n][$columnId]))
                        $pdf->Cell($rowWidth, 15, ($l == 0) ? $string : '', 0, 0, 'C');
                }
                elseif ($columnId == 'NAME')
                {

                    if (!is_null($arCells[$n][$columnId])){
                        $pdf->Cell($rowWidth, 15, $string, 0, 0,  ($n > $cntBasketItem) ? 'R' : '');
                        if($yAddLinesName > 770 && count($strArr) > 1){
                          for ($i = 0; $i <= $columnCount; $i++)
                          {
                            $pdf->Line(${"x$i"}, $yAddLinesName, ${"x$i"}, 800);
                          }
                        }
                        
                    }
                }
                elseif ($columnId == 'VAT_RATE')
                {
                    if (!is_null($arCells[$n][$columnId]))
                        $pdf->Cell($rowWidth, 15, $string, 0, 0, 'R');
                }
                else
                {
                    if (!is_null($arCells[$n][$columnId]))
                    {
                        $pdf->Cell($rowWidth, 15, $string, 0, 0,   ($n > $cntBasketItem) ? 'R' : 'L');
                    }
                }

                if ($l == 0)
                {
                    $pos = array_search($columnId, $arColumnKeys);
                    ${'x'.($pos+1)} = $pdf->GetX();
                }

                if ($arCells[$n][$columnId])
                    $newLine = true;
              }
          }

          $pdf->Ln();
          $l++;
      }
      while($newLine);

      $yAddLines = $pdf->GetY();
    
	if ($params['BILL_COLUMN_NAME_SHOW'] == 'Y')
	{
		if (isset($arProps[$n]) && is_array($arProps[$n]))
		{
			$pdf->SetFont($fontFamily, '', $fontSize - 2);
            if($yAddLines > 800 - $fontSize){
              foreach ($arProps[$n] as $property)
              {
                $i = 0;
				$line = 0;
                foreach ($arCols as $columnId => $caption)
                {
                  $i++;
                  if ($i == $columnCount)
						$line = 1;
                  if ($columnId == 'NAME'){
                    for ($i = ($n > $cntBasketItem) ? $columnCount - 1 : 0; $i <= $columnCount; $i++)
                    {
                        if ($vat > 0 || $arColumnKeys[$i] != 'VAT_RATE')
                            $pdf->Line(${"x$i"}, $y0, ${"x$i"}, 800);
                    }
                  }
                }
              }
              
            }
              
			foreach ($arProps[$n] as $property)
			{
				$i = 0;
				$line = 0;
				foreach ($arCols as $columnId => $caption)
				{
					$i++;
					if ($i == $columnCount)
						$line = 1;
					if ($columnId == 'NAME')
						$pdf->Cell($arRowsWidth_tmp[$columnId] , 12, $property, 0, $line);
					else
						$pdf->Cell($arRowsWidth_tmp[$columnId] , 12, '', 0, $line);
				}
			}
		}
	}

      $y5 = $pdf->GetY();
      if($y5 < $y0){
        if(800-$y0 > 15){
          //$pageNum = $pdf->PageNo();
          //$pdf->page = ($pageNum-1);

          for ($i = ($n > $cntBasketItem) ? $columnCount - 1 : 0; $i <= $columnCount; $i++)
          {

          }

        }else{
          $pdf->Line($x0, $margin['top'], ${'x'.$columnCount}, $margin['top']);
        }
        $y0 = $margin['top'];
      }

      for ($i = ($n > $cntBasketItem) ? $columnCount - 1 : 0; $i <= $columnCount; $i++)
      {
          if ($vat > 0 || $arColumnKeys[$i] != 'VAT_RATE')
              $pdf->Line(${"x$i"}, $y0, ${"x$i"}, $y5);
      }

      $pdf->Line(($n <= $cntBasketItem) ? $x0 : ${'x'.($columnCount-1)}, $y5, ${'x'.$columnCount}, $y5);
  }
  $pdf->Ln();


  $commentText = $subParamsFN[SITE_ID]['BILL_COMMENT'];
$pdf->Write(15, HTMLToTxt(preg_replace(
    array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
    CSalePdf::prepareToPdf($commentText)
), '', array(), 0));
$pdf->Ln();

$pdf->Ln();


  if ($params['BILL_TOTAL_SHOW'] == 'Y')
  {

      $pdf->SetFont($fontFamily, 'B', $fontSize);
      $pdf->Write(15, CSalePdf::prepareToPdf(strip_tags(SaleFormatCurrency(
                                                            $params['SUM'],
                                                            $params["CURRENCY"],
                                                            false
                                                        ))));
      $pdf->Ln();
      $pdf->Ln();
  }

  $pdf->SetFont($fontFamily, '', $fontSize - 2);
  $pdf->Write(12, HTMLToTxt(preg_replace(
      array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
      CSalePdf::prepareToPdf($oferta)
  ), '', array(), 0));

  $pdf->Ln();
  $pdf->Ln();

  if ($params['BILL_SIGN_SHOW'] == 'Y')
  {
      if ($params['BILL_PATH_TO_STAMP'])
      {
          $filePath = $pdf->GetImagePath($params['BILL_PATH_TO_STAMP']);

          if ($filePath != '' && !$blank && \Bitrix\Main\IO\File::isFileExists($filePath))
          {
              list($stampHeight, $stampWidth) = $pdf->GetImageSize($params['BILL_PATH_TO_STAMP']);
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
                          $params['BILL_PATH_TO_STAMP'],
                          $margin['left'] + 40, $pdf->GetY(),
                          $stampWidth, $stampHeight
                  );
              }
          }
      }

      $pdf->SetFont($fontFamily, 'B', $fontSize);

      if ($params["SELLER_COMPANY_DIRECTOR_POSITION"])
      {
          $isDirSign = false;
          if (!$blank && $params['SELLER_COMPANY_DIR_SIGN'])
          {
              list($signHeight, $signWidth) = $pdf->GetImageSize($params['SELLER_COMPANY_DIR_SIGN']);

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
                      $params['SELLER_COMPANY_DIR_SIGN'],
                  $pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
                  $signWidth, $signHeight
              );
          }

          $x1 = $pdf->GetX();
          $pdf->Cell(160, 15, '');
          $x2 = $pdf->GetX();

          if ($params["SELLER_COMPANY_DIRECTOR_NAME"])
              $pdf->Write(15, CSalePdf::prepareToPdf('('.$params["SELLER_COMPANY_DIRECTOR_NAME"].')'));
          $pdf->Ln();

          $y2 = $pdf->GetY();
          $pdf->Line($x1, $y2, $x2, $y2);

          $pdf->Ln();
      }

      if ($params["SELLER_COMPANY_ACCOUNTANT_POSITION"])
      {
          $isAccSign = false;
          if (!$blank && $params['SELLER_COMPANY_ACC_SIGN'])
          {
              list($signHeight, $signWidth) = $pdf->GetImageSize($params['SELLER_COMPANY_ACC_SIGN']);

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
                  $params['SELLER_COMPANY_ACC_SIGN'],
                  $pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
                  $signWidth, $signHeight
              );
          }

          $x1 = $pdf->GetX();
          $pdf->Cell(($params["SELLER_COMPANY_DIRECTOR_NAME"]) ? $x2-$x1 : 160, 15, '');
          $x2 = $pdf->GetX();

          if ($params["SELLER_COMPANY_ACCOUNTANT_NAME"])
              $pdf->Write(15, CSalePdf::prepareToPdf('('.$params["SELLER_COMPANY_ACCOUNTANT_NAME"].')'));
          $pdf->Ln();

          $y2 = $pdf->GetY();
          $pdf->Line($x1, $y2, $x2, $y2);
      }
  }

  $dest = 'I';
  if ($_REQUEST['GET_CONTENT'] == 'Y')
      $dest = 'S';
  else if ($_REQUEST['DOWNLOAD'] == 'Y')
      $dest = 'D';

  $fileName = sprintf(
      'Invoice_No_%s__%s.pdf',
      str_replace(
          array(
              chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11),
              chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22),
              chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31),
              '"', '*', '/', ':', '<', '>', '?', '\\', '|'
          ),
          '_',
          strval($params["ACCOUNT_NUMBER"])
      ),
      $arOrder["DATE_INSERT"]->format('d-m-Y')    
  );

  $trFileName = CUtil::translit($fileName, 'ru', array('max_len' => 1024, 'safe_chars' => '.', 'replace_space' => '-'));
	header("Content-type:application/pdf");
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
    $pdf->Output($trFileName, $dest, $fileName);
   
	exit;

}else{
  print_r("Указанный заказ не найден, возможно вы пытаетесь открыть не свой заказ TODO: переделать на редирект в ЛК");
  LocalRedirect(SITE_DIR . 'personal/');
  die();
  //ShowError(GetMessage('SOP_ORDER_NOT_FOUND'));
}