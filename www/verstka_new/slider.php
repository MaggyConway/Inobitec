<!DOCTYPE html>
<html lang="ru-RU">
<head>
    <title>slider</title>
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            padding: 0;
            margin: 0; }
    </style>
</head>
<body>
<div class="main-slider">
    <img class="main-slider_bg-logo" src="img/slider-images/slider-logo-bg.svg">
    <div class="main-slider_content main-slider_content-active"> <!-- тут стиль удалить, добавить класс main-slider_content-active(только для первого слайда!) -->
        <!-- тут должен быть div с классом main-slider_content-text-wrapper.
             внутри него должны быть дивы text,button,description.
             Так в каждом слайде.
             Должна получиться структура слайда вида(вложенность(по именам классов)):

             main-slider_content
                main-slider_content-text-wrapper
                    main-slider_content-text
                    main-slider_content-button
                    main-slider_content-description
                main-slider_content-image
         -->

        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p>Зарегистрированное медицинское изделие</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p> Скачивайте актуальную версию <br>Инобитек DICOM-Просмотрщика<img src="img/slider-images/INDV-LITE-RGB.svg"> Lite и <img src="img/slider-images/INDV-PRO-RGB.svg">Pro<br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/1.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p class="text-gradient">возможность</p>
                <p>Активации дополнительных модулей</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p>Доступна только в редакции <img src="img/slider-images/INDV-PRO-RGB.svg"> Pro</p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/2.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p class="text-gradient">новый модуль</p>
                <p>анализа сосудов</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p>Скачивайте актуальную версию <br>Инобитек DICOM-Просмотрщика <img src="img/slider-images/INDV-PRO-RGB.svg"> Pro<br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/3.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p class="text-gradient">новый модуль</p>
                <p>анализа коронарных артерий</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p>Скачивайте актуальную версию <br>Инобитек DICOM-Просмотрщика <img src="img/slider-images/INDV-PRO-RGB.svg"> Pro<br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/4.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p class="text-gradient">новый модуль</p>
                <p>анализа ПЭТ-КТ</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p>Скачивайте актуальную версию <br>Инобитек DICOM-Просмотрщика <img src="img/slider-images/INDV-PRO-RGB.svg"> Pro<br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/5.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p>Кроссплатформенный DICOM-Сервер (PACS)</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p>Скачивайте актуальную версию <br><img src="img/slider-images/INDV-SERVER-RGB.svg">DICOM-Сервера (PACS) <br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/6.png">
        </div>
    </div >
    <div class="main-slider_content">
        <div class="main-slider_content-text-wrapper">
            <div class="main-slider_content-text">
                <p>Российское программное обеспечение</p>
            </div>
            <div class="main-slider_content-button">
                <button>подробнее</button>
            </div>
            <div class="main-slider_content-description">
                <p> Скачивайте актуальную версию <br>Инобитек DICOM-Просмотрщика<img src="img/slider-images/INDV-LITE-RGB.svg"> Lite и <img src="img/slider-images/INDV-PRO-RGB.svg">Pro<br> в <a href="#"> Центре загрузки</a></p>
            </div>
        </div>
        <div class="main-slider_content-image">
            <img src="img/slider-images/slider-images-main/7.png">
        </div>
    </div >


    <div class="main-slider_navigation">
        <div class="main-slider_navigation-counter">
            <div class="main-slider_navigation-counter-current"><p>1</p></div>
            <div class="main-slider_navigation-counter-max"><p>7</p></div>
            <span class="main-slider_navigation-counter-line"></span>
            <span class="main-slider_navigation-counter-line-active"></span>
        </div>
        <div class="main-slider_navigation-arrows">
            <div class="main-slider_navigation-arrows-left">
                <svg width="15" height="6" viewBox="0 0 15 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5102 1L2 5H15" class="svg-arrow-left" stroke="#4272A9"/>
                </svg>
                <span></span>
            </div>
            <div class="main-slider_navigation-arrows-right">
                <svg width="16" height="7" viewBox="0 0 16 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.14286 6L14 1L5.01691e-07 1" class="svg-arrow-right" stroke="#4272A9"/>
                </svg>
                <span></span>
            </div>
        </div>
    </div>
</div>
</body>
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
<script src="js/slider.js"></script>
</html>
