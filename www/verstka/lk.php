<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Личный кабинет");
?>

<div class="page_lk">
	<div class="tab_nav">
		<div class="tab_nav_element active" data-target="1">ЛИЦЕНЗИОННЫЕ КЛЮЧИ</div>
		<div class="tab_nav_element" data-target="2">ЛИЧНЫЕ ДАННЫЕ</div>
		<div class="tab_nav_element" data-target="3">обратная связь</div>
	</div>
	<div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1">
		<div class="lk_title">Ваши лицензионные ключи</div>

		<div class="page_basket_table_status green">Оплачено</div>
		<div class="page_basket_table lk">
			<div class="page_basket_table_titles">
				<div class="page_basket_table_element">
					<div class="page_basket_table_title name">ПРОДУКТ</div>
					<div class="page_basket_table_title time">ДЕЙСТВИТЕЛЕН</div>
					<div class="page_basket_table_title bye"></div>
					<div class="page_basket_table_title button"></div>
				</div>
			</div>
			<div class="page_basket_table_p">
				<div class="page_basket_table_element_title">
					<span>ПРОСМОТРЩИК</span>
					<i></i>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Редакция Pro</div>
					<div class="page_basket_table_element_col time">ДО 12.04.2021</div>
					<div class="page_basket_table_element_col bye"></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Редакция Pro</div>
					<div class="page_basket_table_element_col time">ДО 12.04.2021</div>
					<div class="page_basket_table_element_col bye"></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element_urls">
					<a href="#">лицензионный договор-оферты</a><br/><br/>
 					<a href="#">сканы актов по данным договорам-офертам</a>
				</div>
			</div>
			<div class="page_basket_table_s">
				<div class="page_basket_table_element_title">
					<span>СЕРВЕР</span>
					<i></i>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Подключений: <b>18</b></div>
					<div class="page_basket_table_element_col time"></div>
					<div class="page_basket_table_element_col bye"></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Подписка на обновления</div>
					<div class="page_basket_table_element_col time">ДО 12.04.2021</div>
					<div class="page_basket_table_element_col bye"></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element_urls">
					<a href="#">лицензионный договор-оферты</a><br/><br/>
 					<a href="#">сканы актов по данным договорам-офертам</a>
				</div>
			</div>
		</div>

		<div class="page_basket_table_status red">ОЖИДАЕТ ОПЛАТЫ</div>
		<div class="page_basket_table lk">
			<div class="page_basket_table_titles">
				<div class="page_basket_table_element">
					<div class="page_basket_table_title name">ПРОДУКТ</div>
					<div class="page_basket_table_title time">ДЕЙСТВИТЕЛЕН</div>
					<div class="page_basket_table_title bye"></div>
					<div class="page_basket_table_title button"></div>
				</div>
			</div>
			<div class="page_basket_table_p">
				<div class="page_basket_table_element_title">
					<span>ПРОСМОТРЩИК</span>
					<i></i>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Редакция Pro</div>
					<div class="page_basket_table_element_col time">+1</div>
					<div class="page_basket_table_element_col bye">ОЖИДАЕТ ОПЛАТЫ<span>30 000 РУБ</span></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Редакция Pro</div>
					<div class="page_basket_table_element_col time">+1</div>
					<div class="page_basket_table_element_col bye">ОЖИДАЕТ ОПЛАТЫ<span>30 000 РУБ</span></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
			</div>
			<div class="page_basket_table_s">
				<div class="page_basket_table_element_title">
					<span>СЕРВЕР</span>
					<i></i>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Подключений: <b>18</b></div>
					<div class="page_basket_table_element_col time"></div>
					<div class="page_basket_table_element_col bye">ОЖИДАЕТ ОПЛАТЫ<span>30 000 РУБ</span></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
					</div>
				</div>
				<div class="page_basket_table_element">
					<div class="page_basket_table_element_col name">Подписка на обновления</div>
					<div class="page_basket_table_element_col time">+1</div>
					<div class="page_basket_table_element_col bye">ОЖИДАЕТ ОПЛАТЫ<span>30 000 РУБ</span></div>
					<div class="page_basket_table_element_col button">
						<a href="javascript:void(0);" class="btn_n red">скачать PDF счёт</a>
						<span>действителен 5 банковских дней</span>
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

	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
