<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
//Минимальная длина поискового запроса
$minQueryLength = 2;
if (isset($_GET['q'])) 
{
	
    $isResult = true;

	if (strlen($arResult["REQUEST"]["~QUERY"]) >= $minQueryLength) 
	{
		if (!count($arResult['SEARCH'])) 
			$isEmptyResult = true;
	} 
	else 
	{
		$isEmptyRequest = true;
	}
} 
else 
{
	$isResult = false;
}
?>
<?/*<pre><?print_r($arResult);?></pre>*/?>
<div class="search <?if ($isResult): ?>result<?endif;?>">
    <?if (!$isResult): ?>
        <img class="search-main-image" src="/img/magnifier.svg">
    <?endif;?>
    <form class="search-form" action="" method="get">
        <?if ($isResult): ?>
            <img src="/img/magnifier.svg" class="result-image">
        <?endif;?>
        <input class="search-form-input" name="q" title="<?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH');?>" placeholder="<?=GetMessage('INOBITEC.SEARCH.PAGE.SITE_SEARCH');?>" value="<?=$arResult["REQUEST"]["QUERY"]?>">
        <button class="search-form-button"><?=GetMessage('INOBITEC.SEARCH.PAGE.FIND');?></button>
    </form>
    <?if ($isResult): ?>
    	<div class="search-text">
    		<p class="search-text-main"><?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH_RESULTS');?></p>
    		<?if ($isEmptyRequest): ?>
    			<p class="search-text-sub"><?=GetMessage('INOBITEC.SEARCH.PAGE.QUERY_EMPTY', array('#symbols#' => $minQueryLength));?></p>
    		<?else: ?>
    			<p class="search-text-no-search"><?=GetMessage('INOBITEC.SEARCH.PAGE.ON_REQUEST');?>: <span class="search-text-no-search-result"><?=$arResult["REQUEST"]["QUERY"]?></span></p>
    			<?if ($isEmptyResult): ?>
    				<p class="search-text-sub"><?=GetMessage('INOBITEC.SEARCH.PAGE.NOT_FOUND');?></p>
    			<?endif;?>
    		<?endif;?>
    	</div>
    	<?if (count($arResult['SEARCH']) >0): ?>
        	<?foreach ($arResult['SEARCH'] as $arItem): ?>
		        <div class="search-result">
		            <a href="<?=$arItem['URL'];?>" class="search-result-title"><?=$arItem['TITLE_FORMATED'];?></a>
		            <p class="search-result-text"><?=str_replace('____', '', $arItem['BODY_FORMATED']);?></p>
		        </div>
        	<?endforeach;?>
        <?endif;?>
        <?=$arResult['NAV_STRING'];?>
    <?endif;?>
</div>

    <!--<div class="search  result">
        <form class="search-form">
            <img src="/img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="<?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH');?>" placeholder="<?=GetMessage('INOBITEC.SEARCH.PAGE.SITE_SEARCH');?>">
            <button class="search-form-button"><?=GetMessage('INOBITEC.SEARCH.PAGE.FIND');?></button>
        </form>
        <div class="search-text">
            <p class="search-text-main"><?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH_RESULTS');?></p>
            <p class="search-text-sub"><?=GetMessage('INOBITEC.SEARCH.PAGE.QUERY_EMPTY');?></p>
        </div>
    </div>

    <div class="search result">
        <form class="search-form">
            <img src="/img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="<?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH');?>" placeholder="<?=GetMessage('INOBITEC.SEARCH.PAGE.SITE_SEARCH');?>">
            <button class="search-form-button"><?=GetMessage('INOBITEC.SEARCH.PAGE.FIND');?></button>
        </form>
        <div class="search-text">
            <p class="search-text-main"><?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH_RESULTS');?></p>
            <p class="search-text-no-search"><?=GetMessage('INOBITEC.SEARCH.PAGE.ON_REQUEST');?>: <span class="search-text-no-search-result">ккк</span></p>
            <p class="search-text-sub"><?=GetMessage('INOBITEC.SEARCH.PAGE.NOT_FOUND');?></p>
        </div>
    </div>

    <div class="search result">
        <form class="search-form">
            <img src="/img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="<?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH');?>" placeholder="Поиск по сайту">
            <button class="search-form-button"><?=GetMessage('INOBITEC.SEARCH.PAGE.FIND');?></button>
        </form>
        <div class="search-text">
            <p class="search-text-main"><?=GetMessage('INOBITEC.SEARCH.PAGE.SEARCH_RESULTS');?></p>
            <p class="search-text-no-search"><?=GetMessage('INOBITEC.SEARCH.PAGE.ON_REQUEST');?>: <span class="search-text-no-search-result">лицензия</span></p>
        </div>
        <?if (count($arResult['SEARCH']) >0): ?>
        	<?foreach ($arResult['SEARCH'] as $arItem): ?>
		        <div class="search-result">
		            <a href="#" class="search-result-title">ПРОСМОТРЩИК</a>
		            <p class="search-result-text">Инобитек DICOM-Просмотрщик — визуализатор DICOM-данных, полученных
		                с медицинского оборудования (modality), для анализа их различных пространственных реконструкций
		                (2D, 3D, Dynamic 3D, MPR, в том числе MIP и других).</p>
		        </div>
        	<?endforeach;?>
        <?endif;?>
        <div class="search-result">
            <a href="#" class="search-result-title">СЕРВЕР</a>
            <p class="search-result-text">Инобитек DICOM-Сервер (PACS) предназначен для архивирования,
                оперативного доступа, передачи изображений (исследований), полученных с
                различного DICOM-оборудования, установленного в учреждении.</p>
        </div>
        <div class="search-result search-result-borderBottom">
            <a href="#" class="search-result-title">ПРОСМОТРЩИК</a>
            <p class="search-result-text">Инобитек DICOM-Просмотрщик — визуализатор DICOM-данных, полученных
                с медицинского оборудования (modality), для анализа их различных пространственных реконструкций
                (2D, 3D, Dynamic 3D, MPR, в том числе MIP и других).</p>
        </div>
        <div class="search-navigation">
            <div class="search-navigation-buttons">
                <button class="search-navigation-buttons-btn search-navigation-buttons-btn-active">1</button>
                <button class="search-navigation-buttons-btn">2</button>
                <button class="search-navigation-buttons-btn">3</button>
                <button class="search-navigation-buttons-btn">4</button>
                <button class="search-navigation-buttons-btn">5</button>
            </div>
            <div class="search-navigation-text">
                <p>предыдущая</p>
                <p>следующая</p>
            </div>
        </div>
    </div>-->

