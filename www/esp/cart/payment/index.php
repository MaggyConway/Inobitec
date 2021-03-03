<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
// if($USER->GetID()){
//   $rsUser = CUser::GetByID($USER->GetID());

//   $arUser = $rsUser->Fetch();
//   if($arUser["LID"] != SITE_ID){
//     if(SITE_ID == 's1')
//       LocalRedirect("/eng/cart/payment/?".$_SERVER['QUERY_STRING']);
//     else
//       LocalRedirect("/cart/payment/?".$_SERVER['QUERY_STRING']);
//   }
// }
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



