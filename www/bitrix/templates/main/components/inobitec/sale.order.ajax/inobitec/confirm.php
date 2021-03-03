<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!empty($arResult["ORDER"]))
{
    if(empty($arResult["PAY_SYSTEM"])){
      ?>
        <div class="page_basket">
          <div class="page_basket_message">
              <div class="page_basket_message_img">
                  <img src="/verstka/img/svg/checkbox.svg" />
              </div>
              <div class="page_basket_message_title">
                 <?= GetMessage("CUSTOM_PAID_MAIN", Array("#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
              </div>
              <p><?= GetMessage("CUSTOM_PAID_THANKS", Array())?></p>
              <div class="buttons_box center">
                  <a href="<?=SITE_DIR?>personal/" class="btn_n big"><?= GetMessage("CUSTOM_PAID_PERSONAL", Array())?></a>
              </div>
          </div>
      </div>
        
      <?
    }elseif(CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
    {
        ?>
        <div class="page_basket">
            <div class="page_basket_message">
                <div class="page_basket_message_img">
                    <?if(SITE_ID == 's1'):?>
                    <img src="/verstka/img/svg/rub_blue.svg" />
                  <?else:?>
                    <img src="/verstka/img/svg/blue_dollar.svg" />
                  <?endif;?>
                </div>
                <div class="page_basket_message_title">
                  <?= GetMessage("CUSTOM_CONFIRM_WAITING_PAYMENT", Array())?>
                </div>
                <p><?= GetMessage("CUSTOM_CONFIRM_SCHET_TIME", Array())?></p>
                <p><?= GetMessage("CUSTOM_CONFIRM_THANKS", Array("#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?></p>
                <div class="buttons_box center">
                    <a href="<?=SITE_DIR?><?=$arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y"?>" class="btn_n red big"><?= GetMessage("CUSTOM_CONFIRM_DOAWNLOAD_PDF", Array())?></a>
                    <a href="<?=SITE_DIR?>buy/" class="btn_n big"><?= GetMessage("CUSTOM_CONFIRM_PRODUCTS", Array())?></a>
                </div>
            </div>
        </div>

        <?
    }elseif($arResult["PAY_SYSTEM"]["ACTION_FILE"] == "yandexcheckout" || $arResult["PAY_SYSTEM"]["ACTION_FILE"] == "yandexcheckoutvs"){
      ?>
         <div class="page_basket">
            <div class="page_basket_message">
                <div class="page_basket_message_img">
                    <?if(SITE_ID == 's1'):?>
                    <img src="/verstka/img/svg/rub_blue.svg" />
                  <?else:?>
                    <img src="/verstka/img/svg/blue_dollar.svg" />
                  <?endif;?>
                </div>
                <div class="page_basket_message_title">
                  <?= GetMessage("CUSTOM_CONFIRM_WAITING_PAYMENT", Array())?>
                </div>
                <p><?= GetMessage("CUSTOM_CONFIRM_THANKS", Array("#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?></p>
                <div class="buttons_box center">
                    <a target="_blanck" href="<?=SITE_DIR?><?=$arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>" class="btn_n big"><?= GetMessage("CUSTOM_CONFIRM_PAY_YANDEX", Array())?></a>
                    <a href="<?=SITE_DIR?>buy/" class="btn_n big"><?= GetMessage("CUSTOM_CONFIRM_PRODUCTS", Array())?></a>
                </div>
            </div>
        </div>
        
      <?
    }else{
	?>

    

	<b><?=GetMessage("SOA_TEMPL_ORDER_COMPLETE")?></b><br /><br />
	<table class="sale_order_full_table">
		<tr>
			<td>
				<?= GetMessage("SOA_TEMPL_ORDER_SUC", Array("#ORDER_DATE#" => $arResult["ORDER"]["DATE_INSERT"], "#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"]))?>
				<br /><br />
				<?= GetMessage("SOA_TEMPL_ORDER_SUC1", Array("#LINK#" => $arParams["PATH_TO_PERSONAL"])) ?>
			</td>
		</tr>
	</table>
    <?print_r($arResult["PAY_SYSTEM"]);?>
	<?
      if (!empty($arResult["PAY_SYSTEM"]))
      {
          
          ?>
          <br /><br />

          <table class="sale_order_full_table">
              <tr>
                  <td class="ps_logo">
                      <div class="pay_name"><?=GetMessage("SOA_TEMPL_PAY")?></div>
                      <?=CFile::ShowImage($arResult["PAY_SYSTEM"]["LOGOTIP"], 100, 100, "border=0", "", false);?>
                      <div class="paysystem_name"><?= $arResult["PAY_SYSTEM"]["NAME"] ?></div><br>
                  </td>
              </tr>
              <?
              if (strlen($arResult["PAY_SYSTEM"]["ACTION_FILE"]) > 0)
              {
                  ?>
                  <tr>
                      <td>
                          <?
                          if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
                          {
                              ?>
                              <script language="JavaScript">
                                  window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))?>');
                              </script>
                              <?= GetMessage("SOA_TEMPL_PAY_LINK", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))))?>
                              <?
                              if (CSalePdf::isPdfAvailable() && CSalePaySystemsHelper::isPSActionAffordPdf($arResult['PAY_SYSTEM']['ACTION_FILE']))
                              {
                                  ?><br />
                                  <?= GetMessage("SOA_TEMPL_PAY_PDF", Array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]))."&pdf=1&DOWNLOAD=Y")) ?>
                                  <?
                              }
                          }
                          else
                          {
                            
                              if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
                              {
                                  try
                                  {
                                      include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
                                  }
                                  catch(\Bitrix\Main\SystemException $e)
                                  {
                                      if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
                                          $message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
                                      else
                                          $message = $e->getMessage();

                                      echo '<span style="color:red;">'.$message.'</span>';
                                  }
                              }
                          }
                          ?>
                      </td>
                  </tr>
                  <?
              }
              ?>
          </table>
          <?
      }
    }
}
else
{
  global $USER;
  if (!$USER->IsAuthorized()){
    
?>
    <div class="page_basket">
        <div class="page_basket_message">
            
            <div class="page_basket_message_title">
              <?=GetMessage("CUSTOM_ORDER_CONFIRM_NOTAUT_TITLE")?>
            </div>
            <p> <a href="<?=SITE_DIR?>login/?returnUrl=<?=SITE_DIR?>cart/order/?ORDER_ID=<?=$arResult["ACCOUNT_NUMBER"]?>"><?=GetMessage("CUSTOM_ORDER_CONFIRM_NOTAUT_LOGIN_LINKTEXT")?></a><?=GetMessage("CUSTOM_ORDER_CONFIRM_NOTAUT_LOGIN_TEXT")?></p>
        </div>
    </div>  
<?          
  }elseif(!$arResult["ACCOUNT_NUMBER"]){
	?>
    <div class="page_basket">
        <div class="page_basket_message">
            
            <div class="page_basket_message_title">
              <?= GetMessage("SOA_TEMPL_ERROR_ORDER", Array())?>
            </div>
            <p><?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $_GET["ORDER_ID"]))?></p>
            <p><?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?></p>
        </div>
    </div>      
	<?
  }else{
?>    
  
    <div class="page_basket">
        <div class="page_basket_message">
            
            <div class="page_basket_message_title">
              <?= GetMessage("SOA_TEMPL_ERROR_ORDER", Array())?>
            </div>
            <p><?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST", Array("#ORDER_ID#" => $_GET["ORDER_ID"]))?></p>
            <p><?=GetMessage("SOA_TEMPL_ERROR_ORDER_LOST1")?></p>
        </div>
    </div>
<?          
  }
}
?>
