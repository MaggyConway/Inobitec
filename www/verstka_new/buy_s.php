<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Сервер");
?>

<!-- Скрипты для маски + попап для информации в блоке "Код продукта" -->
<script src="js/jquery.mask.js"></script>
<script>
    $(document).ready(function(){
        $('.page_buy-key-mask').mask('0000-0000-0000-0000');
    });
</script>
<div class="popup" id="page_buy_p-info">
    <div class="popup-main">
        <div class="close">
            <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
            </svg>
        </div>
        <p>Для того, чтобы выдать Вам Лицензионный ключ на 2 подключения для Сервера,
            необходимо получить от Вас Код продукта.<br><br> Код продукта для Сервера можно узнать
            следующим образом. В папке с установленным PACS-сервером находится папка
            “logs”, в файл лога при каждом запуске сервера записывается Код продукта в
            формате: [ INFO ] Product key: 6353-8418-5025-8201<br><br> (Примечание: Код продукта,
            указанный выше, просто пример)</p>
    </div>
</div>
<!-- Скрипты для маски + попап для информации в блоке "Код продукта" -->

<div class="page_buy_p">
    <div class="buy_s">
        <div class="buy_s-row">
            <div class="buy_s-logo">
                <div class="buy_s-logo-container">
                    <img src="img/svg/INDV-SERVER-RGB.svg">
                    <p>сервер</p>
                </div>
            </div>
            <div class="buy_s-description">
                <p>
                    <b>Инобитек DICOM-Сервер (PACS)</b> — программное обеспечение для передачи,
                    архивирования и оперативного доступа к медицинским изображениям (исследованиям),
                    полученным с медицинского оборудования различных производителей.<br><br> Для получения
                    сборки Инобитек DICOM-Сервер (PACS) для вашего дистрибутива Linux или с поддержкой
                    другой СУБД отправьте соответствующую заявку на market@inobitec.com
                </p>
            </div>
        </div>
        <div class="buy_s-row">
            <div class="buy_s-logo">
                <img src="img/INDS-RU-RGB.jpg">
            </div>
        </div>
    </div>

	<div class="page_buy_p_counter">
		<div class="page_buy_p_counter_titles">
			<div class="page_buy_p_counter_element count">КОЛИЧЕСТВО ПОДКЛЮЧЕНИЙ</div>
			<div class="page_buy_p_counter_element price">СТОИМОСТЬ</div>
            <!-- Обновленный блок -->
            <div class="page_buy_p_counter_element buy">Код продукта</div>
            <!-- Обновленный блок -->
		</div>
		<div class="page_buy_p_counter_elements in_basket">
			<div class="page_buy_p_counter_element count">
				<div class="title">КОЛИЧЕСТВО ПОДКЛЮЧЕНИЙ</div>
				<div class="input_range">
					<div class="val_mrg">
						<div class="val"></div>
					</div>
					<input type="range" min="1" max="50" value="1" />
					<div class="vals">
						<div class="min"></div>
						<div class="max"></div>
					</div>
				</div>
				<p>Для приобретения лицензии, предусматривающей свыше 50 подключений к Серверу необходимо обратиться в Отдел продаж по e-mail: <a href="mailto:market@inobitec.com">market@inobitec.com</a></p>
			</div>
			<div class="page_buy_p_counter_element price">
				<div class="title">СТОИМОСТЬ</div>
				180 000 &#8381 <span>1 год обновлений включен в стоимость</span>
			</div>
            <!-- Обновленный блок -->
			<div class="page_buy_p_counter_element buy">
                <p class="page_buy-mobile-title">Код продукта</p>
                <input class="page_buy-key page_buy-key-mask" placeholder="1111-2222-3333-4444">
                <div class="page_buy-info" onclick="Popup('page_buy_p-info')" title="подробнее">?</div>
				<div class="page_buy_button btn_n solid" onclick="to_basket(this,true);">В Корзине</div>
				<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this,true);">убрать из корзины</a>
			</div>
            <!-- Обновленный блок -->
		</div>
	</div>

	<div class="page_buy_p_updates">
		<div class="page_buy_p_updates_title">
			ДОПОЛНИТЕЛЬНАЯ ГОДОВАЯ ПОДПИСКА НА ОБНОВЛЕНИЯ
		</div>
		<div class="page_buy_p_updates_subtitle">
			1 год обновлений уже включен в стоимость лицензии.
		</div>
		<div class="page_buy_p_updates_elements">
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time"><span>Бесплатные обновления</span></div>
					<div class="page_buy_p_total_element_percent"><span>% от стоимости лицензии</span></div>
					<div class="page_buy_p_total_element_price"><span>стоимость подписки на обновления</span></div>
					<div class="page_buy_p_total_element_button">
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in in_basket">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+1 год</div>
					<div class="page_buy_p_total_element_percent">30% от 180 000</div>
					<div class="page_buy_p_total_element_price">54 000<i class="rub"></i></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n solid" onclick="to_basket(this);">В Корзине</div>
						<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+2 год</div>
					<div class="page_buy_p_total_element_percent">25% от 180 000</div>
					<div class="page_buy_p_total_element_price">108 000<i class="rub"></i> <span>54 000 &#8381 ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+3 года</div>
					<div class="page_buy_p_total_element_percent">20% от 180 000</div>
					<div class="page_buy_p_total_element_price">152 000<i class="rub"></i> <span>54 000 &#8381 ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_summ">ИТОГО<br> <span>342 000</span> &#8381</div>
	<a href="basket.php" class="page_buy_button to_basket btn_n">Перейти в корзину</a>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
