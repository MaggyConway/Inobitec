<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?=Loc::getMessage('SALE_HPS_BILL_TITLE')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
<style type="text/css">
	table { border-collapse: collapse; }
	table.acc td { border: 1pt solid #000000; padding: 0pt 3pt; line-height: 18pt; }
	table.it td { border: 1pt solid #000000; padding: 0pt 3pt; }
	table.sign td { font-weight: bold; vertical-align: bottom; }
	table.header td { padding: 0pt; vertical-align: top; }
</style>
</head>

<?
$orderID = $_GET["ORDER_ID"];
$arrProps = array();
header('Location: '.$_SERVER['REQUEST_URI']."&pdf=1");
die();

$obOrder = CSaleOrder::GetByID($orderID);
$payerName = "";
switch($obOrder["PERSON_TYPE_ID"]){
  case 3:
    $payerName = Loc::getMessage('SALE_HPS_BILL_IP');
    break;
  default:
    break;
}

$oferta = \COption::GetOptionString( "askaron.settings", "UF_BILL_OFERTA_RU" );

$db_props = CSaleOrderPropsValue::GetOrderProps($orderID);
$iGroup = -1;
while ($arProps = $db_props->Fetch())
{
   if ($iGroup!=IntVal($arProps["PROPS_GROUP_ID"]))
   {
     // echo "<b>".$arProps["GROUP_NAME"]."</b><br>";
      $iGroup = IntVal($arProps["PROPS_GROUP_ID"]);
   }
   $arrProps[$arProps["CODE"]] = "";
   //echo $arProps["NAME"].": ";

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

   //echo "<br>";
}

if ($_REQUEST['BLANK'] == 'Y')
	$blank = true;

$pageWidth  = 595.28;
$pageHeight = 841.89;

$background = '#ffffff';
if ($params['BILL_BACKGROUND'])
{
	$path = $params['BILL_BACKGROUND'];
	if (intval($path) > 0)
	{
		if ($arFile = CFile::GetFileArray($path))
			$path = $arFile['SRC'];
	}

	$backgroundStyle = $params['BILL_BACKGROUND_STYLE'];
	if (!in_array($backgroundStyle, array('none', 'tile', 'stretch')))
		$backgroundStyle = 'none';

	if ($path)
	{
		switch ($backgroundStyle)
		{
			case 'none':
				$background = "url('" . $path . "') 0 0 no-repeat";
				break;
			case 'tile':
				$background = "url('" . $path . "') 0 0 repeat";
				break;
			case 'stretch':
				$background = sprintf(
					"url('%s') 0 0 repeat-y; background-size: %.02fpt %.02fpt",
					$path, $pageWidth, $pageHeight
				);
				break;
		}
	}
}

//print_r($params);
//print_r("<br/><br/>");
//print_r(sortOrder($params['BASKET_ITEMS']));

//$params['BASKET_ITEMS'] = sortOrder($params['BASKET_ITEMS']);

//print_r($params['BASKET_ITEMS']);
$res = sortOrder($params['BASKET_ITEMS']);
$params['BASKET_ITEMS'] = $res;
$params["ACCOUNT_NUMBER"] = 300 + $orderID;



if(SITE_ID == "s2")
  $arrProps["WORK_PHONE"] = "+".$arrProps["WORK_PHONE"];
elseif(SITE_ID == "s1")
  $arrProps["WORK_PHONE"] = "+7".$arrProps["WORK_PHONE"];



$margin = array(
	'top' => intval($params['BILL_MARGIN_TOP'] ?: 15) * 72/25.4,
	'right' => intval($params['BILL_MARGIN_RIGHT'] ?: 15) * 72/25.4,
	'bottom' => intval($params['BILL_MARGIN_BOTTOM'] ?: 15) * 72/25.4,
	'left' => intval($params['BILL_MARGIN_LEFT'] ?: 20) * 72/25.4
);

$width = $pageWidth - $margin['left'] - $margin['right'];

?>

<body style="margin: 0pt; padding: 0pt; background: <?=$background; ?>"<? if ($_REQUEST['PRINT'] == 'Y') { ?> onload="setTimeout(window.print, 0);"<? } ?>>

<div style="margin: 0pt; padding: <?=join('pt ', $margin); ?>pt; width: <?=$width; ?>pt; background: <?=$background; ?>">

<?if ($params['BILL_HEADER_SHOW'] == 'Y'):?>
    <? /*
	<table class="header">
		<tr>
			<? if ($params["BILL_PATH_TO_LOGO"]) { ?>
			<td style="padding-right: 5pt; padding-bottom: 5pt; ">
				<? $imgParams = CFile::_GetImgParams($params['BILL_PATH_TO_LOGO']);
					$dpi = intval($params['BILL_LOGO_DPI']) ?: 96;
					$imgWidth = $imgParams['WIDTH'] * 96 / $dpi;
					if ($imgWidth > $pageWidth)
						$imgWidth = $pageWidth * 0.6;
				?>
				<img src="<?=$imgParams['SRC']; ?>" width="<?=$imgWidth; ?>" />
			</td>
			<? } ?>
			<td>
				<b><?=$params["SELLER_COMPANY_NAME"]; ?></b><br><?
				if ($params["SELLER_COMPANY_ADDRESS"]) {
					$sellerAddr = $params["SELLER_COMPANY_ADDRESS"];
					if (is_array($sellerAddr))
						$sellerAddr = implode(', ', $sellerAddr);
					else
						$sellerAddr = str_replace(array("\r\n", "\n", "\r"), ', ', strval($sellerAddr));
					?><b><?= $sellerAddr ?></b><br><?
				} ?>
				<? if ($params["SELLER_COMPANY_PHONE"]) { ?>
				<b><?=Loc::getMessage('SALE_HPS_BILL_SELLER_COMPANY_PHONE', array('#PHONE#' => $params["SELLER_COMPANY_PHONE"]));?></b><br>
				<? } ?>
			</td>
		</tr>
	</table>
    */?>
	<?
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
		$sellerRs = $params["SELLER_COMPANY_BANK_ACCOUNT"];
	}
	else
	{
		$rsPattern = '/\s*\d{10,100}\s*/';

		$sellerBank = trim(preg_replace($rsPattern, ' ', $params["SELLER_COMPANY_BANK_ACCOUNT"]));

		preg_match($rsPattern, $params["SELLER_COMPANY_BANK_ACCOUNT"], $matches);
		$sellerRs = trim($matches[0]);
	}

	?>
	<table class="acc" width="100%">
		<colgroup>
			<col width="29%">
			<col width="29%">
			<col width="10%">
			<col width="32%">
		</colgroup>
		<tr>
			<td >
				<? if ($params["SELLER_COMPANY_INN"]) { ?>
				<?=Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $params["SELLER_COMPANY_INN"]));?>
				<? } else { ?>
				&nbsp;
				<? } ?>
			</td>
			<td>
				<? if ($params["SELLER_COMPANY_KPP"]) { ?>
				<?=Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"]));?>
				<? } else { ?>
				&nbsp;
				<? } ?>
			</td>
			<td rowspan="2">
				<br>
				<br>
				<?=Loc::getMessage("SALE_HPS_BILL_SELLER_ACC"); ?>
			</td>
			<td rowspan="2">
				<br>
				<br>
				<?=$sellerRs; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?=Loc::getMessage('SALE_HPS_BILL_SELLER_NAME')?><br>
				<?=$params["SELLER_COMPANY_NAME"]; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?=Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_NAME')?><br>
				<?=$sellerBank; ?>
			</td>
			<td>
				<?=Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_BIK')?><br>
				<?=Loc::getMessage('SALE_HPS_BILL_SELLER_ACC_CORR')?><br>
			</td>
			<td>
				<?=$params["SELLER_COMPANY_BANK_BIC"]; ?><br>
				<?=$params["SELLER_COMPANY_BANK_ACCOUNT_CORR"]; ?>
			</td>
		</tr>
        <tr>
          <td colspan="4">
          <?=Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE');?><br>
          <?=Loc::getMessage('SALE_HPS_BILL_PFYMENT_PURPOSE_DATA', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]));?>
           </td>
        </tr>
	</table>
