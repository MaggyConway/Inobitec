<?
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('currency')){
	return false;
}

use Bitrix\Main\Localization\Loc; 
Loc::loadMessages(__FILE__); 

$currency = '';
if(SITE_ID == 's1'){
    $currency = 'RUB';
}elseif(SITE_ID == 's2'){
    $currency = 'USD';
}else{
    $currency = 'EUR';
}
?>
<script type="text/javascript">
	<?
	$jsShowHideMessages = array(
		"show" => Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_DETAIL'),
		"hide" => Loc::getMessage('INOBITEC.ORDERS.LIST.ROLL_UP_ORDER')
	);
    $jsEarlyShowHideMessages = array(
        "show" => Loc::getMessage('INOBITEC.ORDERS.LIST.EARLY_LICENSE_DETAIL'),
        "hide" => Loc::getMessage('INOBITEC.ORDERS.LIST.EARLY_LICENSE_ROLL_UP')
    );
	?>
	var $jsShowHideMessages = <?=json_encode($jsShowHideMessages);?>;
    var $jsEarlyShowHideMessages = <?=json_encode($jsEarlyShowHideMessages);?>;
</script>
<div class="keys">
    <h2><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEYS');?></h2>
	<div class="keys-main">
        <?php
        foreach ($arResult["ORDERS_LIST"] as $arOrderInfo):
            $isPaid = ($arOrderInfo["PAYED"] == "Y");
            $isCanceled = ($arOrderInfo["CANCELED"] == "Y");
            ?>
			<div class="keys-main__order">
				<p class="keys-main__order-number"><?=Loc::getMessage(
                        'INOBITEC.ORDERS.LIST.ORDER_NUM',
                        ['#ORDER_ID#' => $arOrderInfo['ID']]
                    );?></p>
				<div class="keys-main__order-container">
					<div class="keys-main__order-container-prise">
						<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_SUM');?></p>
						<p><?=SaleFormatCurrency(
                                CCurrencyRates::ConvertCurrency(
                                    $arOrderInfo["PRICE"],
                                    $arOrderInfo['CURRENCY'],
                                    $currency
                                ),
                                $currency
                            );?></p>
					</div>
					<div class="keys-main__order-container-status">
						<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_STATUS');?></p>
                        <?php
                        if ($isCanceled): ?>
							<p class="text-gray"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.CANCELED');?></p>
                        <?php
						elseif ($isPaid): ?>
							<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PAID');?></p>
                        <?php
                        else:?>
							<p class="text-error"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.WAITING_PAYMETNT');?></p>
                        <?php
                        endif;?>
					</div>
					<div class="keys-main__order-container-pricePDF">
                        <?php
                        if (!$isCanceled && !$isPaid):
                            if ($arResult["PAYSYSTEMS"][$arOrderInfo["PAY_SYSTEM_ID"]]["CODE"] == "pdf_invoice"): ?>
                                <?php
                                if ($isPaid): ?>
									<a href="<?=SITE_DIR?>personal/generateInvoce.php?ORDER_ID=<?=$arOrderInfo['ID'];?>&pdf=1&DOWNLOAD=Y';"
									   target="_blank" style="display: none;" id="invoice_<?=$arOrderInfo['ID'];?>"></a>
                                <?
                                else: ?>
									<a href="<?=SITE_DIR?>cart/payment/?ORDER_ID=<?=$arOrderInfo['ID'];?>&pdf=1&DOWNLOAD=Y"
									   target="_blank" style="display: none;" id="invoice_<?=$arOrderInfo['ID'];?>"></a>
                                <?
                                endif; ?>
								<button class="btn-blue" onclick="document.getElementById('invoice_<?=$arOrderInfo['ID'];?>').click();"><?=Loc::getMessage(
                                        'INOBITEC.ORDERS.LIST.DOWNLOAD_PDF'
                                    );?></button>
								<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.INVOICE_VALID');?></p>
                            <?php
							elseif ($arResult["PAYSYSTEMS"][$arOrderInfo["PAY_SYSTEM_ID"]]["CODE"] == "yandex" || $arResult["PAYSYSTEMS"][$arOrderInfo["PAY_SYSTEM_ID"]]["CODE"] == "yandex_new"):
                                if (!$isPaid): ?>
									<a href="<?=SITE_DIR?>cart/payment/?ORDER_ID=<?=$arOrderInfo['ID'];?>" target="_blank" style="display: none;" id="gopay_<?=$arOrderInfo['ID'];?>"></a>
									<button class="btn-blue" onclick="document.getElementById('gopay_<?=$arOrderInfo['ID'];?>').click();"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.YKASSA'
                                        );?></button>
                                <?php
                                endif;
                            endif;
                        endif; ?>
					</div>
					<div class="keys-main__order-container-additionalText">
                        <?php
                        if (!$isCanceled):?>
							<a href="javascript:void(0);" onclick="Popup('filesOrder<?=$arOrderInfo["ID"];?>'); return false"><?=Loc::getMessage(
                                    'INOBITEC.ORDERS.LIST.DOCUMENTS'
                                );?></a>
                        <?php
                        endif; ?>
					</div>
				</div>
			</div>
			<div class="keys-main__description">
				<p class="keys-main__description-show">
					<span class="order" data-hide-text="<?=Loc::getMessage('INOBITEC.ORDERS.LIST.ROLL_UP_ORDER');?>"
						  data-show-text="<?=Loc::getMessage(
                              'INOBITEC.ORDERS.LIST.ORDER_DETAIL'
                          );?>"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ORDER_DETAIL');?></span>
					<img src="/img/arrow-up.svg">
				</p>
                <?php
                $isFirstInOrder = true;
                $serverInOrder = [];
                //Находим блок с последним товаром
                foreach ($arOrderInfo["DEVIDED"] as $K => $a) {
                    if (count($a) >= 1) {
                        $lastFilledKey = $K;
                    }
                }
                foreach ($arOrderInfo["DEVIDED"] as $keyArr => $arItems):
                    if (($keyArr == 'server' || $keyArr == 'serverLic') && count($arItems) >= 1) {
                        $serverInOrder[$keyArr] = reset($arItems);
                        unset($arOrderInfo["DEVIDED"][$keyArr]);
                        continue;
                    }
                    $cntItems = count($arItems);
                    $counter = 0;
                    foreach ($arItems as $arItem):
                        $old_lic = getOrderItemPropByCode($arItem["PROPS"], "LICENSE_ID");
                        for ($i = 0; $i < $arItem["QUANTITY"]; $i++):
                            $counter++; ?>
							<div <?php if (isset($arItem['LICENSES_API']) && isset($arItem['LICENSES_API'][$i])): ?>id="<?=$arItem['LICENSES_API'][$i]['ID']?>" <?
							elseif ($old_lic && isset($arResult["RENEWAL_LICENSES"][$old_lic])): ?> id="<?=$arResult["RENEWAL_LICENSES"][$old_lic]['ID']?>" <?
                            endif; ?> class="keys-main__description-product
                            <?php
                            if ($counter == $cntItems && $keyArr == $lastFilledKey && !count(
                                    $serverInOrder
                                )): ?>mobile-border<?
                            endif; ?> <?
                            if (/*!$isPaid*/true): ?>not-paid<?
                            endif; ?>">
								<div class="keys-main__description-product-name">
									<p <?
                                       if ($isFirstInOrder): ?>class="text-showed"<?
                                    endif; ?>><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
									<p class="product-name">
                                        <?
                                        $showName = ''; ?>
                                        <?
                                        if ($keyArr == 'viewerLic'): ?>
                                            <?
                                            $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.UPDATES_SUBSCRIPTION'); ?>
                                            <?
                                            $viewerLicName = Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.VIEWERLIC_NAME_' . $arOrderInfo["VIEWERLIC_TYPE"]
                                            ); ?>
                                        <?
										elseif (is_array(
                                            $shortnamedata = $arResult['PRODUCTS_NAME_DATA'][$arItem['PRODUCT_ID']]
                                        )): ?>
                                            <?
                                            $type = $shortnamedata['type']; ?>
                                            <?
                                            if ($type == 'dicomviewer') {
                                                $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.VIEWER');
                                                $showName .= ' ' . (LANGUAGE_ID == 'en' ? $shortnamedata['superShortName'] : $shortnamedata['superShortName']);
                                            }elseif ($type == 'dicomserver') {
                                                $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.SERVER');
                                            }
                                            ?>
                                        <?
                                        else: ?>
                                            <?
                                            $showName = $arItem['NAME']; ?>
                                        <?
                                        endif; ?>
                                        <?
                                        echo $showName; ?><?
                                        //=$arItem['ID'];?>
										<img src="/img/arrow-up-blue.svg">
									</p>

								</div>
								<div class="keys-main__description-product-key">
                                    <?
                                    if ($arItem['LICENSES_API'][$i]['LICENSE_KEY']): ?>
										<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEY');?></p>
										<p><?=$arItem['LICENSES_API'][$i]['LICENSE_KEY'];?></p>
                                    <?
									elseif ($old_lic && isset($arResult["RENEWAL_LICENSES"][$old_lic])): ?>
										<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEY');?></p>
										<p><?=$arResult["RENEWAL_LICENSES"][$old_lic]['LICENSE_KEY'];?></p>
                                    <?
                                    else: ?>
										<p></p>
										<p></p>
                                    <?
                                    endif; ?>
								</div>
								<div class="keys-main__description-product-data">
                                    <?
                                    if ($arItem['LICENSES_API'][$i]['GENERATION_TIME']) {
                                        $data = $arItem['LICENSES_API'][$i]['GENERATION_TIME'];
                                        $data_arr = explode(" ", $data);
                                        ?>
										<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PURCHASE_DATA');?></p>
										<p><?=$data_arr[0];?></p>
                                    <?
                                    }elseif ($old_lic && isset($arResult["RENEWAL_LICENSES"][$old_lic])) {
                                        $data = $arResult["RENEWAL_LICENSES"][$old_lic]['GENERATION_TIME'];
                                        $data_arr = explode(" ", $data);
                                        ?>
										<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PURCHASE_DATA');?></p>
										<p><?=$data_arr[0];?></p>
                                    <?
                                    }else { ?><p></p><p></p>
                                    <?
                                    } ?>
								</div>
								<div class="keys-main__description-product-active">
									<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ACTIVATION_DATA');?></p>
                                    <?
                                    if ($arItem['LICENSES_API'][$i]['ACTIVATION_TIME']) {
                                        $data = $arItem['LICENSES_API'][$i]['ACTIVATION_TIME'];
                                        $data_arr = explode(" ", $data);
                                        ?>
										<p><?=$data_arr[0];?></p>
                                    <?
                                    }elseif ($old_lic && isset($arResult["RENEWAL_LICENSES"][$old_lic])) {
                                        $data = $arResult["RENEWAL_LICENSES"][$old_lic]['ACTIVATION_TIME'];
                                        $data_arr = explode(" ", $data);
                                        ?>
										<p><?=$data_arr[0];?></p>
                                    <?
                                    }else { ?>
										<p></p>
                                    <?
                                    } ?>


								</div>
							</div>
							<div class="keys-main__description-showCase">
								<div class="keys-main__description-showCase-row">

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
										<p class="keys-main__description-showCase-row-wrapper-connects">
                                            <?

                                            if ($old_lic) {
                                                $years = getOrderItemPropByCode($arItem['PROPS'], 'YEARS');
                                                $conns = getOrderItemPropByCode($arItem['PROPS'], 'CONNECTIONS');
                                                $titleYearsName = ($years === '1') ? 'INOBITEC.ORDERS.LIST.YEAR' : 'INOBITEC.ORDERS.LIST.YEARS';

                                                ?>
                                                <? if ($years):?>
                                                    <?=Loc::getMessage('INOBITEC.ORDERS.LIST.RENEWAL');?> <a
														class="js-showOldLic" onclick="return false;"
														data-href="<?=$old_lic?>"><?=Loc::getMessage(
                                                            'INOBITEC.ORDERS.LIST.OLDLIC'
                                                        );?></a>, <?=$years?> <?=Loc::getMessage($titleYearsName);?>
                                                <?elseif ($conns):?>
                                                    <?=Loc::getMessage('INOBITEC.ORDERS.LIST.ADDITIONALCONNS');?> <a
														class="js-showOldLic" onclick="return false;"
														data-href="<?=$old_lic?>"><?=Loc::getMessage(
                                                            'INOBITEC.ORDERS.LIST.OLDLIC'
                                                        );?></a>: <span>+<?=$conns;?></span>
                                                <?endif; ?>
                                                <?
                                            }else {
                                                if ($keyArr == "viewerLic") {
                                                    echo $viewerLicName;
                                                }else {
                                                    if ($type == 'dicomviewer') {
                                                        $Name = '';
                                                        switch (SITE_ID) {
                                                            case 's1':
                                                                $Name = $shortnamedata['short_name'];
                                                                break;
                                                            case 's2':
                                                                $Name = $shortnamedata['short_english_name'];
                                                                break;
                                                            case 's3':
                                                                $Name = $shortnamedata['short_french_name'];
                                                                break;
                                                            case 's4':
                                                                $Name = $shortnamedata['short_germany_name'];
                                                                break;
                                                            case 's5':
                                                                $Name = $shortnamedata['short_spanish_name'];
                                                                break;
                                                        }
                                                        echo $Name;
                                                    }elseif ($type == 'dicomserver') {
                                                        $Name = '';
                                                        switch (SITE_ID) {
                                                            case 's1':
                                                                $Name = $shortnamedata['russian_name'];
                                                                break;
                                                            case 's2':
                                                                $Name = $shortnamedata['english_name'];
                                                                break;
                                                            case 's3':
                                                                $Name = $shortnamedata['french_name'];
                                                                break;
                                                            case 's4':
                                                                $Name = $shortnamedata['germany_name'];
                                                                break;
                                                            case 's5':
                                                                $Name = $shortnamedata['spanish_name'];
                                                                break;
                                                        }
                                                        echo $Name;
                                                    }else {
                                                        echo $arItem['NAME'];
                                                    }
                                                }
                                            }
                                            ?>
										</p>
									</div>
									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
										<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.VALID'
                                            );?></p>
                                        <?
                                        if (isset($arResult["RENEWAL_LICENSES"][$arItem['ID']])): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.LICWASUPDATE'
                                                );?>, <a class="js-showOldLic" onclick="return false;"
														 data-href="<?=$arResult["RENEWAL_LICENSES"][$arItem['ID']]['ID']?>"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.LOOK'
                                                    );?></a></p>
                                        <?
										elseif ($arItem['LICENSES_API'][$i]['SUPPORT_END_TIME']): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UNTIL'
                                                );?><?=$arItem['LICENSES_API'][$i]['SUPPORT_END_TIME'];?></p>
                                        <?
										elseif ($old_lic && isset($arResult["RENEWAL_LICENSES"][$old_lic]) && $arResult["RENEWAL_LICENSES"][$old_lic]['SUPPORT_END_TIME']): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UNTIL'
                                                );?><?=$arResult["RENEWAL_LICENSES"][$old_lic]['SUPPORT_END_TIME'];?></p>
                                        <?
                                        else: ?>
											<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                        <?
                                        endif; ?>

									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
										<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.PRICE'
                                            );?></p>
										<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                                CCurrencyRates::ConvertCurrency(
                                                    $arItem["PRICE"],
                                                    $arOrderInfo['CURRENCY'],
                                                    $currency
                                                ),
                                                $currency
                                            );?></p>
									</div>
								</div>
                                <?
                                if ($old_lic) {
                                    $conns = getOrderItemPropByCode($arItem['PROPS'], 'CONNECTIONS');
                                    if ($conns) {
                                        ?>
										<div class="keys-main__description-showCase-row">

											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
												<p class="keys-main__description-showCase-row-wrapper-connects">
                                                    <?=Loc::getMessage('INOBITEC.ORDERS.LIST.TOTALCONNS');?>:
													<span><?=$arResult["RENEWAL_LICENSES"][$old_lic]["SERVER_CONNECTIONS"]?></span>
												</p>
											</div>
											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
												<p class="keys-main__description-showCase-row-wrapper-state"></p>
												<p class="keys-main__description-showCase-row-wrapper-data"></p>
											</div>

											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer  no-data">
												<p class="keys-main__description-showCase-row-wrapper-priceText"></p>
												<p class="keys-main__description-showCase-row-wrapper-price"></p>
											</div>
										</div>
                                        <?
                                    }
                                }
                                if ($arItem["modules"]): ?>
                                    <?php
                                    foreach ($arItem["modules"] as $module):
                                        $moduleName = (SITE_ID == 's2') ? getOrderItemPropByCode(
                                            $module['PROPS'],
                                            'NAME_EN'
                                        ) : $module['NAME'] ?>
										<div class="keys-main__description-showCase-row">
											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
												<p class="keys-main__description-showCase-row-wrapper-connects"><?=$moduleName;?></p>
											</div>
											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
												<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.VALID'
                                                    );?></p>
                                                <?
                                                if ($arItem['LICENSES_API'][$i]['SUPPORT_END_TIME'] && !(isset($arResult["RENEWAL_LICENSES"][$arItem['ID']]))): ?>
													<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                            'INOBITEC.ORDERS.LIST.UNTIL'
                                                        );?><?=$arItem['LICENSES_API'][$i]['SUPPORT_END_TIME'];?></p>
                                                <?
                                                else: ?>
													<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                                <?
                                                endif; ?>
											</div>
											<div
												class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer no-data">
												<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.PRICE'
                                                    );?></p>
												<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                                        CCurrencyRates::ConvertCurrency(
                                                            $module['PRICE'],
                                                            $arOrderInfo['CURRENCY'],
                                                            $currency
                                                        ),
                                                        $currency
                                                    );?></p>
											</div>
										</div>
                                    <?
                                    endforeach;
                                endif;
                                if (isset($arItem['license']) && count($arItem['license']) > 0): ?>
                                    <?
                                    $lic = reset($arItem['license']);
                                    $years = getOrderItemPropByCode($lic['PROPS'], 'YEARS');
                                    $titleName = ($years === '1') ? 'INOBITEC.ORDERS.LIST.YEAR' : 'INOBITEC.ORDERS.LIST.YEARS';
                                    ?>
									<div class="keys-main__description-showCase-row">

										<div
											class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
											<p class="keys-main__description-showCase-row-wrapper-connects"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UPDATES_SUBSCRIPTION'
                                                );?>, <?=$years?> <?=Loc::getMessage($titleName);?></p>
										</div>

										<div
											class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
											<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.VALID'
                                                );?></p>
                                            <?
                                            if ($arItem['LICENSES_API'][$i]['SUPPORT_END_TIME'] && !(isset($arResult["RENEWAL_LICENSES"][$arItem['ID']]))): ?>
												<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.UNTIL'
                                                    );?><?=$arItem['LICENSES_API'][$i]['SUPPORT_END_TIME'];?></p>
                                            <?
                                            else: ?>
												<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                            <?
                                            endif; ?>
										</div>

										<div
											class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer no-data">
											<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.PRICE'
                                                );?></p>
											<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                                    CCurrencyRates::ConvertCurrency(
                                                        $lic['PRICE'],
                                                        $arOrderInfo['CURRENCY'],
                                                        $currency
                                                    ),
                                                    $currency
                                                );?></p>
										</div>
									</div>
                                <?
                                endif; ?>
							</div>
                            <?
                            $isFirstInOrder = false;
                        endfor;
                    endforeach;
                endforeach;
                if (!empty($serverInOrder)): ?>

                    <?
                    if (isset($serverInOrder['server']['LICENSES_API']) && count(
                            $serverInOrder['server']['LICENSES_API']
                        ) > 0) {
                        $serverLic = $serverInOrder['server']['LICENSES_API'][0];
                    }else {
                        $serverLic = false;
                    }
                    ?>
					<div <?
                         if ($serverLic): ?>id="<?=$serverLic['ID']?>" <?
                    endif; ?> class="keys-main__description-product <?
                    if (/*!$isPaid*/
                    true): ?>not-paid<?
                    endif; ?> mobile-border">
						<div class="keys-main__description-product-name">
							<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
							<p class="product-name"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.SERVER');?> <img
									src="/img/arrow-up-blue.svg"></p>
						</div>
						<div class="keys-main__description-product-key">
                            <?
                            if ($serverLic['LICENSE_KEY']): ?>
								<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEY');?></p>
								<p><?=$serverLic['LICENSE_KEY'];?></p>
                            <?
                            else: ?><p></p><p></p>
                            <?
                            endif; ?>
						</div>
						<div class="keys-main__description-product-data">
                            <?
                            if ($serverLic['GENERATION_TIME']) {
                                $data = $serverLic['GENERATION_TIME'];
                                $data_arr = explode(" ", $data);
                                ?>
								<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PURCHASE_DATA');?></p>
								<p><?=$data_arr[0];?></p>
                            <?
                            }else { ?><p></p><p></p>
                            <?
                            } ?>
						</div>
						<div class="keys-main__description-product-active">
                            <?
                            $data = $serverLic['ACTIVATION_TIME'];
                            $data_arr = explode(" ", $data);
                            ?>
							<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ACTIVATION_DATA');?></p>
							<p><?=$data_arr[0];?></p>


						</div>
					</div>
					<!-- класс для неоплаченного заказа not-paid -->
					<div class="keys-main__description-showCase">

                        <?
                        if ($serverInOrder['server']): ?>
                            <?
                            $lic = reset($serverInOrder['server']['LICENSES_API']); ?>

							<div class="keys-main__description-showCase-row">

								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
									<p class="keys-main__description-showCase-row-wrapper-connects"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.CONNECTIONS'
                                        );?><span><?=$lic["SERVER_CONNECTIONS"]?></span></p>
                                    <?
                                    /*if (LANGUAGE_ID == 'en') {
                                        echo $arResult['PRODUCTS_NAME_DATA'][$serverInOrder['server']['PRODUCT_ID']]['english_name']?:$serverInOrder['server']['NAME'];
                                    } else {
                                        echo $serverInOrder['server']['NAME'];
                                    }*/
                                    ?> <!--/p-->
								</div>

								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
									<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.VALID'
                                        );?></p>


                                    <?
                                    if (isset($arResult["RENEWAL_LICENSES"][$serverLic['ID']])): ?>
										<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.LICWASUPDATE'
                                            );?>, <a class="js-showOldLic" onclick="return false;"
													 data-href="<?=$arResult["RENEWAL_LICENSES"][$serverLic['ID']]['ID']?>"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.LOOK'
                                                );?></a></p>
                                    <?
									elseif ($serverLic['SUPPORT_END_TIME']): ?>
										<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.UNTIL'
                                            );?><?=$serverLic['SUPPORT_END_TIME'];?></p>
                                    <?
                                    else: ?>
										<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                    <?
                                    endif; ?>
								</div>

								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
									<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.PRICE'
                                        );?></p>
									<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                            CCurrencyRates::ConvertCurrency(
                                                $serverInOrder['server']['PRICE'],
                                                $arOrderInfo['CURRENCY'],
                                                $currency
                                            ),
                                            $currency
                                        );?></p>
								</div>
							</div>

                            <?
                            if ($serverInOrder['serverLic']): ?>
                                <?

                                $lic = reset($serverInOrder['server']['LICENSES_API']);
                                $years = $lic['SUPPORT_YEARS'];
                                $titleName = ($years == '1') ? 'INOBITEC.ORDERS.LIST.YEAR' : 'INOBITEC.ORDERS.LIST.YEAR';
                                $productName = (LANGUAGE_ID == 'en') ? $arResult['PRODUCTS_NAME_DATA'][$serverInOrder['serverLic']['PRODUCT_ID']]['english_name'] ?: $serverInOrder['serverLic']['NAME'] : $serverInOrder['serverLic']['NAME'];
                                ?>
								<div class="keys-main__description-showCase-row">

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
										<p class="keys-main__description-showCase-row-wrapper-connects"><?=$productName?>
											, <?=--$years?> <?=Loc::getMessage($titleName);?></p>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
										<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.VALID'
                                            );?></p>
                                        <?
                                        if ($lic['SUPPORT_END_TIME']): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UNTIL'
                                                );?><?=$lic['SUPPORT_END_TIME'];?></p>
                                        <?
                                        else: ?>
											<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                        <?
                                        endif; ?>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer  no-data">
										<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.PRICE'
                                            );?></p>
										<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                                CCurrencyRates::ConvertCurrency(
                                                    $serverInOrder['serverLic']['PRICE'],
                                                    $arOrderInfo['CURRENCY'],
                                                    $currency
                                                ),
                                                $currency
                                            );?></p>
									</div>
								</div>

                            <?
							elseif (isset($serverInOrder['server']['license']) && count(
                                    $serverInOrder['server']['license']
                                ) > 0): ?>
                                <?
                                $lic = reset($serverInOrder['server']['license']);
                                $years = getOrderItemPropByCode($lic['PROPS'], 'YEARS');
                                $titleName = ($years == '1') ? 'INOBITEC.ORDERS.LIST.YEAR' : 'INOBITEC.ORDERS.LIST.YEAR';
                                ?>
								<div class="keys-main__description-showCase-row">

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
										<p class="keys-main__description-showCase-row-wrapper-connects">
                                            <?=Loc::getMessage('INOBITEC.ORDERS.LIST.UPDATES_SUBSCRIPTION');?>
											, <?=$years?> <?=Loc::getMessage($titleName);?>  </p>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
										<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.VALID'
                                            );?></p>


                                        <?
                                        if (isset($arResult["RENEWAL_LICENSES"][$arLicense['ID']])): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.LICWASUPDATE'
                                                );?>, <a class="js-showOldLic" onclick="return false;"
														 data-href="<?=$arResult["RENEWAL_LICENSES"][$arLicense['ID']]['ID']?>"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.LOOK'
                                                    );?></a></p>
                                        <?
										elseif ($arLicense['SUPPORT_END_TIME']): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UNTIL'
                                                );?><?=$arLicense['SUPPORT_END_TIME'];?></p>
                                        <?
                                        else: ?>
											<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                        <?
                                        endif; ?>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer no-data">
										<p class="keys-main__description-showCase-row-wrapper-priceText"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.PRICE'
                                            );?></p>
										<p class="keys-main__description-showCase-row-wrapper-price"><?=SaleFormatCurrency(
                                                CCurrencyRates::ConvertCurrency(
                                                    $lic['PRICE'],
                                                    $arOrderInfo['CURRENCY'],
                                                    $currency
                                                ),
                                                $currency
                                            );?></p>
									</div>
								</div>
                            <?
                            endif; ?>
                            <?
                            if ($serverLic['LICENSE_KEY'] && $serverLic["SUPPORTED_PO"] == 1): ?>
								<div class="keys-main__description-showCase-row">
									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
										<p class="keys-main__description-showCase-row-wrapper-connects">
                                            <?
                                            if ($serverLic["NEXT_LICENSE_ID"]): ?>
                                                <?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICWASUPDATE');?>, <a
													class="js-showOldLic" onclick="return false;"
													data-href="<?=$serverLic["NEXT_LICENSE_ID"]?>"><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.LOOK'
                                                    );?></a>
                                            <?
                                            else: ?>
												<a href='/include/getLicenseFile.php?LIC_ID=<?=$serverLic['ID']?>'><?=Loc::getMessage(
                                                        'INOBITEC.ORDERS.LIST.DOAWNLOADFILE'
                                                    );?></a>
                                            <?
                                            endif; ?>
										</p>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
										<p class="keys-main__description-showCase-row-wrapper-state"></p>
										<p class="keys-main__description-showCase-row-wrapper-data"></p>

									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
										<p class="keys-main__description-showCase-row-wrapper-priceText"></p>
										<p class="keys-main__description-showCase-row-wrapper-price"></p>
									</div>
								</div>
                            <?
                            endif; ?>

                        <?
                        endif; ?>


					</div>
                <?
                endif; ?>
			</div>
        <?php
        endforeach;
        if (count($arResult['LICENSES_WITHOUT_ORDERS']) > 0): ?>
			<div class="keys-main__order">
				<p class="keys-main__order-number"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.EARLY_LICENSES');?></p>
			</div>
			<div class="keys-main__description">
				<p class="keys-main__description-show">
					<span class="early-licenses"
						  data-hide-text="<?=Loc::getMessage('INOBITEC.ORDERS.LIST.ROLL_UP_ORDER');?>"
						  data-show-text="<?=Loc::getMessage(
                              'INOBITEC.ORDERS.LIST.ORDER_DETAIL'
                          );?>"><?=$jsEarlyShowHideMessages['show'];?></span>
					<img src="/img/arrow-up.png">
				</p>
                <?
                $license_early_num = 0; ?>
                <?
                foreach ($arResult['LICENSES_WITHOUT_ORDERS'] as $arLicense): ?>
                    <?
                    $product = $arResult['PRODUCTS_NAME_DATA'][$arLicense['GOOD_INFO']]; ?>
					<div <?
                         if (isset($arLicense['ID'])): ?>id="<?=$arLicense['ID']?>"<?
                    endif; ?> class="keys-main__description-product mobile-border">
						<div class="keys-main__description-product-name">
							<p <?
                               if ($license_early_num == 0): ?>class="text-showed"<?
                            endif; ?>><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PRODUCT');?></p>
							<p class="product-name">
                                <?
                                if ($product['type'] == 'dicomserver') {
                                    echo Loc::getMessage('INOBITEC.ORDERS.LIST.SERVER');
                                    $showNameFull = Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.CONNECTIONS'
                                        ) . '<span>' . $arLicense["SERVER_CONNECTIONS"] . '</span>';
                                }elseif ($product['type'] == 'dicomviewer') {
                                    $showName = Loc::getMessage('INOBITEC.ORDERS.LIST.VIEWER');
                                    $showName .= ' ' . (LANGUAGE_ID == 'en' ? $product['superShortName'] : $product['superShortName']);
                                    echo $showName;
                                    $showNameFull = (LANGUAGE_ID == 'en' ? $product['short_english_name'] : $product['short_name']);
                                }

                                ?>
								<img src="/img/arrow-up-blue.svg" class="">
							</p>

						</div>
						<div class="keys-main__description-product-key">
                            <?
                            if ($arLicense['LICENSE_KEY']): ?>
								<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE_KEY');?></p>
								<p><?=$arLicense['LICENSE_KEY'];?></p>
                            <?
                            else: ?><p></p><p></p>
                            <?
                            endif; ?>
						</div>
						<div class="keys-main__description-product-data">
                            <?
                            if ($arLicense['GENERATION_TIME']) {
                                $data = $arLicense['GENERATION_TIME'];
                                $data_arr = explode(" ", $data);
                                ?>
								<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.PURCHASE_DATA');?></p>
								<p><?=$data_arr[0];?></p>
                            <?
                            }else { ?><p></p><p></p>
                            <?
                            } ?>
						</div>
						<div class="keys-main__description-product-active">

                            <?
                            $data = $arLicense['ACTIVATION_TIME'];
                            $data_arr = explode(" ", $data);
                            ?>
							<p><?=Loc::getMessage('INOBITEC.ORDERS.LIST.ACTIVATION_DATA');?></p>
							<p><?=$data_arr[0];?></p>

						</div>
					</div>
					<div class="keys-main__description-showCase">
						<div class="keys-main__description-showCase-row">
							<div
								class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
								<p class="keys-main__description-showCase-row-wrapper-connects"><?=$showNameFull?></p>
							</div>

							<div
								class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
								<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                        'INOBITEC.ORDERS.LIST.VALID'
                                    );?></p>

                                <?
                                if (isset($arResult["RENEWAL_LICENSES"][$arLicense['ID']])): ?>
									<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.LICWASUPDATE'
                                        );?>, <a class="js-showOldLic" onclick="return false;"
												 data-href="<?=$arResult["RENEWAL_LICENSES"][$arLicense['ID']]['ID']?>"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.LOOK'
                                            );?></a></p>
                                <?
								elseif ($arLicense['SUPPORT_END_TIME']): ?>
									<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                            'INOBITEC.ORDERS.LIST.UNTIL'
                                        );?><?=$arLicense['SUPPORT_END_TIME'];?></p>
                                <?
                                else: ?>
									<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                <?
                                endif; ?>
							</div>

							<div
								class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
								<p class="keys-main__description-showCase-row-wrapper-priceText"></p>
								<p class="keys-main__description-showCase-row-wrapper-price"></p>
							</div>
						</div>

                        <?
                        if ($product['type'] == 'dicomserver' && $arLicense["SUPPORTED_PO"] == 1): ?>
							<div class="keys-main__description-showCase-row">
								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
									<p class="keys-main__description-showCase-row-wrapper-connects"><a
											href='/include/getLicenseFile.php?LIC_ID=<?=$arLicense['ID']?>'><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.DOAWNLOADFILE'
                                            );?></a></p>
								</div>

								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
									<p class="keys-main__description-showCase-row-wrapper-state"></p>
									<p class="keys-main__description-showCase-row-wrapper-data"></p>

								</div>

								<div
									class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
									<p class="keys-main__description-showCase-row-wrapper-priceText"></p>
									<p class="keys-main__description-showCase-row-wrapper-price"></p>
								</div>
							</div>
                        <?
                        endif; ?>

                        <?
                        if ($arLicense["API_SERVER_MODULES"]): ?>
                            <?
                            foreach ($arLicense["API_SERVER_MODULES"] as $module): ?>
								<div class="keys-main__description-showCase-row">

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
										<p class="keys-main__description-showCase-row-wrapper-connects"><?=(LANGUAGE_ID == 'en' ? $module['PROPERTY_NAME_EN_VALUE'] : $module['NAME']);?></p>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
										<p class="keys-main__description-showCase-row-wrapper-state"><?=Loc::getMessage(
                                                'INOBITEC.ORDERS.LIST.VALID'
                                            );?></p>


                                        <?
                                        if (!(isset($arResult["RENEWAL_LICENSES"][$arLicense['ID']]))): ?>
											<p class="keys-main__description-showCase-row-wrapper-data"><?=Loc::getMessage(
                                                    'INOBITEC.ORDERS.LIST.UNTIL'
                                                );?><?=$arLicense['SUPPORT_END_TIME'];?></p>
                                        <?
                                        else: ?>
											<p class="keys-main__description-showCase-row-wrapper-data"></p>
                                        <?
                                        endif; ?>
									</div>

									<div
										class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
										<p class="keys-main__description-showCase-row-wrapper-priceText"></p>
										<p class="keys-main__description-showCase-row-wrapper-price"></p>
									</div>
								</div>
                            <?
                            endforeach; ?>
                        <?
                        endif; ?>
					</div>
                    <?
                    $license_early_num++; ?>
                <?
                endforeach; ?>
			</div>
        <?php
        endif; ?>
	</div>
