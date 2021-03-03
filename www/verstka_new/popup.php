<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->AddHeadScript('https://code.jquery.com/jquery-2.1.1.js');
$APPLICATION->AddHeadScript('https://code.jquery.com/ui/1.11.1/jquery-ui.js');
$APPLICATION->AddHeadScript('/verstka_new/js/jquery-ui-slider-pips.js');
$APPLICATION->AddHeadScript('/verstka_new/js/popup.js');
$APPLICATION->SetTitle("Купить");
?>

<section class="range-slider">
    <div class="range-slider-container">
        <div class="slider"></div>
    </div>
</section>

<section class="popup-wrapper">
    <button class="btn-blue" onclick="Popup('writing')">нулевый</button>
    <button class="btn-blue" onclick="Popup('pass-success')">первый</button>
    <button class="btn-blue" onclick="Popup('new-pass-success')">первый</button>
    <button class="btn-blue" onclick="Popup('authorization')">первый</button>
    <button class="btn-blue" onclick="Popup('pass')">второй</button>
    <button class="btn-blue" onclick="Popup('changePass')">пятый</button>
    <button class="btn-blue" onclick="Popup('changePassTwo')">шестой</button>
    <button class="btn-blue" onclick="Popup('basketAlert')">седьмой</button>
    <button class="btn-blue" onclick="Popup('basketAlertTwo')">восьмой</button>
</section>

<!-- Авторизация. На попа который нужно открыть ,на родительский контейнер
     с классом "popup" - вешаем id с любым названием. В место где нужно вызвать
     событие открытия попа - вешаем фукнцию: onclick="Popup('name')" ,где 'name'
     айди которое дали попапу -->

<div class="popup" id="writing">
    <div class="popup-main writing">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">документы к заказу</p>
        <div class="writing-doc">
            <img src="img/svg/writing.svg">
            <a href="#">лицензионный договор-оферты сканы актов по данным
                договорам-офертам сканы актов по данным договорам-офертам</a>
        </div>
        <div class="writing-doc">
            <img src="img/svg/writing.svg">
            <a href="#">лицензионный договор-оферты сканы актов по данным
                договорам-офертам сканы актов по данным договорам-офертам</a>
        </div>
        <div class="writing-doc">
            <img src="img/svg/writing.svg">
            <a href="#">лицензионный договор-оферты сканы актов по данным
                договорам-офертам сканы актов по данным договорам-офертам</a>
        </div>
        <div class="writing-doc">
            <img src="img/svg/writing.svg">
            <a href="#">лицензионный договор-оферты сканы актов по данным
                договорам-офертам сканы актов по данным договорам-офертам</a>
        </div>
    </div>
</div>

<div class="popup" id="new-pass-success">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">новый пароль создан</p>
        <form>
            <p class="pass-success-text">Новый пароль оправлен на<br> указанную вами почту</p>
            <button class="btn-blue authorization-button">войти</button>
        </form>
    </div>
</div>


<div class="popup" id="authorization">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">авторизация</p>
        <form>
            <label class="input error"> <!-- Добавить инпуту класс "error", что бы отметить поле ошибкой -->
                <input placeholder="Логин" type="text">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <label class="input">
                <input placeholder="Пароль" type="password">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <button class="btn-blue authorization-button">авторизоваться</button>
        </form>
        <a href="#">Забыли пароль?</a>
    </div>
</div>

<!-- Востановление пароля -->
<div class="popup" id="pass">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">восстановление пароля</p>
        <form>
            <label class="input">
                <label class="input error">
                    <input placeholder="Ваш email при регистрации" type="email">
                    <div class="error-text">Поле заполнено не верно</div>
                </label>
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <p>Мы отправим вам ссылку на Email<br> для создания нового пароля</p>
            <button class="btn-blue media-button">восстановить пароль</button>
        </form>
        <a href="#">Авторизация</a>
    </div>
</div>

<!-- Изменение пароля -->
<div class="popup" id="changePass">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">изменение пароля</p>
        <form>
            <label class="input">
                <input placeholder="Ваш email при регистрации" type="email">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <p>Мы отправим вам ссылку на Email<br> для создания нового пароля</p>
            <button class="btn-blue media-button">изменить пароль</button>
        </form>
        <a href="#">Авторизация</a>
    </div>
</div>

