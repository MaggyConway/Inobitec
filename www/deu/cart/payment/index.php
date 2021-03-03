<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->SetTitle("Оплата заказа");
/*
use \Bitrix\Sale;
$orderObj = Sale\Order::load($_REQUEST['ORDER_ID']);
$paymentCollection = $orderObj->getPaymentCollection();
$payment = $paymentCollection[0];
$service = Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
$context = \Bitrix\Main\Application::getInstance()->getContext();
$service->initiatePay($payment, $context->getRequest());
*/
$APPLICATION->IncludeComponent(
   "inobitec:sale.order.payment",
   "",
   Array(
   )
);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");