<?endif;?>
<br>

<table width="100%">
	<colgroup>
		<col width="50%">
		<col width="0">
		<col width="50%">
	</colgroup>
<?if ($params['BILL_HEADER']):?>
	<tr>
		<td></td>
		<td style="font-size: 1.5em; font-weight: bold; text-align: center">
			<nobr><?=Loc::getMessage('SALE_HPS_BILL_TITLE');?> <?=Loc::getMessage('SALE_HPS_BILL_SELLER_TITLE', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"], '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]));?>
			</nobr>
		</td>
		<td></td>
	</tr>
<?endif;?>
<? if ($params["BILL_ORDER_SUBJECT"]) { ?>
	<tr>
		<td></td>
		<td><?=$params["BILL_ORDER_SUBJECT"]; ?></td>
		<td></td>
	</tr>
<? } ?>
<? if ($params["PAYMENT_DATE_PAY_BEFORE"]) { ?>
	<tr>
		<td></td>
		<td>
			<?=Loc::getMessage('SALE_HPS_BILL_SELLER_DATE_END', array('#PAYMENT_DATE_END#' => ConvertDateTime($params["PAYMENT_DATE_PAY_BEFORE"], FORMAT_DATE) ?: $params["PAYMENT_DATE_PAY_BEFORE"]));?>
		</td>
		<td></td>
	</tr>
<? } ?>
</table >
<br/>
<br/>
<table class="acc" width="100%">
  <tr>
    <td style="line-height: 18pt;"><?=Loc::getMessage('SALE_HPS_BILL_LICENSOR');?></td>
    <td style="line-height: 18pt;">
      <?=$params["SELLER_COMPANY_NAME"]; ?>, <?=Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $params["SELLER_COMPANY_INN"]));?>, <?=Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"]));?><br/>
      <?=$params["SELLER_COMPANY_ADDRESS"]?>
    </td>
  </tr>
  <tr>
    <td style="line-height: 18pt;"><?=Loc::getMessage('SALE_HPS_BILL_LICENSEE');?></td>
    <td style="line-height: 18pt;"><?=$payerName?><?=$arrProps["NAME"]?>, <?=Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $arrProps["INN"]));?>, <?=Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $arrProps["BIK"]));?><br/>
      <?=$arrProps["MAIL_ADDRESS"]?>, <?=$arrProps["WORK_PHONE"]?>, <?=$arrProps["EMAIL"]?>
    </td>
  </tr>
