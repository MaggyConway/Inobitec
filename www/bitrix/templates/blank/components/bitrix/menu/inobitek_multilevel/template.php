<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>



<?if (!empty($arResult)):?>
<ul>

<?
$previousLevel = 0;
foreach($arResult as $arItem):?>

	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

			<li <?if ($arItem["SELECTED"]){?>class="active"<?}?>>
				<a href="#" class="ext"></a>
				<a href="<?=$arItem["LINK"]?>" class="section">
					
					<?=$arItem["TEXT"]?>
				</a>
				<ul>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<li <?if ($arItem["SELECTED"]):?>class="active"<?endif?>><a href="<?=$arItem["LINK"]?>" class="item"><?=$arItem["TEXT"]?></a></li>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<?endif?>