</div>
<?foreach ($arResult["FILES"] as $order_id => $filesList): ?>
    <div class="popup" id="filesOrder<?=$order_id;?>">
        <div class="popup-main writing">
            <div class="close">
                <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                    <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
                </svg>
            </div>
            <p class="authorization"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.DOCUMENTS');?></p>
              <div class="writing-doc">
                  <img src="/img/svg/writing.svg">
                  <a href="<?=SITE_DIR?>personal/generateInvoce.php?ORDER_ID=<?=$order_id?>" target="_blank"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.INVOCE');?></a>
              </div>
            <?if($arResult["ORDERS_LIST"][$order_id]["PAYED"] == "Y"):?>
              <div class="writing-doc">
                  <img src="/img/svg/writing.svg">
                  <a href="<?=SITE_DIR?>personal/license.php?ORDER_ID=<?=$order_id?>" target="_blank"><?=Loc::getMessage('INOBITEC.ORDERS.LIST.LICENSE');?></a>
              </div>
            <?endif;?>
            <?foreach ($filesList as $file): ?>
              <div class="writing-doc">
                <img src="/img/svg/writing.svg">
                <a href="<?=$file['FILE']['SRC'];?>" target="_blank"><?=$file['NAME'];?></a>
              </div>  
            <?endforeach;?>
        </div>
    </div>
<?endforeach;?>