</table>

<br>
<?

if ($params['BILL_PAYER_SHOW'] == 'Y'):
	if ($params["BUYER_PERSON_COMPANY_NAME"]) {
		echo Loc::getMessage('SALE_HPS_BILL_BUYER_NAME', array('#BUYER_NAME#' => $params["BUYER_PERSON_COMPANY_NAME"]));
		if ($params["BUYER_PERSON_COMPANY_INN"])
			echo Loc::getMessage('SALE_HPS_BILL_BUYER_INN', array('#INN#' => $params["BUYER_PERSON_COMPANY_INN"]));
		if ($params["BUYER_PERSON_COMPANY_ADDRESS"])
		{
			$buyerAddr = $params["BUYER_PERSON_COMPANY_ADDRESS"];
			if (is_array($buyerAddr))
				$buyerAddr = implode(', ', $buyerAddr);
			else
				$buyerAddr = str_replace(array("\r\n", "\n", "\r"), ', ', strval($buyerAddr));
			echo sprintf(", %s", $buyerAddr);
		}
		if ($params["BUYER_PERSON_COMPANY_PHONE"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_PHONE"]);
		if ($params["BUYER_PERSON_COMPANY_FAX"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_FAX"]);
		if ($params["BUYER_PERSON_COMPANY_NAME_CONTACT"])
			echo sprintf(", %s", $params["BUYER_PERSON_COMPANY_NAME_CONTACT"]);
	}
endif;
?>

<br>
<br>

<?
$arCurFormat = CCurrencyLang::GetCurrencyFormat($params['CURRENCY']);
$currency = preg_replace('/(^|[^&])#/', '${1}', $arCurFormat['FORMAT_STRING']);

$cells = array();
$props = array();

$n = 0;
$sum = 0.00;
$vat = 0;
$cntBasketItem = 0;

$columnList = array('NUMBER', 'NAME', 'QUANTITY', 'MEASURE', 'PRICE', 'VAT_RATE', 'SUM');
//$columnList = array('NUMBER', 'NAME', 'PRICE', 'VAT_RATE', 'SUM');
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
			'NAME' => $caption,
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
			'NAME' => $val['NAME'],
			'SORT' => $val['SORT']
		);
	}
}

uasort($arCols, function ($a, $b) {return ($a['SORT'] < $b['SORT']) ? -1 : 1;});

$arColumnKeys = array_keys($arCols);
$columnCount = count($arColumnKeys);

if ($params['BASKET_ITEMS'])
{
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

		$cells[++$n] = array();
		foreach ($arCols as $columnId => $caption)
		{
			$data = null;

			switch ($columnId)
			{
				case 'NUMBER':
					$data = $n;
					break;
				case 'NAME':
					$data = htmlspecialcharsbx($productName);
					break;
				case 'QUANTITY':
					$data = roundEx($basketItem['QUANTITY'], SALE_VALUE_PRECISION);
					break;
				case 'MEASURE':
					$data = $basketItem["MEASURE_NAME"] ? htmlspecialcharsbx($basketItem["MEASURE_NAME"]) : Loc::getMessage('SALE_HPS_BILL_BASKET_MEASURE_DEFAULT');
					break;
				case 'PRICE':
					$data = SaleFormatCurrency($basketItem['PRICE'], $basketItem['CURRENCY'], true);
					break;
				case 'VAT_RATE':
					$data = roundEx($basketItem['VAT_RATE'] * 100, SALE_VALUE_PRECISION)."%";
					break;
				case 'SUM':
					$data = SaleFormatCurrency($basketItemPrice * $basketItem['QUANTITY'], $basketItem['CURRENCY'], true);
					break;
				default :
					$data = ($basketItem[$columnId]) ?: '';
			}
			if ($data !== null)
				$cells[$n][$columnId] = $data;
		}
		$props[$n] = array();
		/** @var \Bitrix\Sale\BasketPropertyItem $basketPropertyItem */
		if ($basketItem['PROPS'])
		{
			foreach ($basketItem['PROPS'] as $basketPropertyItem)
			{
                if($basketPropertyItem['CODE'] != 'YEARS')
                  continue;
				if ($basketPropertyItem['CODE'] == 'CATALOG.XML_ID' || $basketPropertyItem['CODE'] == 'PRODUCT.XML_ID')
					continue;
                $basketitemPropertyName = $basketPropertyItem["NAME"];
                if($basketPropertyItem['CODE'] == 'YEARS' && SITE_ID == "s2"){
                  $basketitemPropertyName = "Increase the subscription validity term (number of years)";
                }
				$props[$n][] = htmlspecialcharsbx(sprintf("%s: %s", $basketitemPropertyName, $basketPropertyItem["VALUE"]));
			}
		}
		$sum += doubleval($basketItem['PRICE'] * $basketItem['QUANTITY']);
		$vat = max($vat, $basketItem['VAT_RATE']);
	}
}

