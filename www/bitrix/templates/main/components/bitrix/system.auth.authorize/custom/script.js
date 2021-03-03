$(function(){
	var auth_ajax_url = $('#authorization form').attr('action');
	$('#authorization.popup').on('click', '.button', function(e){
		e.preventDefault();
		var emailInput = $('#authorization input[name=USER_LOGIN]');
		var passwordInput = $('#authorization input[name=USER_PASSWORD]');
		var email = $.trim(emailInput.val());
		var password = $.trim(passwordInput.val());
		var emailErrorLbl = emailInput.siblings('.error-text');
		var passwordErrorLbl = passwordInput.siblings('.error-text');
		var emailCont = emailInput.closest('label.input');
		var passwordCont = passwordInput.closest('label.input');
		var isValid = true;

		if (email.length) {
			emailCont.removeClass('error');
			emailErrorLbl.text('');
		} else {
			emailCont.addClass('error');
			emailErrorLbl.text(emailErrorLbl.data('empty-text'));
			isVaild = false;
		}
		if (password.length) {
			passwordCont.removeClass('error');
			passwordErrorLbl.text('');
		} else {
			passwordCont.addClass('error');
			passwordErrorLbl.text(passwordErrorLbl.data('empty-text'));
			isValid = false;
		}
		if (isValid) {
			var request = {
				login : email,
				password : password,
				remember : $('input[name=USER_REMEMBER]').prop('checked') ? 'Y' : 'N',
				auth : 'Y'
			};
		} else {
			return;
		}
		console.log(request);
		$.ajax(auth_ajax_url, {
			data : request,
			dataType : "json",
			method : "POST"
		}).done(function(response){
			console.log(response);
			if (response.success) {
				window.location.reload();
			} else {
				emailCont.addClass('error');
				emailErrorLbl.text(response.message);
			}
		});
	});
});