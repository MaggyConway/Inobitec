<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Поиск");
?>

    <div class="search">
        <img class="search-main-image" src="img/magnifier.svg">
        <form class="search-form">
            <input class="search-form-input" title="поиск" placeholder="Поиск по сайту">
            <button class="search-form-button">найти</button>
        </form>
    </div>

    <div class="search result">
        <form class="search-form">
            <img src="img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="поиск" placeholder="Поиск по сайту">
            <button class="search-form-button">найти</button>
        </form>
        <div class="search-text">
            <p class="search-text-main">РЕЗУЛЬТАТЫ ПОИСКА</p>
            <p class="search-text-sub">Пустой поисковый запрос (запрос не содержит букв или цифр).</p>
        </div>
    </div>

    <div class="search result">
        <form class="search-form">
            <img src="img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="поиск" placeholder="Поиск по сайту">
            <button class="search-form-button">найти</button>
        </form>
        <div class="search-text">
            <p class="search-text-main">РЕЗУЛЬТАТЫ ПОИСКА</p>
            <p class="search-text-no-search">ПО ЗАПРОСУ: <span class="search-text-no-search-result">ккк</span></p>
            <p class="search-text-sub">К сожалению, по вашему запросу ничего не найдено. Измените запрос и попробуйте снова.</p>
        </div>
    </div>

    <div class="search result">
        <form class="search-form">
            <img src="img/magnifier.svg" class="result-image">
            <input class="search-form-input" title="поиск" placeholder="Поиск по сайту">
            <button class="search-form-button">найти</button>
        </form>
        <div class="search-text">
            <p class="search-text-main">РЕЗУЛЬТАТЫ ПОИСКА</p>
            <p class="search-text-no-search">ПО ЗАПРОСУ: <span class="search-text-no-search-result">лицензия</span></p>
        </div>
        <div class="search-result">
            <a href="#" class="search-result-title">ПРОСМОТРЩИК</a>
            <p class="search-result-text">Инобитек DICOM-Просмотрщик — визуализатор DICOM-данных, полученных
                с медицинского оборудования (modality), для анализа их различных пространственных реконструкций
                (2D, 3D, Dynamic 3D, MPR, в том числе MIP и других).</p>
        </div>
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
                <p class="not-active" onclick="return false;">предыдущая</p>
                <p>следующая</p>
            </div>
        </div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>