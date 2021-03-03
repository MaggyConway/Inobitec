$(function(){
	$('form[name="changepass"] input[type="password"]').on('keydown', function(e){
		console.info(e.key);
		if (!/^[A-Za-z0-9]+$/.test(e.key))
			return false;
	});
	$('#changePassword').on('click', function(e){
		e.preventDefault();
		var password_input = $('form[name="changepass"] input[name="password"]'),
			confirm_password_input = $('form[name="changepass"] input[name="confirm_password"]'),
			password_error_cont = password_input.siblings('.error-text'),
			confirm_password_error_cont = confirm_password_input.siblings('.error-text'),
			ajax_url = $('form[name="changepass"]').attr('action'),
			min_password_length = parseInt(password_input.data('minlength'));

		var password = password_input.val();
		var confirm_password = confirm_password_input.val();
		var isValid = true;

		var passRegExp = /^[A-Za-z0-9]+$/;
		if (password.length < min_password_length) {
			password_input.closest('label').addClass('error');
			password_error_cont.text(password_input.data('minlength-message'));
			isValid = false;
		} else if (!(/^[A-Za-z0-9]+$/.test(password))) {
			password_input.closest('label').addClass('error');
			password_error_cont.text(password_input.data('message-pattern'));
			isValid = false;
		} else {
			password_input.closest('label').removeClass('error');
		}

		/*if (password.length) {
			var passRegExp = /^[A-Za-z0-9]+$/;

			if (!passRegExp.test(password)) {
				password_input.closest('label').addClass('error');
				password_error_cont.text(password_input.data('message-pattern'));
			} else if (password.length >= min_password_length) {
				password_input.closest('label').removeClass('error');
			} else {
				password_input.closest('label').addClass('error');
				password_error_cont.text(password_input.data('minlength-message'));
			}
		} else {
			password_input.closest('label').addClass('error');
			password_error_cont.text(password_error_cont.data('empty-text'));
			isValid = false;
		}*/
		if (confirm_password.length) {
			if (password != confirm_password) {
				confirm_password_input.closest('label').addClass('error');
				confirm_password_error_cont.text(confirm_password_error_cont.data('incorrect-text'));
				isValid = false;
			} else {
				confirm_password_input.closest('label').removeClass('error');
			}
		} else {
			confirm_password_input.closest('label').addClass('error');
			confirm_password_error_cont.text(confirm_password_error_cont.data('empty-text'));
			isValid = false;
		}
		if (isValid) {
			$.post(ajax_url, {password : password, confirm_password : confirm_password, change : 'Y'}, function(res){
				if (res.success) {
					closePopup(document.getElementById('changePassTwo'));
					Popup('pass-success-changed');
				} else {
					password_input.closest('label').addClass('error');
					password_error_cont.text(res.message);
				}
			}, "json")
		}
	});
});