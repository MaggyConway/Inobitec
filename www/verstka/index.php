<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Купить");
?>
<div class="page_buy">
	<div class="page_buy_element">
		<div class="page_buy_element_img">
			<img src="/upload/iblock/7d4/sol_ico_1.png" />
		</div>
		<div class="page_buy_element_text">
			<div class="page_buy_element_text_company">ИНОБИТЕК</div>
			<div class="page_buy_element_text_name">DICOM <span>ПРОСМОТРЩИК</span></div>
			<p>Инобитек DICOM-Просмотрщик — визуализатор DICOM-данных, полученных с медицинского оборудования (modality), для анализа их различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, в том числе MIP и других).</p>
			<a href="buy_p.php" class="page_buy_button btn_n">Купить</a>
		</div>
	</div>
	<div class="page_buy_element">
		<div class="page_buy_element_img">
			<img src="/upload/iblock/9c5/sol_ico_3.png" />
		</div>
		<div class="page_buy_element_text">
			<div class="page_buy_element_text_company">ИНОБИТЕК</div>
			<div class="page_buy_element_text_name">DICOM <span>СЕРВЕР</span></div>
			<p>Инобитек DICOM-Сервер (PACS) предназначен для архивирования, оперативного доступа, передачи изображений (исследований), полученных с различного DICOM-оборудования, установленного в учреждении.</p>
			<a href="buy_s.php" class="page_buy_button btn_n">Купить</a>
		</div>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
