<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");
if(count($arResult["ORDER_PROP"]["USER_PROPS_Y"]) > 0)
  $users_props = array_merge($arResult["ORDER_PROP"]["USER_PROPS_N"],$arResult["ORDER_PROP"]["USER_PROPS_Y"]);
else
  $users_props = $arResult["ORDER_PROP"]["USER_PROPS_N"];
$auth_users_props = array();

$arrRegProps = array("OFERTA_AGREE");
//print_r($arResult["ORDER_PROP"]["USER_PROPS_Y"]);
usort($users_props, function($a, $b){
  return ($a['SORT'] - $b['SORT']);
});
$personTypeID = false;
foreach($arResult["PERSON_TYPE"] as $v){
  if($v["CHECKED"]=="Y"){
    $personTypeID = $v["ID"];
  }
}

/*
$firstBlock = $secondBlock = $thirdBlock = $fourtBlock = $fifthBlock = array();
if(($users_props[0]["PROPS_GROUP_ID"] == 3 || $users_props[0]["PROPS_GROUP_ID"] == 1)){
  $firstBlock[] = $users_props[0];
  unset($users_props[0]);
}*/
foreach($users_props as $key => $prop){

    if(in_array($prop["CODE"], $arrRegProps)){
      $auth_users_props[] = $prop;

    }
    unset($users_props[$key]);

}
//PrintPropsForm($auth_users_props, $arParams["TEMPLATE_LOCATION"]);
            if(count($auth_users_props) > 0){
        ?>
            <div class="clear"></div>
              <div class="form_table_col">
        <?
                foreach ($auth_users_props as $key => $arProperties){
                  if($key%2 == 0){
                    customPrintFormProp($arProperties, $personTypeID);
                  }
                }
        ?>
              </div>
              <div class="form_table_col">
                <?
                foreach ($auth_users_props as $key => $arProperties){
                  if($key%2 == 1){
                    customPrintFormProp($arProperties, $personTypeID);
                  }
                }
        ?>      
              </div>
              
        <?       
          }
       
         

