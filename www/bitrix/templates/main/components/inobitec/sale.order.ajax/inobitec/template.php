<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($USER->IsAuthorized() || $arParams["ALLOW_AUTO_REGISTER"] == "Y")
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
          
			$APPLICATION->RestartBuffer();
            //header("Location: ". $arResult["REDIRECT_URL"]); 
			?>
			<script type="text/javascript">
              console.log("CHECK");
				window.location='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}

	}
}

$APPLICATION->SetAdditionalCSS($templateFolder."/style_cart.css");
$APPLICATION->SetAdditionalCSS($templateFolder."/style.css");
?>
<div class="page_basket">
  <div class="page_basket_personal_info">
<a name="order_form"></a>

<div id="" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (!function_exists("getColumnName"))
{
	function getColumnName($arHeader)
	{
		return (strlen($arHeader["name"]) > 0) ? $arHeader["name"] : GetMessage("SALE_".$arHeader["id"]);
	}
}

if (!function_exists("cmpBySort"))
{
	function cmpBySort($array1, $array2)
	{
		if (!isset($array1["SORT"]) || !isset($array2["SORT"]))
			return -1;

		if ($array1["SORT"] > $array2["SORT"])
			return 1;

		if ($array1["SORT"] < $array2["SORT"])
			return -1;

		if ($array1["SORT"] == $array2["SORT"])
			return 0;
	}
}
?>

