$(function(){
	$('#pass.popup button').on('click', function(e){
		e.preventDefault();
		var loginInpt = $('#pass input[name="USER_LOGIN"]'),
			loginLbl = loginInpt.closest('label.input'),
			loginErrorCont = loginInpt.siblings('.error-text'),
			sendPassBtn = $('#pass .button'),
			ajax_url = $('#pass form').attr('action');

		var login = $.trim(loginInpt.val());

		if (login.length) {
			loginErrorCont.text('');
			loginLbl.removeClass('error');
		} else {
			loginErrorCont.text(loginErrorCont.data('empty-text'));
			loginLbl.addClass('error');
			return false;
		}
		$.post(ajax_url, {send : 'Y', login : login}, function(response){
			if (response.TYPE == 'OK') {
				console.log('Успешная отправка');
				closePopup(document.getElementById('pass'));Popup('new-pass-success');
			} else {
				console.log(response);
				loginErrorCont.text(response.MESSAGE.replace('<br>', ''));
				loginLbl.addClass('error');
			}
		}, "json");
	});
});