if ($vat <= 0)
{
	unset($arCols['VAT_RATE']);
	$columnCount = count($arCols);
	$arColumnKeys = array_keys($arCols);
	foreach ($cells as $i => $cell)
		unset($cells[$i]['VAT_RATE']);
}

if ($params['DELIVERY_PRICE'] > 0)
{
	$deliveryItem = Loc::getMessage('SALE_HPS_BILL_DELIVERY');

	if ($params['DELIVERY_NAME'])
		$deliveryItem .= sprintf(" (%s)", $params['DELIVERY_NAME']);
	$cells[++$n] = array();
	foreach ($arCols as $columnId => $caption)
	{
		$data = null;

		switch ($columnId)
		{
			case 'NUMBER':
				$data = $n;
				break;
			case 'NAME':
				$data = htmlspecialcharsbx($deliveryItem);
				break;
			case 'QUANTITY':
				$data = 1;
				break;
			case 'MEASURE':
				$data = '';
				break;
			case 'PRICE':
				$data = SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true);
				break;
			case 'VAT_RATE':
				$data = roundEx($params['DELIVERY_VAT_RATE'] * 100, SALE_VALUE_PRECISION)."%";
				break;
			case 'SUM':
				$data = SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true);
				break;
		}
		if ($data !== null)
			$cells[$n][$columnId] = $data;
	}
	$sum += doubleval($params['DELIVERY_PRICE']);
}

