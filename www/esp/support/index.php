<?
$newPageCSS =  true;
$newPage2020 = true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Inobitec Products Manual: user guide, FAQ, knowledge base and paid services upon request");
$APPLICATION->SetPageProperty("keywords", "Manual to DICOM Viewer, user’s manual to PACS Server, FAQ, Inobitec paid services, Frequently Asked Questions, user guide to DICOM Viewer, operation manual to DICOM Server,  download Viewer, download Inobitec DICOM Viewer, download Inobitec Web DICOM Viewer, download Inobitec DICOM Server, Inobitec knowledge base");
$APPLICATION->SetPageProperty("title", "Inobitec / Soporte técnico");
$APPLICATION->SetTitle("Apoyo");
?>

<div class="support-search">
  <div class="wr">
    <form action="/esp/search/" method="GET">
      <div class="input support-search__input">
        <input type="text" name="search" id="search" placeholder="Buscar por el sitio web">
      </div>
      <button class="btn btn--light-blue-outline support-search__button" type="submit"><span>BUSCAR</span>
        <svg width="20" height="21">
          <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-search-blue"></use>
        </svg>
      </button>
    </form>
  </div>
</div>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"support_links", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "149",
		"IBLOCK_TYPE" => "information_es",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "500",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "ATT_ICON",
			1 => "ATT_LINKS",
			2 => "",
		),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "support_links"
	),
	false
);?>          

<div class="support-form" id="support-form">
  <div class="wr">
    <div class="support-form__title">
      <svg width="80" height="80">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/2020/img/sprite.svg#sprite-question"></use>
      </svg>
      <h2>PREGUNTAR O INFORMAR SOBRE EL PROBLEMA</h2>
    </div>
    <div class="support-form__toggle">
      <div class="support-form__radio">
        <input class="visually-hidden" type="radio" name="message-type" id="question" value="question" checked>
        <label for="question">PREGUNTAR</label>
      </div>
      <div class="support-form__radio">
        <input class="visually-hidden" type="radio" name="message-type" id="issue" value="issue">
        <label for="issue">INFORMAR SOBRE EL PROBLEMA</label>
      </div>
    </div>
    <div class="support-form__question-form js-question-form is-open">
      <?$APPLICATION->IncludeComponent(
          "bitrix:form", 
          "question", 
          array(
              "AJAX_MODE" => "N",
              "AJAX_OPTION_ADDITIONAL" => "",
              "AJAX_OPTION_HISTORY" => "N",
              "AJAX_OPTION_JUMP" => "N",
              "AJAX_OPTION_STYLE" => "N",
              "CACHE_TIME" => "3600",
              "CACHE_TYPE" => "A",
              "CHAIN_ITEM_LINK" => "",
              "CHAIN_ITEM_TEXT" => "",
              "COMPONENT_TEMPLATE" => "question",
              "EDIT_ADDITIONAL" => "N",
              "EDIT_STATUS" => "N",
              "IGNORE_CUSTOM_TEMPLATE" => "N",
              "NOT_SHOW_FILTER" => array(
                  0 => "",
                  1 => "",
              ),
              "NOT_SHOW_TABLE" => array(
                  0 => "",
                  1 => "",
              ),
              "RESULT_ID" => $_REQUEST[RESULT_ID],
              "SEF_MODE" => "N",
              "SHOW_ADDITIONAL" => "N",
              "SHOW_ANSWER_VALUE" => "N",
              "SHOW_EDIT_PAGE" => "N",
              "SHOW_LIST_PAGE" => "Y",
              "SHOW_STATUS" => "N",
              "SHOW_VIEW_PAGE" => "Y",
              "START_PAGE" => "new",
              "SUCCESS_URL" => "",
              "USE_EXTENDED_ERRORS" => "N",
              "WEB_FORM_ID" => "24",
              "VARIABLE_ALIASES" => array(
                  "action" => "action",
              )
          ),
          false
      );?>
    </div>
    <div class="support-form__issue-form js-issue-form">
      <?$APPLICATION->IncludeComponent(
          "bitrix:form", 
          "problem", 
          array(
              "AJAX_MODE" => "N",
              "AJAX_OPTION_ADDITIONAL" => "",
              "AJAX_OPTION_HISTORY" => "N",
              "AJAX_OPTION_JUMP" => "N",
              "AJAX_OPTION_STYLE" => "N",
              "CACHE_TIME" => "3600",
              "CACHE_TYPE" => "A",
              "CHAIN_ITEM_LINK" => "",
              "CHAIN_ITEM_TEXT" => "",
              "COMPONENT_TEMPLATE" => "question",
              "EDIT_ADDITIONAL" => "N",
              "EDIT_STATUS" => "N",
              "IGNORE_CUSTOM_TEMPLATE" => "N",
              "NOT_SHOW_FILTER" => array(
                  0 => "",
                  1 => "",
              ),
              "NOT_SHOW_TABLE" => array(
                  0 => "",
                  1 => "",
              ),
              "RESULT_ID" => $_REQUEST[RESULT_ID],
              "SEF_MODE" => "N",
              "SHOW_ADDITIONAL" => "N",
              "SHOW_ANSWER_VALUE" => "N",
              "SHOW_EDIT_PAGE" => "N",
              "SHOW_LIST_PAGE" => "Y",
              "SHOW_STATUS" => "N",
              "SHOW_VIEW_PAGE" => "Y",
              "START_PAGE" => "new",
              "SUCCESS_URL" => "",
              "USE_EXTENDED_ERRORS" => "N",
              "WEB_FORM_ID" => "25",
              "VARIABLE_ALIASES" => array(
                  "action" => "action",
              )
          ),
          false
      );?>

    </div>
  </div>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD"></script>
<script>
grecaptcha.ready(function() {
    grecaptcha.execute('6LdNy7wUAAAAAPeRFWzbD3GIijmFnH-dJ_oNE8YD', {action: 'homepage'}).then(function(token) {
    	$('<input type="hidden" name="recaptcha_response">').val(token).prependTo($('form[name="QUESTION_FORM_EN"]'));
        $('<input type="hidden" name="recaptcha_response">').val(token).prependTo($('form[name="TROUBLE_FORM_EN"]'));
    });
});
</script>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>