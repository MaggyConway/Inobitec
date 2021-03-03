<? 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-type: application/json');
$formId = $_GET['formId'];
$fieldCode = $_GET['fieldCode'];
CModule::IncludeModule('form');
CModule::IncludeModule('iblock');
if (CForm::GetDataByID($formId, 
    $form, 
    $questions, 
    $answers, 
    $dropdown, 
    $multiselect))
{
    /*echo "<pre>";
        print_r($form);
        print_r($questions);
        print_r($answers);
        print_r($dropdown);
        print_r($multiselect);
    echo "</pre>";*/
}
$arrResult = $arrAnswer = array();
foreach($answers[$fieldCode] as $answer){
  $arrResult[$answer['ID']] = array();
  $arrAnswer[$answer['VALUE']] = $answer['ID'];
}

$preSections = CIBlockSection::GetList(
    Array("SORT"=>"ASC"),
    Array('IBLOCK_CODE' => 'support_prosuct_version_s1'),
    false,
    Array(),
    false
);
$arrSections = $arrElems = array();
while($arrSection = $preSections->GetNext())
{
  $arrSections[$arrSection['ID']]=$arrSection['NAME'];
}

$preElems = CIBlockElement::GetList(
					array('sort' => 'asc'),
					array('IBLOCK_CODE' => 'support_prosuct_version_s1'),
					false,
					array(),
					array('ID', 'NAME', 'IBLOCK_SECTION_ID')
				);
while($arrElement = $preElems->GetNext())
{
  $sectionVal = $arrSections[$arrElement['IBLOCK_SECTION_ID']];
  $answerId = $arrAnswer[$sectionVal];
  $arrResult[$answerId][] = $arrElement['NAME'];
  
}
echo json_encode($arrResult);


