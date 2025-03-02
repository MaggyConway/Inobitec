<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
LocalRedirect("/");
$APPLICATION->SetTitle("Форум");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:forum", 
	".default", 
	array(
		"SEF_MODE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"DATE_FORMAT" => "d.m.Y",
		"DATE_TIME_FORMAT" => "d.m.Y H:i:s",
		"FID" => array(
		),
		"SET_TITLE" => "N",
		"SET_NAVIGATION" => "N",
		"FORUMS_PER_PAGE" => "15",
		"TOPICS_PER_PAGE" => "10",
		"MESSAGES_PER_PAGE" => "3",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"PATH_TO_ICON" => "/bitrix/images/forum/icon/",
		"USE_DESC_PAGE_TOPIC" => "Y",
		"USE_DESC_PAGE_MESSAGE" => "N",
		"SHOW_FORUMS_LIST" => "N",
		"SHOW_FORUM_ANOTHER_SITE" => "Y",
		"SEF_FOLDER" => "/forum/",
		"COMPONENT_TEMPLATE" => ".default",
		"THEME" => "blue",
		"SHOW_TAGS" => "Y",
		"SEO_USER" => "",
		"SEO_USE_AN_EXTERNAL_SERVICE" => "N",
		"SHOW_FORUM_USERS" => "N",
		"SHOW_SUBSCRIBE_LINK" => "N",
		"SHOW_AUTH_FORM" => "Y",
		"SHOW_NAVIGATION" => "N",
		"SHOW_LEGEND" => "N",
		"SHOW_STATISTIC_BLOCK" => array(
		),
		"SHOW_FORUMS" => "N",
		"SHOW_FIRST_POST" => "N",
		"SHOW_AUTHOR_COLUMN" => "N",
		"TMPLT_SHOW_ADDITIONAL_MARKER" => "",
		"PAGE_NAVIGATION_TEMPLATE" => "",
		"PAGE_NAVIGATION_WINDOW" => "",
		"AJAX_POST" => "N",
		"WORD_WRAP_CUT" => "",
		"WORD_LENGTH" => "",
		"RESTART" => "N",
		"NO_WORD_LOGIC" => "N",
		"USE_LIGHT_VIEW" => "Y",
		"USER_PROPERTY" => array(
		),
		"USER_FIELDS" => array(
		),
		"HELP_CONTENT" => "",
		"RULES_CONTENT" => "",
		"CHECK_CORRECT_TEMPLATES" => "N",
		"CACHE_TIME_USER_STAT" => "60",
		"CACHE_TIME_FOR_FORUM_STAT" => "3600",
		"PATH_TO_AUTH_FORM" => "",
		"TIME_INTERVAL_FOR_USER_STAT" => "",
		"USE_NAME_TEMPLATE" => "N",
		"NAME_TEMPLATE" => "",
		"IMAGE_SIZE" => "500",
		"ATTACH_MODE" => array(
			0 => "THUMB",
		),
		"ATTACH_SIZE" => "",
		"EDITOR_CODE_DEFAULT" => "N",
		"SEND_MAIL" => "",
		"SEND_ICQ" => "",
		"SET_DESCRIPTION" => "N",
		"SET_PAGE_PROPERTY" => "N",
		"USE_RSS" => "Y",
		"SHOW_RATING" => "",
		"RATING_ID" => array(
		),
		"RATING_TYPE" => "",
		"RSS_CACHE" => "",
		"RSS_TYPE_RANGE" => array(
		),
		"RSS_COUNT" => "30",
		"RSS_TN_TITLE" => "",
		"RSS_TN_DESCRIPTION" => "",
		"SEF_URL_TEMPLATES" => array(
			"index" => "index.php",
			"list" => "forum#FID#/",
			"read" => "forum#FID#/topic#TID#/message#MID#/",
			"message" => "messages/forum#FID#/message#MID#/#TITLE_SEO#",
			"help" => "help/",
			"rules" => "rules/",
			"message_appr" => "message/approve/forum#FID#/topic#TID#/",
			"message_move" => "message/move/forum#FID#/topic#TID#/message#MID#/",
			"rss" => "rss/#TYPE#/#MODE#/#IID#/",
			"search" => "search/",
			"subscr_list" => "subscribe/",
			"active" => "topic/new/",
			"topic_move" => "topic/move/forum#FID#/topic#TID#/",
			"topic_new" => "topic/add/forum#FID#/",
			"topic_search" => "topic/search/",
			"user_list" => "users/",
			"profile" => "user/#UID#/edit/",
			"profile_view" => "user/#UID#/",
			"user_post" => "user/#UID#/post/#mode#/",
			"message_send" => "user/#UID#/send/#TYPE#/",
			"pm_list" => "pm/folder#FID#/",
			"pm_edit" => "pm/folder#FID#/message#MID#/user#UID#/#mode#/",
			"pm_read" => "pm/folder#FID#/message#MID#/",
			"pm_search" => "pm/search/",
			"pm_folder" => "pm/folders/",
		)
	),
	false
);
?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>