<div class="bx_order_make">
	<?
	if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
	{
		if(!empty($arResult["ERROR"]))
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);
		}
		elseif(!empty($arResult["OK_MESSAGE"]))
		{
			foreach($arResult["OK_MESSAGE"] as $v)
				echo ShowNote($v);
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	}
	else
	{
		if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
		{
			if(strlen($arResult["REDIRECT_URL"]) == 0)
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
			}
		}
		else
		{
			?>
            <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@18.11.1/dist/css/suggestions.min.css" type="text/css" rel="stylesheet" />
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/suggestions-jquery@17.12.0/dist/js/jquery.suggestions.min.js"></script>
			<script type="text/javascript">

			<?if(CSaleLocation::isLocationProEnabled()):?>

				<?
				// spike: for children of cities we place this prompt
				$city = \Bitrix\Sale\Location\TypeTable::getList(array('filter' => array('=CODE' => 'CITY'), 'select' => array('ID')))->fetch();
				?>

				BX.saleOrderAjax.init(<?=CUtil::PhpToJSObject(array(
					'source' => $this->__component->getPath().'/get.php',
					'cityTypeId' => intval($city['ID']),
					'messages' => array(
						'otherLocation' => '--- '.GetMessage('SOA_OTHER_LOCATION'),
						'moreInfoLocation' => '--- '.GetMessage('SOA_NOT_SELECTED_ALT'), // spike: for children of cities we place this prompt
						'notFoundPrompt' => '<div class="-bx-popup-special-prompt">'.GetMessage('SOA_LOCATION_NOT_FOUND').'.<br />'.GetMessage('SOA_LOCATION_NOT_FOUND_PROMPT', array(
							'#ANCHOR#' => '<a href="javascript:void(0)" class="-bx-popup-set-mode-add-loc">',
							'#ANCHOR_END#' => '</a>'
						)).'</div>'
					)
				))?>);

			<?endif?>
              
              var $flagSugg = true;
              var $flagSuggBIK = true;
              <?if(SITE_ID == "s1"):?>
              var $innData = $('[data-call="INN"]').suggestions({
                  token: "1bc1f26cae9e7f1ce987a51a6fd94c7ba1791ff7",
                  type: "PARTY",
                  count: 5,
                  minChars: 6,
                  // Вызывается, когда пользователь выбирает одну из подсказок
                  onSelect: showSuggestion,
                  params: {
                    type: $SuggestionType
                  }
                }).suggestions();
                
                
                 var $bankData = $('[data-call="BIK"]').suggestions({
                    token: "1bc1f26cae9e7f1ce987a51a6fd94c7ba1791ff7",
                    type: "BANK",
                    minChars: 4,
                    /* Вызывается, когда пользователь выбирает одну из подсказок */
                    onSelect: showSuggestionBank,
                }).suggestions();
                <?endif;?>
              
              function join(arr /*, separator */) {
                var separator = arguments.length > 1 ? arguments[1] : ", ";
                return arr.filter(function(n){return n}).join(separator);
              }
              
              if (!String.prototype.startsWith) {
                String.prototype.startsWith = function(searchString, position) {
                  position = position || 0;
                  return this.indexOf(searchString, position) === position;
                };
              }
              
              function typeDescription(type) {
                var TYPES = {
                  'INDIVIDUAL': 'Индивидуальный предприниматель',
                  'LEGAL': 'Организация'
                }
                return TYPES[type];
              }
              //7456013658
              var $type = $('input[name=PERSON_TYPE]:checked').val();
              var $SuggestionType = "LEGAL";
              if($type == 1){
                $SuggestionType = "LEGAL";
              }else if($type == 3){
                $SuggestionType = "INDIVIDUAL";
              }
              <?php if(SITE_ID == 's1'):?>
              function changeFormINNData(data){
                $innData.disable();
                $('[data-call="INN"]').val(data.inn);
                $('[data-call="INN"]').trigger('input');
                $('[data-call="INN"]').trigger('change');
                $innData.enable();
                if($('[data-call="KPP"]').length > 0 && !$('[data-call="KPP"]').val()){
                  console.log("KPP");
                  $('[data-call="KPP"]').val(data.kpp);
                  $('[data-call="KPP"]').trigger('input');
                  $('[data-call="KPP"]').trigger('change');
                }
                
                if($('[data-call="OGRN"]').length > 0 && !$('[data-call="OGRN"]').val()){
                  console.log("OGRN");
                  $('[data-call="OGRN"]').val(data.ogrn);
                  $('[data-call="OGRN"]').trigger('input');
                  $('[data-call="OGRN"]').trigger('change');
                }


                if (data.address) {
                  var address = "";
                  if (data.address.data.qc == "0") {
                    address = join([data.address.data.postal_code, data.address.value]);
                  } else {
                    address = data.address.data.source;
                  }
                  if($('[data-call="ADDRESS"]').length > 0 && !$('[data-call="ADDRESS"]').val()){
                    console.log("ADDRESS");
                    $('[data-call="ADDRESS"]').val(address);
                    $('[data-call="ADDRESS"]').trigger('input');
                    $('[data-call="ADDRESS"]').trigger('change');
                  }
                  
                  if($('[data-call="MAIL_ADDRESS"]').length > 0 && !$('[data-call="MAIL_ADDRESS"]').val()){
                    console.log("MAIL_ADD");
                    $('[data-call="MAIL_ADDRESS"]').val(address);
                    $('[data-call="MAIL_ADDRESS"]').trigger('input');
                    $('[data-call="MAIL_ADDRESS"]').trigger('change');
                  }

                }
                
                if($('[data-call="WORK_COMPANY"]').length > 0 && !$('[data-call="WORK_COMPANY"]').val()){
                  console.log("WORK_COMP");
                  console.log(data.name);
                  $('[data-call="WORK_COMPANY"]').val(data.name.short);
                  $('[data-call="WORK_COMPANY"]').trigger('input');
                  $('[data-call="WORK_COMPANY"]').trigger('change');
                }
                
                if($('[data-call="LEGAL_FORM"]').length > 0 && !$('[data-call="LEGAL_FORM"]').val()){
                  $('[data-call="LEGAL_FORM"]').val(data.opf.short);
                  $('[data-call="LEGAL_FORM"]').trigger('input');
                  $('[data-call="LEGAL_FORM"]').trigger('change');
                }
                
                if($('[data-call="OGRNIP"]').length > 0 && !$('[data-call="OGRNIP"]').val()){
                  console.log("OGRNIP");
                  $('[data-call="OGRNIP"]').val(data.ogrn);
                  $('[data-call="OGRNIP"]').trigger('input');
                  $('[data-call="OGRNIP"]').trigger('change');
                }
                
                var $checkType = $('input[name=PERSON_TYPE]:checked').val();
              
                //ЕСли ИП
                if($checkType == 3){
                  if($('[data-call="NAME"]').length > 0 && !$('[data-call="NAME"]').val()){
                    console.log("NAME");
                    $('[data-call="NAME"]').val(data.name.full);
                    $('[data-call="NAME"]').trigger('input');
                    $('[data-call="NAME"]').trigger('change');
                  }
                }
                
                
                if($checkType == 1){
                  if(data.management){
                    if($('[data-call="DIRECTOR_JOB"]').length > 0 && !$('[data-call="DIRECTOR_JOB"]').val()){
                      console.log("DIR_JOB");
                      $('[data-call="DIRECTOR_JOB"]').val(data.management.post);
                      $('[data-call="DIRECTOR_JOB"]').trigger('input');
                      $('[data-call="DIRECTOR_JOB"]').trigger('change');
                    }
                    
                    if($('[data-call="DIRECTOR_FIO"]').length > 0 && !$('[data-call="DIRECTOR_FIO"]').val()){
                      console.log("DIR_FIO");
                      $('[data-call="DIRECTOR_FIO"]').val(data.management.name);
                      $('[data-call="DIRECTOR_FIO"]').trigger('input');
                      $('[data-call="DIRECTOR_FIO"]').trigger('change');
                    }
                  }
                  
                }
              }
              
              $('[data-call="INN"]').on( "suggestions-fixdata", function( event, suggestion ) {
                console.log("firstCheckInn");
                $flagSugg = true;
                if(!suggestion)
                  return;
                var data = suggestion.data;
                if(!data)
                  return;
                changeFormINNData(data);
              });
              
              function changeFormBANKData(data){
                if($('[data-call="KOR_ACC"]').length > 0 && !$('[data-call="KOR_ACC"]').val()){
                  $('[data-call="KOR_ACC"]').val(data.correspondent_account);
                  $('[data-call="KOR_ACC"]').trigger('input');
                  $('[data-call="KOR_ACC"]').trigger('change');
                }
                
                if($('[data-call="BANK_NAME"]').length > 0 && !$('[data-call="BANK_NAME"]').val()){
                  $('[data-call="BANK_NAME"]').val(data.name.payment);
                  $('[data-call="BANK_NAME"]').trigger('input');
                  $('[data-call="BANK_NAME"]').trigger('change');
                }
                $bankData.disable();
                $('[data-call="BIK"]').val(data.bic);
                $('[data-call="BIK"]').trigger('input');
                $('[data-call="BIK"]').trigger('change');
                $bankData.enable();
              }
              
              $('[data-call="BIK"]').on( "suggestions-fixdata", function( event, suggestion ) {
                console.log("firstCheckBIK");
                $flagSuggBIK = true;
                if(!suggestion)
                  return;
                var data = suggestion.data;
                if (!data)
                  return;
                changeFormBANKData(data);
              });
              <?endif;?>
              
              
              
              
              $('#ORDER_FORM').on('click', '#ORDER_CONFIRM_BUTTON', function(){
                 angular.element('#ORDER_FORM').scope().sumbitOrder();
                 angular.element('#ORDER_FORM').scope().$apply() 
              })
              <?php if(SITE_ID == 's1'):?>
              $( document ).ready(function() {
                
                  if($('[data-call="INN"]').val()){
                    $flagSugg = false;
                    $innData.fixData();
                  }

                  if($('[data-call="BIK"]').val()){
                    $flagSuggBIK = false;
                    $bankData.fixData();
                  }
              });
              <?php endif;?>
             
              function showSuggestionBank(suggestion) {
                if(!$flagSuggBIK)
                  return;
                var data = suggestion.data;
                if (!data)
                  return;
                changeFormBANKData(data)
              }

              function showSuggestion(suggestion) {
                if(!$flagSugg)
                  return;
                console.log(suggestion);
                var data = suggestion.data;
                if (!data)
                  return;
                changeFormINNData(data);
              }
            
			var BXFormPosting = false;
			function submitForm(val)
			{
				if (BXFormPosting === true)
					return true;

				BXFormPosting = true;
				if(val != 'Y')
					BX('confirmorder').value = 'N';

				var orderForm = BX('ORDER_FORM');
				BX.showWait();

				<?if(CSaleLocation::isLocationProEnabled()):?>
					BX.saleOrderAjax.cleanUp();
				<?endif?>
				BX.ajax.submit(orderForm, ajaxResult);

				return true;
			}

			function ajaxResult(res)
			{
                console.log("check");
				var orderForm = BX('ORDER_FORM');
				try
				{
					// if json came, it obviously a successfull order submit

                    
					var json = JSON.parse(res);
					BX.closeWait();
                    console.log("Test");
					if (json.error)
					{
						BXFormPosting = false;
						return;
					}
					else if (json.redirect)
					{
						window.location = json.redirect;
					}
				}
				catch (e)
				{
					// json parse failed, so it is a simple chunk of html

					BXFormPosting = false;
                    //console.log(res);
                    /*var scope = angular.element('#order_form_content').scope();
                    scope.refresh(res);
                    scope.$apply();*/
					BX('order_form_content').innerHTML = res;
                    var $div = $('#ORDER_FORM');
                    var $target = $("[ng-app]");
                    angular.element($target).injector().invoke(['$compile', function ($compile) {
                        var $scope = angular.element($target).scope();
                        $compile($div)($scope);
                        $scope.$apply();
                    }]);
                    var $type = $('input[name=PERSON_TYPE]:checked').val();
                    var $SuggestionType = "LEGAL";
                    if($type == 1){
                      $SuggestionType = "LEGAL";
                    }else if($type == 3){
                      $SuggestionType = "INDIVIDUAL";
                    }
                    <?php if(SITE_ID == 's1'):?>
                    $innData = $('[data-call="INN"]').suggestions({
                      token: "1bc1f26cae9e7f1ce987a51a6fd94c7ba1791ff7",
                      type: "PARTY",
                      count: 5,
                      minChars: 6,
                      // Вызывается, когда пользователь выбирает одну из подсказок
                      onSelect: showSuggestion,
                      params: {
                        type: $SuggestionType
                      }
                    }).suggestions();
                    
                    $bankData = $('[data-call="BIK"]').suggestions({
                        token: "1bc1f26cae9e7f1ce987a51a6fd94c7ba1791ff7",
                        type: "BANK",
                        minChars: 6,
                        /* Вызывается, когда пользователь выбирает одну из подсказок */
                        onSelect: showSuggestionBank,
                    }).suggestions();
                    <?endif;?>
					<?if(CSaleLocation::isLocationProEnabled()):?>
						BX.saleOrderAjax.initDeferredControl();
					<?endif?>
				}

				BX.closeWait();
				BX.onCustomEvent(orderForm, 'onAjaxSuccess');
			}

			function SetContact(profileId)
			{
				BX("profile_change").value = "Y";
				submitForm();
			}
			</script>
			<?if($_POST["is_ajax_post"] != "Y")
			{
				?><form action="<?=$APPLICATION->GetCurPage();?>" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data" ng-controller="orderController">
				<?=bitrix_sessid_post()?>
				<div id="order_form_content">
				<?
			}
			else
			{
				$APPLICATION->RestartBuffer();
			}

			if($_REQUEST['PERMANENT_MODE_STEPS'] == 1)
			{
				?>
				<input type="hidden" name="PERMANENT_MODE_STEPS" value="1" />
				<?
			}

			if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
			{
				foreach($arResult["ERROR"] as $v)
					echo ShowError($v);
				?>
				<script type="text/javascript">
					top.BX.scrollToNode(top.BX('ORDER_FORM'));
				</script>
				<?
			}
            
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/person_type.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
			}
			else
			{
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
				include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
			}

			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
            include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_afterpayment.php");
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");
			if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
				echo $arResult["PREPAY_ADIT_FIELDS"];
            
			?>

			<?if($_POST["is_ajax_post"] != "Y")
			{
				?>
                        
                        <!--div class="page_basket_personal_info_type_tab active">
                          <div class="form_table"-->
                              <?  ?>
                          <!--/div>
                        </div-->
					</div>
					<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
					<input type="hidden" name="profile_change" id="profile_change" value="N">
					<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
					<input type="hidden" name="json" value="Y">
                    <div style="clear:both;"></div>
                    <div class="page_buy_p_summ">
                      <div class="buy_p-btn-case">
                        
                          <button type="button" ng-class="{'test-class': checkFormValidate()}" class="btn-blue ng-binding btn-blue-disable" tabindex="999" id="ORDER_CONFIRM_BUTTON"><?=GetMessage("SOA_TEMPL_BUTTON")?></button>
                      </div>
                    </div>  
                    <!--div class="page_buy_p bx_ordercart_order_pay_center" style="border-top: none; padding-top: 0px;">
                      <a href="#" onclick="return false;"   id="ORDER_CONFIRM_BUTTON" class="page_buy_button to_basket btn_n"><?=GetMessage("SOA_TEMPL_BUTTON")?></a>
                    </div-->
                    
                     
                    <? /*<div class="bx_ordercart_order_pay_center"><a href="javascript:void();"  id="ORDER_CONFIRM_BUTTON" class="checkout"><?=GetMessage("SOA_TEMPL_BUTTON")?></a></div>  * */?>
				</form>
            
				<?
				if($arParams["DELIVERY_NO_AJAX"] == "N")
				{
					?>
					<div style="display:none;"><?$APPLICATION->IncludeComponent("bitrix:sale.ajax.delivery.calculator", "", array(), null, array('HIDE_ICONS' => 'Y')); ?></div>
					<?
				}
			}
			else
			{
				?>
				<script type="text/javascript">
					top.BX('confirmorder').value = 'Y';
					top.BX('profile_change').value = 'N';
				</script>
				<?
				die();
			}
		}
	}
	?>
	</div>
</div>

</div>
</div>
<?if(CSaleLocation::isLocationProEnabled()):?>

	<div style="display: none">
		<?// we need to have all styles for sale.location.selector.steps, but RestartBuffer() cuts off document head with styles in it?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.steps", 
			".default", 
			array(
			),
			false
		);?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:sale.location.selector.search", 
			".default", 
			array(
			),
			false
		);?>
	</div>

<?endif?>