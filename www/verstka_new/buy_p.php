<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->AddHeadScript('/verstka_new/js/buy_p.js');
$APPLICATION->SetTitle("Просмотрщик");
?>

<div class="buy_p">
    <div class="buy_p-row">
        <div class="buy_p-logo">
            <div class="buy_s-logo-lite">
                <img src="img/svg/INDV-LITE-RGB.svg">
                <p>редакция</p>
                <p>Lite</p>
            </div>
            <div class="buy_s-logo-pro">
                <img src="img/svg/INDV-PRO-RGB.svg">
                <p>редакция</p>
                <p>Pro</p>
            </div>
        </div>
        <div class="buy_p-text">
            <p><b>Инобитек DICOM-Просмотрщик</b> — программное обеспечение для визуализации,
                архивирования и экспорта медицинских изображений формата DICOM, полученных
                с медицинского оборудования различных производителей.</p>
            <ol>
                <li>Устанавливается на все версии популярных ОС: Windows, Mac OS, Linux</li>
                <li>Не предъявляет высокие системные требования</li>
                <li>Предоставляет расширенный функционал для работы в 3D</li>
                <li>Содержит подробное руководство пользователя на русском языке </li>
                <li>Внесён в единый реестр российских программ Минкомсвязи </li>
                <li>Зарегистрирован как медицинское изделие в Росздравнадзоре</li>
                <li>Реализуется в нескольких редакциях и по доступным ценам</li>
            </ol>
            <p>Бесплатный пробный период — 1 месяц.</p>
            <p>Релиз Web-версии Инобитек DICOM-Просмотрщика намечен на вторую половину 2019 года.</p>
            <a href="#">Лицензионное соглашение с конечным пользователем Инобитек DICOM-Просмотрщика</a>
            <a href="#">Регистрационное удостоверение Росздравнадзора</a>
        </div>
    </div>
    <div class="buy_p-product">
        <div class="buy_p-product-logo">
            <img src="img/INDV-LITE-RU-RGB.jpg">
        </div>
        <div class="buy_p-product-text">
            <p class="buy_p-product-text-description">Статичная редакция с ограниченным функционалом</p>
            <p class="buy_p-product-text-prise">8 000 &#8381;</p>
            <div class="buy_p-btn-case">
                <button class="btn-blue btn-blue-active">в корзине</button>
                <span class="buy_p-clear">убрать из корзины</span>
            </div>
        </div>
    </div>
    <div class="buy_p-product">
        <div class="buy_p-product-logo">
            <img src="img/INDV-LITE-RU-RGB.jpg">
        </div>
        <div class="buy_p-product-text">
            <p class="buy_p-product-text-description">Статичная редакция с ограниченным функционалом</p>
            <p class="buy_p-product-text-prise">8 000 &#8381;</p>
            <button class="btn-blue">в корзину</button>
        </div>
    </div>
    <button class="btn-blue btn-blue-gray">+ добавить редакцию LITE</button>
    <div class="buy_p-gray-container buy_p-gray-container-main">
        <div class="buy_p-product-logo">
            <img src="img/PRO-GRAY.png">
        </div>
        <div class="buy_p-product-text">
            <p class="buy_p-product-text-description">Динамично развивающаяся редакция
                (релиз 1 раз в 3 месяца) Расширенные возможности функционала
                Предусмотрены дополнительные модули</p>
            <p class="buy_p-product-text-prise">50 000 &#8381;</p>
            <button class="btn-blue">в корзину</button>
        </div>

    </div>
    <div class="buy_p-gray-container-show">
        <div class="buy_p-gray-container-show-wrapper">
            <p class="buy_p-gray-container-show-subtext">дополнительные Модули для PRO</p>
            <span></span>
        </div>
        <div class="buy_p-gray-container-show-wrapper">
            <p class="buy_p-gray-container-show-subtext">Дополнительные модули для Pro, выбрано: Х</p>
            <p class="buy_p-gray-container-show-showtext" data-show="0">развернуть</p>
        </div>
    </div>
    <div class="buy_p_showed-container">
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль анализа сосудов</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality), для анализа их
                    различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">30 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль коронарографии</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality), для анализа их различных
                    пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">25 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль PET/CT</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality),
                    для анализа их различных пространственных реконструкций
                    (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">20 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container-close">
            <div class="buy_p-gray-container-show-wrapper">
                <p class="buy_p-gray-container-close-showtext" data-close="0">свернуть</p>
            </div>
        </div>
    </div>

    <div class="buy_p-gray-container buy_p-gray-container-main">
        <div class="buy_p-product-logo">
            <img src="img/PRO-GRAY.png">
        </div>
        <div class="buy_p-product-text">
            <p class="buy_p-product-text-description">Динамично развивающаяся редакция
                (релиз 1 раз в 3 месяца) Расширенные возможности функционала
                Предусмотрены дополнительные модули</p>
            <p class="buy_p-product-text-prise">50 000 &#8381;</p>
            <button class="btn-blue">в корзину</button>
        </div>

    </div>
    <div class="buy_p-gray-container-show">
        <div class="buy_p-gray-container-show-wrapper">
            <p class="buy_p-gray-container-show-subtext">дополнительные Модули для PRO</p>
            <span></span>
        </div>
        <div class="buy_p-gray-container-show-wrapper">
            <p class="buy_p-gray-container-show-subtext">Дополнительные модули для Pro, выбрано: Х</p>
            <p class="buy_p-gray-container-show-showtext" data-show="1">развернуть</p>
        </div>
    </div>
    <div class="buy_p_showed-container">
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль анализа сосудов</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality), для анализа их
                    различных пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">30 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль коронарографии</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality), для анализа их различных
                    пространственных реконструкций (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">25 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container buy_p-gray-container-sub">
            <div class="buy_p-product-name">
                <p>Модуль PET/CT</p>
            </div>
            <div class="buy_p-product-text">
                <p class="buy_p-product-text-description">Визуализатор медицинских DICOM-данных,
                    полученных с различного оборудования (modality),
                    для анализа их различных пространственных реконструкций
                    (2D, 3D, Dynamic 3D, MPR, MIP и других).</p>
                <p class="buy_p-product-text-prise">20 000 &#8381;</p>
                <button class="btn-blue">в корзину</button>
            </div>
        </div>
        <div class="buy_p-gray-container-close">
            <div class="buy_p-gray-container-show-wrapper">
                <p class="buy_p-gray-container-close-showtext" data-close="1">свернуть</p>
            </div>
        </div>
    </div>
</div>


<div class="page_buy_p">
	<div class="page_buy_p_pro">
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
					<div class="page_buy_p_total_element_price">40 000<i class="rub"></i> <span>20 000 &#8381 ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
			<div class="page_buy_p_total_element in">
				<div class="page_buy_p_total_element_content">
					<div class="page_buy_p_total_element_time">+3 года <i>-80%</i></div>
					<div class="page_buy_p_total_element_percent">20% от 80 000</div>
					<div class="page_buy_p_total_element_price">48 000<i class="rub"></i> <span>16 000 &#8381 ЗА ГОД</span></div>
					<div class="page_buy_p_total_element_button">
						<div class="page_buy_button btn_n disabled" onclick="to_basket(this);">В Корзину</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="page_buy_p_summ">ИТОГО <span class="buy_p_mobile-new-line">104 000 &#8381</span></div>
	<a href="basket.php" class="page_buy_button to_basket btn_n">Перейти в корзину</a>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
