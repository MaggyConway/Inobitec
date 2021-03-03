<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Сервер");
?>
<div class="page_buy_p">
	<div class="page_buy_p_description">
		<div class="page_buy_p_description_img">
			<img src="/upload/iblock/9c5/sol_ico_3.png" />
		</div>
		<div class="page_buy_p_description_text">
			<div class="page_buy_p_description_text_title">ИНОБИТЕК<br/> DICOM-СЕРВЕР</div>
			<p>Программный DICOM-Сервер (PACS) предназначен для архивирования, передачи и оперативного доступа к изображениям (исследованиям), полученным с различного DICOM-оборудования, установленного в учреждении.</p>
			<p>Для получения сборки Инобитек DICOM-Сервер (PACS) для вашего дистрибутива Linux или с поддержкой другой СУБД отправьте соответствующую заявку на <a href="mailto:market@inobitec.com">market@inobitec.com</a>.</p>
		</div>
	</div>

	<div class="page_buy_p_counter">
		<div class="page_buy_p_counter_titles">
			<div class="page_buy_p_counter_element count">КОЛИЧЕСТВО ПОДКЛЮЧЕНИЙ</div>
			<div class="page_buy_p_counter_element price">СТОИМОСТЬ</div>
			<div class="page_buy_p_counter_element buy"></div>
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
				180 000 РУБ <span>1 год обновлений включен в стоимость</span>
			</div>
			<div class="page_buy_p_counter_element buy">
				<div class="page_buy_button btn_n solid" onclick="to_basket(this,true);">В Корзине</div>
				<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this,true);">убрать из корзины</a>
			</div>
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
					<div class="page_buy_p_total_element_price">108 000<i class="rub"></i> <span>54 000 РУБ ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+3 года</div>
					<div class="page_buy_p_total_element_percent">20% от 180 000</div>
					<div class="page_buy_p_total_element_price">152 000<i class="rub"></i> <span>54 000 РУБ ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_summ">ИТОГО <span>342 000</span> руб</div>
	<a href="basket.php" class="page_buy_button to_basket btn_n">Перейти в корзину</a>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
