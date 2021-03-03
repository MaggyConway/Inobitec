<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetAdditionalCSS("/verstka/css/main.css");
$APPLICATION->AddHeadScript('/verstka/js/bye_scripts.js');
$APPLICATION->SetTitle("Корзина");
?>
<div class="page_basket">
	<div class="page_basket_table">
		<div class="page_basket_table_titles">
			<div class="page_basket_table_element">
				<div class="page_basket_table_title name">ПРОДУКТ</div>
				<div class="page_basket_table_title count"></div>
				<div class="page_basket_table_title price">стоимость</div>
				<div class="page_basket_table_title delete"></div>
			</div>
		</div>
		<div class="page_basket_table_p">
			<div class="page_basket_table_element_title">
				<span>ПРОСМОТРЩИК</span>
				<a href="buy_p.php">изменить выбор</a>
				<i></i>
			</div>
			<div class="page_basket_table_element">
				<div class="page_basket_table_element_col name">Редакция Pro</div>
				<div class="page_basket_table_element_col count">1 шт</div>
				<div class="page_basket_table_element_col price">50 000 РУБ</div>
				<div class="page_basket_table_element_col delete">
					<a href="javascript:void(0);" class="page_buy_delete_basket disabled">убрать из корзины</a>
				</div>
			</div>
			<div class="page_basket_table_element">
				<div class="page_basket_table_element_col name">Модуль анализа сосудов</div>
				<div class="page_basket_table_element_col count">1 шт</div>
				<div class="page_basket_table_element_col price">30 000 РУБ</div>
				<div class="page_basket_table_element_col delete">
					<a href="javascript:void(0);" class="page_buy_delete_basket">убрать из корзины</a>
				</div>
			</div>
			<div class="page_basket_table_element">
				<div class="page_basket_table_element_col name">Подписка на обновления</div>
				<div class="page_basket_table_element_col count">+1 ГОД</div>
				<div class="page_basket_table_element_col price">24 000 РУБ</div>
				<div class="page_basket_table_element_col delete">
					<a href="javascript:void(0);" class="page_buy_delete_basket">убрать из корзины</a>
				</div>
			</div>
		</div>
		<div class="page_basket_table_s">
			<div class="page_basket_table_element_title">
				<span>СЕРВЕР</span>
				<a href="buy_s.php">изменить выбор</a>
				<i></i>
			</div>
			<div class="page_basket_table_element">
				<div class="page_basket_table_element_col name">Подключений: <b>18</b></div>
				<div class="page_basket_table_element_col count">1 ГОД</div>
				<div class="page_basket_table_element_col price">180 000 РУБ</div>
				<div class="page_basket_table_element_col delete">
					<a href="javascript:void(0);" class="page_buy_delete_basket disabled">убрать из корзины</a>
				</div>
			</div>
			<div class="page_basket_table_element">
				<div class="page_basket_table_element_col name">Подписка на обновления</div>
				<div class="page_basket_table_element_col count">+3 года</div>
				<div class="page_basket_table_element_col price">162 000 РУБ</div>
				<div class="page_basket_table_element_col delete">
					<a href="javascript:void(0);" class="page_buy_delete_basket">убрать из корзины</a>
				</div>
			</div>
		</div>
		<div class="page_basket_table_summ">
			ИТОГО <span>446 000</span> руб
		</div>
	</div>
	<div class="page_basket_personal_info">
		<form>
			<div class="page_basket_personal_info_title"><span>ИНФОРМАЦИЯ О ПЛАТЕЛЬЩИКЕ</span><i></i></div>
			<div class="page_basket_personal_info_type js-tab_inputs">
				<label class="radio_tab">
					<input type="radio" name="type" value="1" checked="checked" />
					<div class="radio_tab_button"></div>
					<div class="radio_tab_text">юридическое лицо</div>
				</label>
				<label class="radio_tab">
					<input type="radio" name="type" value="2" />
					<div class="radio_tab_button"></div>
					<div class="radio_tab_text">индивидуальный предприниматель</div>
				</label>
				<label class="radio_tab">
					<input type="radio" name="type" value="3" />
					<div class="radio_tab_button"></div>
					<div class="radio_tab_text">Физическое лицо</div>
				</label>
			</div>

			<div class="page_basket_personal_info_type_tab active" style="display: block;" data-target="1">
				<div class="form_table">
					<div class="form_table_col">
						<label class="input error">
							<span>Название компании</span>
							<input type="text" />
							<div class="error">Поле заполнено не верно</div>
						</label>
					</div>

					<div class="clear"></div>

					<div class="form_table_col">
						<label class="input valid have_value">
							<span>ОГРН</span>
							<input type="text" placeholder="1056405010100" value="1056405010100" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>ИНН</span>
							<input type="text" placeholder="6450606508" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>КПП</span>
							<input type="text" placeholder="645001001" />
							<div class="error"></div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Расчётный счёт</span>
							<input type="text" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Кор.счёт</span>
							<input type="text" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>БИК или банк</span>
							<input type="text" />
							<div class="error"></div>
						</label>
					</div>

					<div class="clear"></div>

					<div class="form_table_no_col">
						<label class="input">
							<span>Должность руководителя</span>
							<input type="text" />
							<div class="error"></div>
						</label>
						<div class="radio">
							<span>Действует на основании</span>
							<div class="radio_box">
								<label class="in_radio">
									<input type="radio" name="osn" checked="checked" />
									<div class="in_radio_button"></div>
									<div class="in_radio_text">Устава</div>
								</label>
								<label class="in_radio">
									<input type="radio" name="osn" />
									<div class="in_radio_button"></div>
									<div class="in_radio_text">Доверенности</div>
								</label>
							</div>
						</div>
						<label class="input">
							<span>ФИО директора</span>
							<input type="text" placeholder="Фамилия Имя Отчество" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Доверенность</span>
							<input type="text" placeholder="Дата и номер доверенности" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Юридический адрес</span>
							<input type="text" placeholder="Город, улица,дом, офис" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Почтовый адрес</span>
							<input type="text" placeholder="Город, улица,дом, офис" />
							<div class="error"></div>
						</label>

						<div class="clear"></div>
					</div>

					<hr/>

					<div class="form_table_col">
						<label class="input">
							<span>Email</span>
							<input type="text" placeholder="market@inobitec.com" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Телефон</span>
							<input type="text" placeholder="+7(900)000 00 00" />
							<div class="error"></div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Повторите пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
					</div>

					<div class="clear"></div>

					<label class="checkbox">
						<input type="checkbox" />
						<div class="checkbox_button"></div>
						<span>Я соглашаюсь с <a href="#">Лицензионным договором-офертой</a> и <a href="#">Обработкой персональных данных</a></span>
					</label>
					<div class="button_box">
						<a href="#" class="btn_n red big">скачать PDF счёт</a>
						<span>действителен 5 банковских дней</span>
					</div>
				</div>
			</div>

			<div class="page_basket_personal_info_type_tab" data-target="2">
				<div class="form_table">
					<div class="form_table_col">
						<label class="input error">
							<span>ФИО</span>
							<input type="text" />
							<div class="error">Фамилия Имя Отчество</div>
						</label>
					</div>

					<div class="clear"></div>

					<div class="form_table_col">
						<label class="input">
							<span>ИНН</span>
							<input type="text" placeholder="6450606508" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>ОГРНИП</span>
							<input type="text" placeholder="315645100023002" />
							<div class="error"></div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Юридический адрес</span>
							<input type="text" placeholder="Город, улица,дом, офис" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Почтовый адрес</span>
							<input type="text" placeholder="Город, улица,дом, офис" />
							<div class="error"></div>
						</label>
					</div>

					<div class="clear"></div>

					<hr/>

					<div class="form_table_col">
						<label class="input">
							<span>Email</span>
							<input type="text" placeholder="market@inobitec.com" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Телефон</span>
							<input type="text" placeholder="+7(900)000 00 00" />
							<div class="error"></div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Повторите пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
					</div>

					<div class="clear"></div>

					<label class="checkbox">
						<input type="checkbox" />
						<div class="checkbox_button"></div>
						<span>Я соглашаюсь с <a href="#">Лицензионным договором-офертой</a> и <a href="#">Обработкой персональных данных</a></span>
					</label>
					<div class="buttons_box">
						<div class="button_box">
							<a href="#" class="btn_n big">перейти к оплате</a>
							<span>По нажатию кнопки вы будете перенаправлены на страницу Яндекс.Касса для оплаты</span>
						</div>
						<div class="button_box">
							<a href="#" class="btn_n red big">скачать PDF счёт</a>
							<span>действителен 5 банковских дней</span>
						</div>
					</div>
				</div>
			</div>

			<div class="page_basket_personal_info_type_tab" data-target="3">
				<div class="form_table">
					<div class="form_table_col">
						<label class="input">
							<span>Email</span>
							<input type="text" placeholder="market@inobitec.com" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Телефон</span>
							<input type="text" placeholder="+7(900)000 00 00" />
							<div class="error"></div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
						<label class="input">
							<span>Повторите пароль</span>
							<input type="text" placeholder="*******" />
							<div class="error"></div>
						</label>
					</div>

					<div class="form_table_col">
						<label class="input error">
							<span>ФИО</span>
							<input type="text" />
							<div class="error">Фамилия Имя Отчество</div>
						</label>
					</div>
					<div class="form_table_col">
						<label class="input">
							<span>Почтовый адрес</span>
							<input type="text" placeholder="Город, улица,дом, офис" />
							<div class="error"></div>
						</label>
					</div>

					<div class="clear"></div>

					<label class="checkbox">
						<input type="checkbox" />
						<div class="checkbox_button"></div>
						<span>Я соглашаюсь с <a href="#">Лицензионным договором-офертой</a> и <a href="#">Обработкой персональных данных</a></span>
					</label>
					<div class="buttons_box">
						<div class="button_box">
							<a href="#" class="btn_n big">перейти к оплате</a>
							<span>По нажатию кнопки вы будете перенаправлены на страницу Яндекс.Касса для оплаты</span>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
