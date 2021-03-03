<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="tab_nav">
	<?$num = 0;?>
	<?foreach ($arResult as $aMenuLink): ?>
		<?$num++;?>
		<?$link = $aMenuLink['LINK'];?>
		<div class="tab_nav_element <?if ($aMenuLink['SELECTED']): ?>active<?endif;?> link" data-target="<?=$num;?>" onclick="window.location.href='<?=$link;?>';">
			<?=$aMenuLink['TEXT'];?>
		</div>
	<?endforeach;?>		
</div>