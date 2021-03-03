<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();
?>
<?
use Bitrix\Main\Localization\Loc; 
Loc::loadMessages(__FILE__); 
?>
<?/*
<pre><?print_r($arResult);?></pre>*/?>
<script type="text/javascript">
	<?
	$jsShowHideMessages = array(
		"show" => Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_DETAIL'),
		"hide" => Loc::getMessage('INOBITEC.ORDERS.LIST.ROLL_UP_ORDER')
	);
	?>
	var $jsShowHideMessages = <?=json_encode($jsShowHideMessages);?>;
</script>
<div class="keys">
    <h2><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEYS');?></h2>
    <div class="keys-main">
    	<?foreach ($arResult["ORDERS_LIST"] as $arOrderInfo): ?>
    		<?
    		$isPaid = ($arOrderInfo["PAYED"] == "Y");
    		?>
	        <div class="keys-main__order">
	            <p class="keys-main__order-number"><?=Loc::getMessage(
	            	'INOBITEC.ORDERS.LIST.ORDER_NUM', 
	            	array('#ORDER_ID#' => $arOrderInfo['ID'])
	            );?>
	            </p>
	            <div class="keys-main__order-container">
	                <div class="keys-main__order-container-prise">
	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_SUM');?></p>
	                    <p><?=SaleFormatCurrency($arOrderInfo["PRICE"], 'RUB', true);?> &#8381;</p>
	                </div>
	                <div class="keys-main__order-container-status">
	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_STATUS');?></p>
	                    <?if ($isPaid): ?>
	                    	<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PAID');?></p>
	                    <?else:?>
	                    	<p class="text-error"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.WAITING_PAYMETNT');?></p>
	                    <?endif;?>
	                </div>
	                <div class="keys-main__order-container-pricePDF">
                        <?if ($arResult["PAYSYSTEMS"][$arOrderInfo["PAY_SYSTEM_ID"]]["CODE"] == "pdf_invoice"): ?>
    	                    <button class="btn-red-disable" onclick="window.location.href='/cart/payment/?ORDER_ID=<?=$arOrderInfo['ID'];?>&pdf=1&DOWNLOAD=Y';"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.DOWNLOAD_PDF');?></button>
    	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.INVOICE_VALID');?></p>
                        <?elseif ($arResult["PAYSYSTEMS"][$arOrderInfo["PAY_SYSTEM_ID"]]["CODE"] == "yandex"):?>
                            <button class="btn-red-disable"  onclick="javascript:;">Яндекс-касса</button>
                        <?endif;?>
	                </div>
	                <div class="keys-main__order-container-additionalText">
	                    <a href="#"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.OFFER_AGREEMENT');?></a>
	                    <a href="#"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.OFFER_SCANS');?></a>
	                </div>
	            </div>
	        </div>
	        <div class="keys-main__description close">
	            <p class="keys-main__description-show">
	            	<span><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ROLL_UP_ORDER');?></span>
	            	<img src="/img/arrow-up.png">
	        	</p>
                <?$isFirstInOrder = true;?>
                <?foreach ($arOrderInfo["DEVIDED"] as $keyArr => $arItems): ?>
                    <?foreach ($arItems as $arItem): ?>
        	            <div class="keys-main__description-product">
        	                <div class="keys-main__description-product-name">
        	                    <p <?if ($isFirstInOrder): ?>class="text-showed"<?endif;?>><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
        	                    <p>
                                    <?$showName = '';?>
                                    <?if ($keyArr == 'server' || $keyArr == 'serverLic'): ?>
                                        <?$showName = Loc::getMessage('INOBITEC.ORDERS.LIST.SERVER');?>
                                    <?elseif ($keyArr == 'viewerLic'):?>
                                        <?$showName = Loc::getMessage('INOBITEC.ORDERS.LIST.UPDATES_SUBSCRIPTION');?>
                                    <?elseif (is_array($shortnamedata = $arResult['PRODUCTS_NAME_DATA'][$arItem['PRODUCT_ID']])): ?>
                                        <?$type = $shortnamedata['type'];?>
                                        <?
                                            if ($type == 'dicomviewer') {
                                                $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.VIEWER');
                                                $showName .= ' ' . $shortnamedata['short_name'];
                                            } elseif ($type == 'dicomserver') {
                                                $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.SERVER');
                                            }
                                        ?>
                                    <?else: ?>
                                        <?/*$showName = $arItem['PRODUCT_ID'] . ' ' . CIBlockElement::GetIBlockById($arItem['PRODUCT_ID']) . ' ' . $keyArr;*/?>
                                    <?endif;?>   
                                    <?echo $showName;?>
                                </p>
        	                </div>
        	                <div class="keys-main__description-product-key">
        	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEY');?></p>
        	                    <p><?=$arItem['LICENSE']['LICENSE_KEY'];?></p>
        	                </div>
        	                <div class="keys-main__description-product-data">
        	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PURCHASE_DATA');?></p>
        	                    <p><?=$arItem['LICENSE']['GENERATION_TIME'];?></p>
        	                </div>
        	                <div class="keys-main__description-product-active">
        	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ACTIVATION_DATA');?></p>
        	                    <p><?=$arItem['LICENSE']['ACTIVATION_TIME'];?></p>
        	                </div>
        	            </div>
                        <div class="keys-main__description-showCase">
                            <div class="keys-main__description-showCase-row">
                                <div class="keys-main__description-showCase-row-wrapper">
                                    <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                    <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                    <p class="keys-main__description-showCase-row-wrapper-connects"><?=$arItem['NAME'];?></p>
                                    <p class="keys-main__description-showCase-row-wrapper-data"></p>
                                    <p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency($arItem['PRICE'], 'RUB', true);?> <span>&#8381;</span></p>
                                </div>

                                <!--<div class="keys-main__description-showCase-row-wrapper">
                                    <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                                    <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                                    <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                                    <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                                    <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                                </div>

                                <div class="keys-main__description-showCase-row-wrapper">
                                    <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                                    <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                                    <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                                    <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                                    <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                                </div>

                                <div class="keys-main__description-showCase-row-wrapper">
                                    <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                                    <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                                    <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                                    <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                                    <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                                </div>-->
                            </div>
                        </div>
                        <?$isFirstInOrder = false;?>
                    <?endforeach;?>

                    <?if ($arItem["modules"]): ?>
        	            
                    <?endif;?>
                <?endforeach;?>
	            <!--<div class="keys-main__description-product mobile-border">
	                <div class="keys-main__description-product-name">
	                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
	                    <p>ПРОСМОТРЩИК</p>
	                </div>
	                <div class="keys-main__description-product-key">
	                    <p>Лицензионный ключ</p>
	                    <p>1078 6507 1670 3230 6327</p>
	                </div>
	                <div class="keys-main__description-product-data">
	                    <p>дата покупки</p>
	                    <p>14.04.2019 11:25</p>
	                </div>
	                <div class="keys-main__description-product-active">
	                    <p>дата активации</p>
	                    <p>14.04.2019 11:25</p>
	                </div>
	            </div>
	            <div class="keys-main__description-showCase">
	                <div class="keys-main__description-showCase-row">
	                    <div class="keys-main__description-showCase-row-wrapper">
	                        <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
	                        <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
	                        <p class="keys-main__description-showCase-row-wrapper-connects">Редакция Pro</p>
	                        <p class="keys-main__description-showCase-row-wrapper-data"></p>
	                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
	                    </div>

	                    <div class="keys-main__description-showCase-row-wrapper">
	                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
	                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
	                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
	                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
	                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
	                    </div>

	                    <div class="keys-main__description-showCase-row-wrapper">
	                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
	                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
	                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
	                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
	                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
	                    </div>

	                    <div class="keys-main__description-showCase-row-wrapper">
	                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
	                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
	                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
	                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
	                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
	                    </div>
	                </div>
	            </div>-->
	        </div>
    	<?endforeach;?>
        <!--<div class="keys-main__order">
            <p class="keys-main__order-number">Заказ №12345</p>
            <div class="keys-main__order-container">
                <div class="keys-main__order-container-prise">
                    <p>сумма покупки</p>
                    <p>123 456 &#8381;</p>
                </div>
                <div class="keys-main__order-container-status">
                    <p>статус заказа</p>
                    <p class="text-error">ОЖИДАЕТ ОПЛАТЫ</p>
                </div>
                <div class="keys-main__order-container-pricePDF">
                    <button>перейти к оплате</button>
                    <p>действителен 5 банковских дней</p>
                </div>
                <div class="keys-main__order-container-additionalText">
                    <a href="#">лицензионный договор-оферты</a>
                </div>
            </div>
        </div>
        <div class="keys-main__description close last-keys-padding">
            <p class="keys-main__description-show"><span>свернуть заказ</span><img src="img/arrow-up.png"></p>
            <div class="keys-main__description-product not-paid">
                <div class="keys-main__description-product-name">
                    <p class="text-showed"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
                    <p>СЕРВЕР</p>
                </div>
                <div class="keys-main__description-product-key">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-data">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-active">
                    <p></p>
                    <p></p>
                </div>
            </div>
            <div class="keys-main__description-showCase">
                <div class="keys-main__description-showCase-row">
                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Подключений: <span>18</span></p>
                        <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                        <p class="keys-main__description-showCase-row-wrapper-price">180 000 <span>&#8381;</span></p>
                    </div>

                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Подписка на обновления</p>
                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                        <p class="keys-main__description-showCase-row-wrapper-price">162 000 <span>&#8381;</span></p>
                    </div>
                </div>
            </div>

            <div class="keys-main__description-product not-paid">
                <div class="keys-main__description-product-name">
                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
                    <p>ПРОСМОТРЩИК</p>
                </div>
                <div class="keys-main__description-product-key">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-data">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-active">
                    <p></p>
                    <p></p>
                </div>
            </div>
            <div class="keys-main__description-showCase">
                <div class="keys-main__description-showCase-row">
                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Редакция Pro</p>
                        <p class="keys-main__description-showCase-row-wrapper-data"></p>
                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                    </div>

                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                    </div>

                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                    </div>

                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state mobile-text">ДЕЙСТВИТЕЛЕН</p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText mobile-text">стоимость</p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                        <p class="keys-main__description-showCase-row-wrapper-data">ДО 12.04.2024</p>
                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                    </div>
                </div>
            </div>

            <div class="keys-main__description-product not-paid">
                <div class="keys-main__description-product-name">
                    <p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
                    <p>ПРОСМОТРЩИК LITE</p>
                </div>
                <div class="keys-main__description-product-key">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-data">
                    <p></p>
                    <p></p>
                </div>
                <div class="keys-main__description-product-active">
                    <p></p>
                    <p></p>
                </div>
            </div>
            <div class="keys-main__description-showCase">
                <div class="keys-main__description-showCase-row">
                    <div class="keys-main__description-showCase-row-wrapper">
                        <p class="keys-main__description-showCase-row-wrapper-state"></p>
                        <p class="keys-main__description-showCase-row-wrapper-priceText"></p>
                        <p class="keys-main__description-showCase-row-wrapper-connects">Редакция Lite</p>
                        <p class="keys-main__description-showCase-row-wrapper-data"></p>
                        <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>