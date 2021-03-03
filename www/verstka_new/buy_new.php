<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Купить");
?>

<div class="page_buy">
    <div class="buy-row">
        <a href="#">DICOM ПРОСМОТРЩИК</a>
        <div class="buy-row-logo">
            <div>
                <a href="#"><img src="img/INDV-LITE-RU-RGB.jpg"></a>
            </div>
            <div>
                <a href="#"><img src="img/INDV-PRO-RU-RGB.jpg"></a>
            </div>
        </div>
        <p>Инобитек DICOM-Просмотрщик — программное обеспечение для визуализации,
            архивирования и экспорта медицинских изображений формата DICOM, полученных
            с медицинского оборудования различных производителей.</p>
        <button class="btn-blue">купить</button>
    </div>
    <div class="buy-row">
        <a href="#">DICOM СЕРВЕР</a>
        <div class="buy-row-logo">
            <div>
                <a href="#"><img src="img/INDS-RU-RGB.jpg"></a>
            </div>
        </div>
        <p>Инобитек DICOM-Сервер (PACS) — программное обеспечение для передачи,
            архивирования и оперативного доступа к медицинским изображениям (исследованиям),
            полученным с медицинского оборудования различных производителей.</p>
        <button class="btn-blue">купить</button>
    </div>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