if ($params['BILL_TOTAL_SHOW'] == 'Y')
{
	$cntBasketItem = $n;
	$eps = 0.0001;
	if ($params['SUM'] - $sum > $eps)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_SUBTOTAL');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($sum, $params['CURRENCY'], true);
	}

	if ($params['TAXES'])
	{
		foreach ($params['TAXES'] as $tax)
		{
			$cells[++$n] = array();
			for ($i = 0; $i < $columnCount; $i++)
				$cells[$n][$arColumnKeys[$i]] = null;

			$cells[$n][$arColumnKeys[$columnCount-2]] = htmlspecialcharsbx(sprintf(
					"%s%s%s:",
					($tax["IS_IN_PRICE"] == "Y") ? Loc::getMessage('SALE_HPS_BILL_INCLUDING') : "",
					$tax["TAX_NAME"],
					($vat <= 0 && $tax["IS_PERCENT"] == "Y")
							? sprintf(' (%s%%)', roundEx($tax["VALUE"], SALE_VALUE_PRECISION))
							: ""
			));
			$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($tax["VALUE_MONEY"], $params['CURRENCY'], true);
		}
	}

	if (!$params['TAXES'])
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$i] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = htmlspecialcharsbx(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE'));
		$cells[$n][$arColumnKeys[$columnCount-1]] = htmlspecialcharsbx(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE_NO'));
	}

	if ($params['SUM_PAID'] > 0)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_PAID');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['SUM_PAID'], $params['CURRENCY'], true);
	}
	if ($params['DISCOUNT_PRICE'] > 0)
	{
		$cells[++$n] = array();
		for ($i = 0; $i < $columnCount; $i++)
			$cells[$n][$arColumnKeys[$i]] = null;

		$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_DISCOUNT');
		$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['DISCOUNT_PRICE'], $params['CURRENCY'], true);
	}

	$cells[++$n] = array();
	for ($i = 0; $i < $columnCount; $i++)
		$cells[$n][$arColumnKeys[$i]] = null;

	$cells[$n][$arColumnKeys[$columnCount-2]] = Loc::getMessage('SALE_HPS_BILL_TOTAL_SUM');
	$cells[$n][$arColumnKeys[$columnCount-1]] = SaleFormatCurrency($params['SUM'], $params['CURRENCY'], true);
}
?>
<table class="it" width="100%">
	<tr>
	<?foreach ($arCols as $columnId => $col):?>
		<td><?=$col['NAME'];?></td>
	<?endforeach;?>
	</tr>
<?

$rowsCnt = count($cells);
for ($n = 1; $n <= $rowsCnt; $n++):

	$accumulated = 0;
?>
	<tr valign="top">
	<?foreach ($arCols as $columnId => $col):?>
		<?
			if (!is_null($cells[$n][$columnId]))
			{
				if ($columnId === 'NUMBER')
				{?>
					<td align="center"><?=$cells[$n][$columnId];?></td>
				<?}
				elseif ($columnId === 'NAME')
				{
				?>
					<td align="<?=($n > $cntBasketItem) ? 'right' : 'left';?>"
						style="word-break: break-word; word-wrap: break-word; <? if ($accumulated) {?>border-width: 0pt 1pt 0pt 0pt; <? } ?>"
						<? if ($accumulated) { ?>colspan="<?=($accumulated+1); ?>"<? $accumulated = 0; } ?>>
                      
						<? 
                          $strArr = explode("#br#", ($cells[$n][$columnId])); 
                          foreach($strArr as $key => $val){
                            if($key > 0 ){
                              ?><br><?
                            }
                            echo $val;
                            
                          }
                        ?>
						<? if (isset($props[$n]) && is_array($props[$n])) { ?>
						<? foreach ($props[$n] as $property) { ?>
						<br>
						<small><?=$property; ?></small>
						<? } ?>
						<? } ?>
					</td>
				<?}
				else
				{
					if (!is_null($cells[$n][$columnId]))
					{
						if ($columnId != 'VAT_RATE' || $vat > 0 || is_null($cells[$n][$columnId]) || $n > $cntBasketItem)
						{ ?>
							<td align="right" data-info="<?=$columnId?>"
								<? if ($accumulated) { ?>
								style="/*border-width: 0pt 1pt 0pt 0pt*/"
								colspan="<?=(($columnId == 'VAT_RATE' && $vat <= 0) ? $accumulated : $accumulated+1); ?>"
								<? $accumulated = 0; } ?>>
								<?if ($columnId == 'SUM' || $columnId == 'PRICE'):?>
									<nobr><?=$cells[$n][$columnId];?></nobr>
								<?else:?>
									<?=$cells[$n][$columnId]; ?>
								<?endif;?>
							</td>
						<? }
					}
					else
					{
						$accumulated++;
					}
				}
			}
			else
			{
				$accumulated++;
			}
		?>
	<?endforeach;?>
	</tr>

