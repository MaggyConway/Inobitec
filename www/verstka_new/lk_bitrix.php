<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka_new/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->AddHeadScript('/verstka_new/js/keys.js');
$APPLICATION->SetTitle("Личный кабинет");
?>
<div class="page_lk">
	<div class="tab_nav">
		<div class="tab_nav_element active" data-target="1">ЛИЦЕНЗИОННЫЕ КЛЮЧИ</div>
		<div class="tab_nav_element" data-target="2">ЛИЧНЫЕ ДАННЫЕ</div>
		<div class="tab_nav_element" data-target="3">обратная связь</div>
	</div>

	<div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1">
        <div class="keys">
            <h2>Ваши лицензионные ключи</h2>
            <div class="keys-main">
                <div class="keys-main__order">
                    <p class="keys-main__order-number">Заказ №12345</p>
                    <div class="keys-main__order-container">
                        <div class="keys-main__order-container-prise">
                            <p>сумма покупки</p>
                            <p>123 456 &#8381;</p>
                        </div>
                        <div class="keys-main__order-container-status">
                            <p>статус заказа</p>
                            <p>оплачен</p>
                        </div>
                        <div class="keys-main__order-container-pricePDF">
                            <button class="btn-red-disable">скачать PDF счёт</button>
                            <p>действителен 5 банковских дней</p>
                        </div>
                        <div class="keys-main__order-container-additionalText">
                            <a href="#">лицензионный договор-оферты</a>
                            <a href="#">сканы актов по данным договорам-офертам</a>
                        </div>
                    </div>
                </div>
                <div class="keys-main__description">
                    <p class="keys-main__description-show"><span>подробнее о заказе</span><img src="img/arrow-up.png"></p>
                    <div class="keys-main__description-product">
                        <div class="keys-main__description-product-name">
                            <p class="text-showed">ПРОДУКТ</p>
                            <p>СЕРВЕР</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p>Лицензионный ключ</p>
                            <p>1078 6507 1670 3230 6327</p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p>дата покупки</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p>дата активации</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подключений: <span>18</span></p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">180 000 <span>&#8381;</span></p>
                            </div>
                        </div>

                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подписка на обновления</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">162 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="keys-main__description-product">
                        <div class="keys-main__description-product-name">
                            <p>ПРОДУКТ</p>
                            <p>ПРОСМОТРЩИК</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p>Лицензионный ключ</p>
                            <p>1078 6507 1670 3230 6327</p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p>дата покупки</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p>дата активации</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Редакция Pro</p>
                            </div>
                            <div
                                    class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer no-data">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data"></p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>

                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="keys-main__description-product mobile-border">
                        <div class="keys-main__description-product-name">
                            <p>ПРОДУКТ</p>
                            <p>ПРОСМОТРЩИК</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p>Лицензионный ключ</p>
                            <p>1078 6507 1670 3230 6327</p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p>дата покупки</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p>дата активации</p>
                            <p>14.04.2019 11:25</p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подписка на обновления</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">162 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="keys-main__order">
                    <p class="keys-main__order-number">Заказ №12345</p>
                    <div class="keys-main__order-container">
                        <div class="keys-main__order-container-prise">
                            <p>сумма покупки</p>
                            <p>123 456 &#8381;</p>
                        </div>
                        <div class="keys-main__order-container-status">
                            <p>статус заказа</p>
                            <p class="text-error">ОЖИДАЕТ ОПЛАТЫ</p>
                        </div>
                        <div class="keys-main__order-container-pricePDF">
                            <button>перейти к оплате</button>
                            <p>действителен 5 банковских дней</p>
                        </div>
                        <div class="keys-main__order-container-additionalText">
                            <a href="#">лицензионный договор-оферты</a>
                        </div>
                    </div>
                </div>
                <div class="keys-main__description">
                    <p class="keys-main__description-show"><span>подробнее о заказе</span><img src="img/arrow-up.png"></p>
                    <div class="keys-main__description-product not-paid">
                        <div class="keys-main__description-product-name">
                            <p class="text-showed">ПРОДУКТ</p>
                            <p>СЕРВЕР</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p></p>
                            <p></p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подключений: <span>18</span></p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">180 000 <span>&#8381;</span></p>
                            </div>
                        </div>

                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подписка на обновления</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">162 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="keys-main__description-product not-paid">
                        <div class="keys-main__description-product-name">
                            <p>ПРОДУКТ</p>
                            <p>ПРОСМОТРЩИК</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p></p>
                            <p></p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Подключений: <span>18</span></p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">14.04.2019 11:25</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">180 000 <span>&#8381;</span></p>
                            </div>
                        </div>

                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Модуль анализа сосудов</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state">ДЕЙСТВИТЕЛЕН</p>
                                <p class="keys-main__description-showCase-row-wrapper-data">до 12.04.2021</p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">50 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="keys-main__description-product not-paid">
                        <div class="keys-main__description-product-name">
                            <p>ПРОДУКТ</p>
                            <p>ПРОСМОТРЩИК LITE</p>
                        </div>
                        <div class="keys-main__description-product-key">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-data">
                            <p></p>
                            <p></p>
                        </div>
                        <div class="keys-main__description-product-active">
                            <p></p>
                            <p></p>
                        </div>
                    </div>
                    <div class="keys-main__description-showCase last-keys-padding">
                        <div class="keys-main__description-showCase-row">
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-name">
                                <p class="keys-main__description-showCase-row-wrapper-connects">Редакция Lite</p>
                            </div>
                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-subContainer">
                                <p class="keys-main__description-showCase-row-wrapper-state"></p>
                                <p class="keys-main__description-showCase-row-wrapper-data"></p>
                            </div>

                            <div class="keys-main__description-showCase-row-wrapper keys-main__description-showCase-row-wrapper-priceContainer
                            reset-margin">
                                <p class="keys-main__description-showCase-row-wrapper-priceText">стоимость</p>
                                <p class="keys-main__description-showCase-row-wrapper-price">8 000 <span>&#8381;</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="page_basket_personal_info_type_tab" data-target="2">
		<div class="lk_title">ЛИЧНЫЕ ДАННЫЕ</div>


		<div class="page_basket_personal_info">
			<form>
				<div class="page_basket_personal_info_title"><span>ИНФОРМАЦИЯ О ЛИЦЕНЗИАТЕ</span><i></i></div>
				<div class="page_basket_personal_info_type js-tab_inputs">
					<label class="radio_tab">
						<input type="radio" name="type" value="1" checked="checked" disabled="disabled" />
						<div class="radio_tab_button"></div>
						<div class="radio_tab_text">юридическое лицо</div>
					</label>
				</div>

				<div class="form_table">
					<div class="form_table_col">
						<label class="input have_value">
							<span>Название компании</span>
							<div class="val">Название компании</div>
						</label>
					</div>

					<div class="clear"></div>

					<div class="form_table_col">
						<label class="input valid have_value">
							<span>ОГРН</span>
							<div class="val">1056405010100</div>
						</label>
						<label class="input valid have_value">
							<span>ИНН</span>
							<div class="val">6450606508</div>
						</label>
						<label class="input have_value">
							<span>КПП</span>
							<div class="val">645001001</div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input have_value">
							<span>Расчётный счёт</span>
							<div class="val">1056405010100</div>
						</label>
						<label class="input have_value">
							<span>Кор.счёт</span>
							<div class="val">1056405010100</div>
						</label>
						<label class="input have_value">
							<span>БИК или банк</span>
							<div class="val">1056405010100</div>
						</label>
					</div>

					<div class="clear"></div>

					<div class="form_table_no_col">
						<label class="input have_value">
							<span>Должность руководителя</span>
							<div class="val">Должность руководителя</div>
						</label>
						<div class="radio have_value">
							<span>Действует на основании</span>
							<div class="radio_box">
								<label class="in_radio">
									<input type="radio" name="osn" checked="checked" disabled="disabled" />
									<div class="in_radio_button"></div>
									<div class="in_radio_text">Устава</div>
								</label>
							</div>
						</div>
						<label class="input have_value">
							<span>ФИО директора</span>
							<div class="val">ФИО директора</div>
						</label>
						<label class="input have_value">
							<span>Доверенность</span>
							<div class="val">Доверенность</div>
						</label>
						<label class="input have_value">
							<span>Юридический адрес</span>
							<div class="val">Юридический адрес</div>
						</label>
						<label class="input have_value">
							<span>Почтовый адрес</span>
							<div class="val">Почтовый адрес</div>
						</label>

						<div class="clear"></div>
					</div>

					<hr/>

					<div class="form_table_col">
						<label class="input have_value">
							<span>Email</span>
							<div class="val">Email</div>
						</label>
						<label class="input have_value">
							<span>Телефон</span>
							<div class="val">Телефон</div>
						</label>
					</div>

					<div class="button_box">
						<a href="#" class="btn_n big">Изменить пароль</a>
					</div>
				</div>
			</form>
		</div>
	</div>

    <div class="page_basket_personal_info_type_tab" data-target="3">
        <div class="contact-us">
            <div class="contact-us__form contact-us__form-active">
                <h4>свяжитесь с нами</h4>
                <p>имя</p>
                <p>Варламов Алексей Николаевич</p>
                <p>Телефон</p>
                <p>+7 (900) 000 00 00</p>
                <p>Email</p>
                <p>market@inobitec.com</p>
                <label class="input error"> <!-- Добавить инпуту класс "error", что бы отметить поле ошибкой -->
                    <span>Текст сообщения</span>
                    <textarea id="message"></textarea>
                    <div class="error-text">Поле заполнено не верно</div>
                </label>
                <div class="verification">
                    <input type="checkbox">
                    <span>Я соглашаюсь с </span><a href="#">Лицензионным договором-офертой</a><span> и </span><a href="#">Обработкой персональных данных</a>
                    <div class="verification-error">необходимо согласиться с этим пунктом</div>
                </div>
                <button>отправить</button>
            </div>
            <!-- для активации блока успешной отправки убрать"contact-us__form-active"-->
            <div class="contact-us__form contact-us__form-success">
                <h4>свяжитесь с нами</h4>
                <img src="img/svg/letter.svg">
                <p>спасибо,что<br> связались с нами</p>
                <p>Мы ответим вам в ближайшее время</p>
            </div>
            <!-- для активации блока успешной отправки добавить "contact-us__form-success-active"-->
            <div class="contact-us__contacts">
                <a href="tel: +79204056743" class="contact-us__contacts-phone">
                    +79204056743
                </a>
                <a href="mailto: market@inobitec.com" class="contact-us__contacts-mail">
                    market@inobitec.com
                </a>
                <p class="contact-us__contacts-address">
                    394006, Россия, г. Воронеж, ул. Бахметьева, 2Б, 5 этаж, офисы 500-511
                </p>
            </div>
        </div>
    </div>

</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>