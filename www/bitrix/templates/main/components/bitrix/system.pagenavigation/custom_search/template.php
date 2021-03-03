<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}
/** Старая версия начало
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>
<!--Старая версия окончание-->
<div class="search-navigation">
	<div class="search-navigation-buttons">
	    <?if ($arResult["NavPageNomer"] > 1):?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" class="search-navigation-buttons-btn">1</a>
		<?else:?>
			<button class="search-navigation-buttons-btn search-navigation-buttons-btn-active">1</button>
		<?endif;?>
	    <?
		$arResult["nStartPage"]++;
		while($arResult["nStartPage"] <= $arResult["nEndPage"]-1):
		?>
			<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
				<button class="search-navigation-buttons-btn search-navigation-buttons-btn-active"><?=$arResult["nStartPage"]?></button>
			<?else:?>
				<a class="search-navigation-buttons-btn" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
			<?endif?>
			<?$arResult["nStartPage"]++?>
		<?endwhile?>
		<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
			<?if($arResult["NavPageCount"] > 1):?>
				<a class="search-navigation-buttons-btn" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a>
			<?endif;?>
		<?else:?>
			<?if($arResult["NavPageCount"] > 1):?>
				<button class="search-navigation-buttons-btn search-navigation-buttons-btn-active"><?=$arResult["NavPageCount"]?></button>
			<?endif;?>
		<?endif;?>
	</div>
    <div class="search-navigation-text">
		<?if ($arResult["NavPageNomer"] > 1):?>
			<p>
				<?if ($arResult["NavPageNomer"] > 2):?>
					<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.PREV');?></a>
				<?else:?>
					<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.PREV');?></a>
				<?endif?>
			</p>
		<?else:?>
			<p><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.PREV');?></p>
		<?endif;?>
        <?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
			<p>
				<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.NEXT');?></a>
			</p>
		<?else:?>
			<p><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.NEXT');?></p>
		<?endif;?>
    </div>
</div>*/?>
<!--Старая версия окончание-->
<?
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
// to show always first and last pages
$arResult["nStartPage"] = 1;
$arResult["nEndPage"] = $arResult["NavPageCount"];

$sPrevHref = '';
if ($arResult["NavPageNomer"] > 1)
{
	$bPrevDisabled = false;
	
	if ($arResult["bSavePage"] || $arResult["NavPageNomer"] > 2)
	{
		$sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
	}
	else
	{
		$sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
	}
}
else
{
	$bPrevDisabled = true;
}

$sNextHref = '';
if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
{
	$bNextDisabled = false;
	$sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
}
else
{
	$bNextDisabled = true;
}
?>
<div class="search-navigation">
    <div class="search-navigation-buttons">
    	<?
			$bFirst = true;
			$bPoints = false;
			do
			{
				if ($arResult["nStartPage"] <= 2 || $arResult["nEndPage"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=2)
				{

					if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):
					?>
						<button class="search-navigation-buttons-btn search-navigation-buttons-btn-active"><?=$arResult["nStartPage"]?></button>
					<?
							elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):
					?>
							<a class="search-navigation-buttons-btn" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
					<?
							else:
					?>
							<a class="search-navigation-buttons-btn" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
					<?
					endif;
					$bFirst = false;
					$bPoints = true;
				}
				else
				{
					if ($bPoints)
					{
						?><div class="search-navigation-points">...</div><?
						$bPoints = false;
					}
				}
				$arResult["nStartPage"]++;
			} while($arResult["nStartPage"] <= $arResult["nEndPage"]);

		?>
    </div>
	<div class="search-navigation-text">
		<?if ($bPrevDisabled): ?>
			<p><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.PREV');?></p>
		<?else:?>
			<p>
				<a href="<?=$sPrevHref;?>"><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.PREV');?></a>
			</p>
		<?endif;?>
		<?if ($bNextDisabled): ?>
			<p><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.NEXT');?></p>
		<?else:?>
			<p>
				<a href="<?=$sNextHref;?>"><?=GetMessage('INOBITEC.SEARCH.PAGE.PAGENAVGATION.NEXT');?></a>
			</p>
		<?endif;?>
	</div>
</div>