<?endfor;?>
</table>
<br>
<?=Loc::getMessage('SALE_HPS_BILL_VAT_COMMENT')?>
<br>
<?=Loc::getMessage('SALE_HPS_BILL_BILL_OFERTA_COMMENT')?>
<br><br>

<?if ($params['BILL_TOTAL_SHOW'] == 'Y'):?>
	<?/*=Loc::getMessage(
			'SALE_HPS_BILL_BASKET_TOTAL',
			array(
					'#BASKET_COUNT#' => $cntBasketItem,
					'#BASKET_PRICE#' => SaleFormatCurrency($params['SUM'], $params['CURRENCY'], false)
			)
	);*/?>
	<br>

	<b>
	<?

	if (in_array($params['CURRENCY'], array("RUR", "RUB")))
	{
		echo Number2Word_Rus($params['SUM']);
	}
	else
	{
		echo SaleFormatCurrency(
			$params['SUM'],
			$params['CURRENCY'],
			false
		);
	}

	?>
	</b>
<?endif;?>
<br>
<br>

<div style="font-size: 0.8em;">
  <?=$oferta;?>
</div>

<?/* if ($params["BILL_COMMENT1"] || $params["BILL_COMMENT2"]) { ?>
<b><?=Loc::getMessage('SALE_HPS_BILL_COND_COMM')?></b>
<br>
	<? if ($params["BILL_COMMENT1"]) { ?>
	<?=nl2br(HTMLToTxt(preg_replace(
		array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
		htmlspecialcharsback($params["BILL_COMMENT1"])
	), '', array(), 0)); ?>
	<br>
	<br>
	<? } ?>
	<? if ($params["BILL_COMMENT2"]) { ?>
	<?=nl2br(HTMLToTxt(preg_replace(
		array('#</div>\s*<div[^>]*>#i', '#</?div>#i'), array('<br>', '<br>'),
		htmlspecialcharsback($params["BILL_COMMENT2"])
	), '', array(), 0)); ?>
	<br>
	<br>
	<? } ?>
<? } */?>

<br>
<br>

<?if ($params['BILL_SIGN_SHOW'] == 'Y'):?>
	<? if (!$blank) { ?>
	<div style="position: relative; "><?=CFile::ShowImage(
			$params["BILL_PATH_TO_STAMP"],
		160, 160,
		'style="position: absolute; left: 40pt; "'
	); ?></div>
	<? } ?>

	<div style="position: relative">
		<table class="sign">
			<? if ($params["SELLER_COMPANY_DIRECTOR_POSITION"]) { ?>
			<tr>
				<td style="width: 150pt; "><?=Loc::getMessage('SELLER_COMPANY_DIRECTOR_POSITION')?></td>
				<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
					<? if (!$blank) { ?>
					<?=CFile::ShowImage($params["SELLER_COMPANY_DIR_SIGN"], 200, 50); ?>
					<? } ?>
				</td>
				<td>
					<? if ($params["SELLER_COMPANY_DIRECTOR_NAME"]) { ?>
					(<?=$params["SELLER_COMPANY_DIRECTOR_NAME"]; ?>)
					<? } ?>
				</td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<? } ?>
			<? if ($params["SELLER_COMPANY_ACCOUNTANT_POSITION"]) { ?>
			<tr>
				<td style="width: 150pt; "><?=Loc::getMessage('SELLER_COMPANY_ACCOUNTANT_POSITION')?></td>
				<td style="width: 160pt; border: 1pt solid #000000; border-width: 0pt 0pt 1pt 0pt; text-align: center; ">
					<? if (!$blank) { ?>
					<?=CFile::ShowImage($params["SELLER_COMPANY_ACC_SIGN"], 200, 50); ?>
					<? } ?>
				</td>
				<td>
					<? if ($params["SELLER_COMPANY_ACCOUNTANT_NAME"]) { ?>
					(<?=$params["SELLER_COMPANY_ACCOUNTANT_NAME"]; ?>)
					<? } ?>
				</td>
			</tr>
			<? } ?>
		</table>
	</div>
<?endif;?>

</div>

</body>
</html>