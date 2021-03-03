<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Просмотрщик");
?>
<div class="page_buy_p">
	<div class="page_buy_p_description">
		<div class="page_buy_p_description_img">
			<img src="/upload/iblock/7d4/sol_ico_1.png" />
		</div>
		<div class="page_buy_p_description_text">
			<div class="page_buy_p_description_text_title">ИНОБИТЕК<br/>DICOM-ПРОСМОТРЩИК</div>
			<p>Визуализатор медицинских DICOM-данных, полученных с различного оборудования (modality), для анализа их различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других). «Просмотрщик» устанавливается на диагностических рабочих станциях, как правило, это компьютер с подключенным к нему медицинским оборудованием (например, томографом или УЗИ-аппаратом) и может интегрироваться с PACS (системой архивации и передачи данных). В настоящее время Инобитек DICOM-Просмотрщик доступен только в десктопной версии, Web-версия продукта в стадии активной разработки.</p>
			<a href="">Лицензионное соглашение с конечным пользователем Инобитек DICOM-Просмотрщика</a>
			<a href="" class="img_href"><img src="img/svg/href.svg" width="10" height="10" /></a><br/>
			<a href="">Регистрационное удостоверение Росздравнадзора</a>
			<a href="" class="img_href"><img src="img/svg/href.svg" width="10" height="10" /></a>
		</div>
	</div>
	<div class="page_buy_p_lite">
		<div class="page_buy_p_lite_elements">
			<div class="page_buy_p_lite_element in in_basket"><!--класс .in отвечает ТОЛЬКО за изначальный показ элемента. Выводить анимацию появления лучше всего делать через jQuery функцию ".toggle()"-->
				<div class="page_buy_p_lite_element_content">
					<div class="page_buy_p_lite_element_name">РЕДАКЦИЯ<span>LITE</span></div>
					<div class="page_buy_p_lite_element_description">Статичная редакция с ограниченным функционалом</div>
					<div class="page_buy_p_lite_element_price rub">8 000</div>
					<div class="page_buy_p_lite_element_button">
						<div class="page_buy_button btn_n solid" onclick="to_basket(this);">В Корзине</div>
						<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_lite_element in">
				<div class="page_buy_p_lite_element_content">
					<div class="page_buy_p_lite_element_name">РЕДАКЦИЯ<span>LITE</span></div>
					<div class="page_buy_p_lite_element_description">Статичная редакция с ограниченным функционалом</div>
					<div class="page_buy_p_lite_element_price rub">8 000</div>
					<div class="page_buy_p_lite_element_button">
						<div class="page_buy_button btn_n" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page_buy_p_lite_element in left">
			<div class="page_buy_p_lite_element_content">
				<div class="page_buy_button btn_n left disabled js-add_lite">+ добавить редакцию LITE</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_pro">
		<div class="page_buy_p_pro_elements">
			<div class="page_buy_p_pro_element in in_basket">
				<div class="page_buy_p_pro_element_content">
					<div class="page_buy_p_pro_element_name">РЕДАКЦИЯ<span>PRO</span></div>
					<div class="page_buy_p_pro_element_description">
						Динамично развивающаяся редакция (релиз 1 раз в 3 месяца)<br/>
						Расширенные возможности функционала<br/>
						Предусмотрены дополнительные модули
					</div>
					<div class="page_buy_p_pro_element_price rub">50 000</div>
					<div class="page_buy_p_pro_element_button">
						<div class="page_buy_button btn_n solid" onclick="to_basket(this);">В Корзине</div>
						<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_pro_element in dop_PO_text"><div class="page_buy_p_lite_element_content"><span>ДОПОЛНИТЕЛЬНЫЕ Модули для PRO</span><i></i></div></div>
			<div class="page_buy_p_pro_element in in_basket">
				<div class="page_buy_p_pro_element_content">
					<div class="page_buy_p_pro_element_name"><i>Модуль анализа сосудов</i></div>
					<div class="page_buy_p_pro_element_description">
						Визуализатор медицинских DICOM-данных, полученных с различного оборудования (modality), для анализа их различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).
					</div>
					<div class="page_buy_p_pro_element_price rub">30 000</div>
					<div class="page_buy_p_pro_element_button">
						<div class="page_buy_button btn_n solid" onclick="to_basket(this);">В Корзине</div>
						<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_pro_element in">
				<div class="page_buy_p_pro_element_content">
					<div class="page_buy_p_pro_element_name"><i>Модуль коронарографии</i></div>
					<div class="page_buy_p_pro_element_description">
						Визуализатор медицинских DICOM-данных, полученных с различного оборудования (modality), для анализа их различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).
					</div>
					<div class="page_buy_p_pro_element_price rub">25 000</div>
					<div class="page_buy_p_pro_element_button">
						<div class="page_buy_button btn_n" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
			<div class="page_buy_p_pro_element in">
				<div class="page_buy_p_pro_element_content">
					<div class="page_buy_p_pro_element_name"><i>Модуль PET/CT</i></div>
					<div class="page_buy_p_pro_element_description">
						Визуализатор медицинских DICOM-данных, полученных с различного оборудования (modality), для анализа их различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).
					</div>
					<div class="page_buy_p_pro_element_price rub">20 000</div>
					<div class="page_buy_p_pro_element_button">
						<div class="page_buy_button btn_n" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
		<div class="page_buy_p_pro_element in left">
			<div class="page_buy_p_pro_element_content">
				<div class="page_buy_button btn_n left disabled js-add_pro">+ добавить редакцию PRO</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_total">
		<div class="page_buy_p_total_elements">
			<div class="page_buy_p_total_element in in_basket">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_name"></div>
					<div class="page_buy_p_total_element_description">
					</div>
					<div class="page_buy_p_total_element_price">80 000<i class="rub"></i> <span>1 год обновлений включен в стоимость</span></div>
					<div class="page_buy_p_total_element_button">
					</div>
				</div>
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
					<div class="page_buy_p_total_element_time">+1 год <i>-70%</i></div>
					<div class="page_buy_p_total_element_percent">30% от 80 000</div>
					<div class="page_buy_p_total_element_price">24 000<i class="rub"></i></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n solid" onclick="to_basket(this);">В Корзине</div>
						<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+2 год <i>-75%</i></div>
					<div class="page_buy_p_total_element_percent">25% от 80 000</div>
					<div class="page_buy_p_total_element_price">40 000<i class="rub"></i> <span>20 000 РУБ ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+3 года <i>-80%</i></div>
					<div class="page_buy_p_total_element_percent">20% от 80 000</div>
					<div class="page_buy_p_total_element_price">48 000<i class="rub"></i> <span>16 000 РУБ ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_summ">ИТОГО <span>104 000</span> руб</div>
	<a href="basket.php" class="page_buy_button to_basket btn_n">Перейти в корзину</a>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
