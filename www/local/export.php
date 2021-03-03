<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Intervolga\Migrato\Data\Module\Iblock\Iblock ; 


$_SERVER["DOCUMENT_ROOT"] = realpath("/home/krasovskaya/projects/inobitec/html");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('NO_AGENT_CHECK', true);
define("STATISTIC_SKIP_ACTIVITY_CHECK", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

@set_time_limit(0);

Loc::setCurrentLang('ru');
Loc::loadMessages(__FILE__);
if (!Loader::includeModule("intervolga.migrato"))
{
	die(Loc::getMessage('INTERVOLGA_MIGRATO.MODULE_NOT_INSTALLED'));
}
\CModule::IncludeModule('iblock');


$getList = \CIBlock::GetList($order, [
  
]);
$iblocks = [];

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

function exportFile( $iblock_type,$iFileId){
    $sFullFilesPath = __DIR__.'/'.$iblock_type.'/';
    $arFile = CFile::GetFileArray( $iFileId );

    if(!$arFile){
        echo "\nerror on get file !!\n";
        die();
    }
    
    $arPathInfo = pathinfo( $_SERVER['DOCUMENT_ROOT'].$arFile['SRC'] );
    $sFileName = $arFile['ID'].'.'.$arPathInfo['extension'];
    if (!file_exists( $sFullFilesPath )) {
        if ( !mkdir($sFullFilesPath, 0775, true) ){
           die('Ошибка создания директории.');
        }
    }
    if (!file_exists( $sFullFilesPath.'/'.$sFileName)) {
        if (copy( $_SERVER['DOCUMENT_ROOT'].$arFile['SRC'],$sFullFilesPath.'/'.$sFileName)){
           return  $iblock_type.'/'.$sFileName;
        }else{
            echo "\nunable copy\n";
            die();
        }
    }
    return  $iblock_type.'/'. $sFileName;

}
function getFile( $iblock_type, $iFileId )
    {
       
        if (!$iFileId) return "";

        if(is_array( $iFileId)){
            $res = [] ;

            foreach($iFileId as $fid){
                $res[]=exportFile( $iblock_type,$fid);
            }

            return $res ; 
        }


        return  exportFile( $iblock_type,$iFileId);

       
    }

$arConfig = array();

/**
 * Получаем информацию о инфоблоках
 */
 
$oIblock = new \CIBlock;
$oIblockProp = new \CIBlockProperty;
$oIblockPropEnum = new \CIBlockPropertyEnum;
 
$arConfig['IBLOCK'] = array();
$res = $oIblock->GetList(array(), array(/*'SITE_ID' => 's1', */'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N'), false);

    
while($arBlock = $res->Fetch()) {

    $key = !empty($arBlock['CODE']) ? strtoupper($arBlock['CODE']) : $arBlock['ID'];
    $arConfig['IBLOCK'][$key] = $arBlock;
    
    /**
     * Получаем информацию о свойствах инфоблока.
     */
    
    $arConfig['IBLOCK'][$key]['PROPERTIES'] = array();
    $aIblockProps = &$arConfig['IBLOCK'][$key]['PROPERTIES'];
     
    $props = $oIblockProp->GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $arBlock['ID']));
    
    while($fields = $props->GetNext()) {
        $aIblockProps[$fields['CODE']] = $fields;
        
        /**
         * Если свойство является списком, то получаем информацию о значениях.
         */
        if($fields['PROPERTY_TYPE'] == 'L') {
        
            $oResult = $oIblockPropEnum->GetList(array(), array('PROPERTY_ID' => $fields['ID']));
    
            while($arProp = $oResult->GetNext()) {
                $aIblockProps[$fields['CODE']]['VALUES'][$arProp['VALUE']] = $arProp['ID'];
                $aIblockProps[$fields['CODE']]['VALUES_XML'][$arProp['XML_ID']] = $arProp['ID'];
            }
        }
    }
}

/*
 * Получаем информацию о сайтах
 */
$arConfig['SITE'] = array();
$rsSites = CSite::GetList($by="sort", $order="desc");

while($arSite = $rsSites->Fetch()) {
    $arConfig['SITE'][$arSite['SITE']] = $arSite;
}