<!-- Смена пароля (2 поля ввода) -->
<div class="popup" id="changePassTwo">
    <div class="popup-main input-width popup-main-height">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">Новый пароль</p>
        <p class="sub-text sub-text-margin">Минимум 8 символов, буквы и цифры</p>
        <form class="popup-padding">
            <label class="input">
                <input placeholder="Новый пароль" type="password" >
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <label class="input">
                <input placeholder="Повтор пароля" type="password">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <button class="btn-blue media-button">Сменить пароль</button>
        </form>
    </div>
</div>

<!-- Страница авторизации -->
<div class="authorization-block">
    <div class="popup-main">
        <form>
            <label class="input error">
                <input placeholder="Логин" type="text">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <label class="input">
                <input placeholder="Пароль" type="password">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <button class="btn-blue authorization-button">авторизоваться</button>
        </form>
        <a href="#">Забыли пароль?</a>
    </div>
</div>


<!-- Страница востановления пароля -->
<div class="reset-password-block">
    <div class="popup-main">
        <form>
            <label class="input">
                <label class="input">
                    <input placeholder="Ваш email при регистрации" type="email">
                    <div class="error-text">Поле заполнено не верно</div>
                </label>
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <p>Мы отправим вам ссылку на Email<br> для создания нового пароля</p>
            <button class="btn-blue media-button pass-button">восстановить пароль</button>
        </form>
        <a href="#">Авторизация</a>
    </div>
</div>

<!-- Страница изменение пароля -->
<div class="change-password-block">
    <div class="popup-main">
        <form>
            <label class="input">
                <input placeholder="Ваш email при регистрации" type="email">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <p>Мы отправим вам ссылку на Email<br> для создания нового пароля</p>
            <button class="btn-blue media-button">изменить пароль</button>
        </form>
        <a href="#">Авторизация</a>
    </div>
</div>

<!-- Страница смены пароля (2 поля ввода) -->
<div class="change-password-block">
    <div class="popup-main input-width popup-main-height">
        <form class="popup-padding">
            <label class="input">
                <input placeholder="Новый пароль" type="password" >
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <label class="input">
                <input placeholder="Повтор пароля" type="password">
                <div class="error-text">Поле заполнено не верно</div>
            </label>
            <button class="btn-blue media-button">Сменить пароль</button>
        </form>
    </div>
</div>

<!-- Подтверждение о удалении товара -->
<div class="popup" id="basketAlert">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">Очистить корзину?</p>
        <form>
            <p class="pass-success-text">Вы не сможете восстановить <br> список товаров в корзине</p>
            <div class="popup-main_row">
                <button class="btn-blue btn-red authorization-button">ОЧИСТИТЬ</button>
                <button class="btn-blue authorization-button">ОТМЕНА</button>
            </div>
        </form>
    </div>
</div>

<!-- Подтверждение о удалении ОДНОГО товара -->
<div class="popup" id="basketAlertTwo">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p class="authorization">УДАЛЕНИЕ ТОВАРА</p>
        <form>
            <p class="pass-success-text">Вы подтверждаете удаление товара из корзины?</p>
            <div class="popup-main_row">
                <button class="btn-blue btn-red authorization-button">ДА, УДАЛИТЬ</button>
                <button class="btn-blue authorization-button">ОТМЕНА</button>
            </div>
        </form>
    </div>
</div>

<div class="success-block">
    <div class="popup-main">
        <img src="img/svg/checkbox.svg">
        <p class="success">Вы зарегистрированы и успешно авторизовались</p>
        <a href="#" class="success-link">Проверьте почту и перейдите по ссылке в письме о восстановлении пароля.</a>
        <button class="btn-blue media-button">перейти на главную</button>
    </div>
</div>

<div class="subscribe">
    <div class="subscribe__main">
        <p>подпишитесь на рассылку и следите за обновлениями</p>
        <form>
            <div class="subscribe__main-column">
                <label for="subscribe">Email</label>
                <input id="subscribe">
            </div>
            <button type="submit">подписаться</button>
            <div class="subscribe__main-column">
                <div class="verification">
                    <label class="checkbox">
                        <input type="checkbox">
                        <div class="checkbox_button"></div>
                        <span>Я соглашаюсь с <a href="#">Лицензионным договором-офертой</a> и <a href="#">Обработкой персональных данных</a></span>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
