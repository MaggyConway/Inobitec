$(function(){
	$('form.changepassform input[type="password"]').on('keydown', function(e){
		console.info(e.key);
		if (!/^[A-Za-z0-9]+$/.test(e.key))
			return false;
	});
	var password_input = $('form.changepassform input[name="USER_PASSWORD"]'),
		confirm_input = $('form.changepassform input[name="USER_CONFIRM_PASSWORD"]'),
		password_label = password_input.closest('label'),
		confirm_label = confirm_input.closest('label'),
		min_length = password_input.data('minlength'),
		min_length_mess = password_input.data('minlength-message'),
		empty_password_text = password_input.data('empty-text'),
		password_pattern_text = password_input.data('pattern-message'),
		empty_confirm_text = confirm_input.data('empty-text'),
		incorrect_confirm_text = confirm_input.data('incorrect-text');
		
	$('form.changepassform button').on('click', function(e){
		e.preventDefault();
		var bPasswordValid = false, bConfirmValid = false;
		var password = password_input.val(),
			confirm_password = confirm_input.val(),
			password_error = null,
			confirm_error = null;

		var passRegExp = /^[A-Za-z0-9]+$/;


		if (password.length < min_length) 
		{
			password_error = min_length_mess;
		} 
		else if (!passRegExp.test(password)) 
		{
			password_error = password_pattern_text;
		}
		else 
		{
			bPasswordValid = true;
		}
		if (bPasswordValid) 
		{
			password_label.removeClass('error').find('.error-text').remove();
		} 
		else 
		{
			if (password_input.siblings('.error-text').length) 
			{
				password_error_cont = password_input.siblings('.error-text')
			} 
			else 
			{
				password_error_cont = $('<div></div>').addClass('error-text').appendTo(password_label);
			}
			password_label.addClass('error');
			password_error_cont.text(password_error);
		}

		if (!confirm_password.length) 
		{
			confirm_error = empty_confirm_text;
		} 
		else if (confirm_password != password) 
		{
			confirm_error = incorrect_confirm_text;
		} 
		else 
		{
			bConfirmValid = true;
		}

		if (bConfirmValid) 
		{
			confirm_label.removeClass('error').find('.error-text').remove();
		} 
		else 
		{
			if (confirm_input.siblings('.error-text').length) 
			{
				confirm_error_cont = confirm_input.siblings('.error-text')
			} 
			else 
			{
				confirm_error_cont = $('<div></div>').addClass('error-text').appendTo(confirm_label);
			}
			confirm_label.addClass('error');
			confirm_error_cont.text(confirm_error);
		}
		if (bPasswordValid && bConfirmValid) 
			$('form.changepassform').submit();
	});
});