file_put_contents('config.raw', serialize( $arConfig));
$types = [] ;
echo "\n\n\n\n\n\n";
while ($iblock = $getList->Fetch()){
    
    if(!endsWith($iblock["IBLOCK_TYPE_ID"], '_fr')){//} || endsWith($iblock["IBLOCK_TYPE_ID"], '_de') || endsWith($iblock["IBLOCK_TYPE_ID"], '_fr')  || endsWith($iblock["IBLOCK_TYPE_ID"], '_eng')){
        //echo "------ ".$iblock["IBLOCK_TYPE_ID"].":".$iblock["CODE"]."\n";
        continue;;
    }
    echo "+++++++ ".$iblock["IBLOCK_TYPE_ID"].":".$iblock["CODE"]."\n";


    /*
    $messages = \CIBlock::getMessages( $iblock['ID']);
    if ($messages)
    {
        $iblock['MESSAGES'] =$messages;

        $fields = \CIBlock::getFields($record->getId()->getValue());
		if ($fields)
		{
			foreach ($fields as $k => $field)
			{
				unset($field['NAME']);
				$field = $this->exportWatermarkSettings($k, $field);
				$fields[$k] = $field;
			}
            $iblock['FIELDS_VALUES'] = $fields;
			
		}
    }
    */
   
    $iblock['SECTIONS'] = [];
    $rsSection = \CIBlockSection::GetList(
        $arOrder  = array("SORT" => "ASC"),
        $arFilter = array(
            "ACTIVE"    => "Y",
            'IBLOCK_ID'=> $iblock['ID'],
        ),
        false,
        $arSelect = array(),
        false
    );
    while($arSection = $rsSection->fetch()) {

        $uf = \CIBlockSection::GetList(
            $arOrder  = array("SORT" => "ASC"),
            $arFilter = array(
                "ACTIVE"    => "Y",
                'ID'=> $arSection['ID'],
            ),
            false,
            $arSelect = array( "UF_*"),
            false
        )->fetch();

        if($uf){
            $arSection['UFS'] = $uf; 
        }

        if (intval($arSection['PICTURE'])>0){
            $arSection['PICTURE'] = getFile($iblock['IBLOCK_TYPE_ID'], intval($arSection['PICTURE']) );
        }



        $iblock['SECTIONS'] []=$arSection;
        
    }

    $rsElement = CIBlockElement::GetList(
        $arOrder  = array("SORT" => "ASC"),
        $arFilter = array(
            "ACTIVE"    => "Y", 
            'IBLOCK_ID'=> $iblock['ID'],
        ),
        false,
        false,
      []//  $arSelectFields = array("*", "PROPERTY_*")
    );

    $iblock['elements'] = [];
    while($element = $rsElement->GetNextElement()) {
        $fields= $element->getFields();


        foreach($fields as $key=>$value){
            switch($key){
                case "DETAIL_PICTURE":
                case "PREVIEW_PICTURE":
                    $fields[$key] = getFile( $iblock['IBLOCK_TYPE_ID'], intval($fields[$key]) );
                    break;
                case "NAME":
                case "DETAIL_TEXT":
                case "PREVIEW_TEXT":

                   // $fields[$key] = htmlspecialchars($arFields["~".$key], ENT_QUOTES);;
                    break;

            }
        }


        $props=  $element->GetProperties();
        
        foreach($props as $sPropertyCode => $arProperty){

            if ( empty($arProperty['VALUE']) ) continue;
            $types[]=  $arProperty["PROPERTY_TYPE"];     
            
            switch($arProperty["PROPERTY_TYPE"]){
                case 'L':
                  
                    break;
                case 'E':
                  /* var_dump([
                       'field'=>$fields['ID'],
                       'props'=>$arProperty,
                   ]);
                   die();
                   */
                    break;

            }
            //фильтруем поля
            ///$arProperty = array_intersect_key($arProperty, array_flip( array("PROPERTY_TYPE","VALUE") ) );
            if ($arProperty["PROPERTY_TYPE"]=="F"){
                $props[$sPropertyCode]['FILE_PATH'] =getFile( $iblock['IBLOCK_TYPE_ID'], $arProperty['VALUE'] );
                
            }

           
        }



        $iblock['elements'][]=[
            'FIELDS'=>$fields,
            'PROPS'=>$props,
        ];
    }


    $iblocks [] = $iblock;

}

echo "fra.raw";
file_put_contents('fra.raw', serialize($iblocks));

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");