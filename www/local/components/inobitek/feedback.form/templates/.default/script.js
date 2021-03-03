$(function(){
$('#feedBackSend').on('click', function(e){
		e.preventDefault();
		var $textar = $("#message"), $message = $.trim($textar.val()), $form = $(this).closest('form'), 
		$site = $form.find('input[name="site"]').val();
		var isValid = true;
		if ($message.length) {
			$textar.closest('label').removeClass('error');
		} else {
			isValid = false;
			$textar.closest('label').addClass('error');
		}
		if ($('#agree').prop('checked')) {
			$('#checkbox_err').hide();
		} else {
			isValid = false;
			$('#checkbox_err').text($site == 's2' ? "You must agree with this item" : "Необходимо согласиться с этим пунктом").css('color', '#E35E55').show();

		}
		if (isValid) {
			var $url = $form.attr('action');
			$.ajax($url, {
				method : 'POST',
				data : {
					text : $message,
					send : 'Y',
					site : $site,
					EMAIL : $form.find('input[name="EMAIL"]').val()
				},
				dataType : 'json'
			}).done(function(response){
				console.log(response);
                if(response.error){
                  $('#globalError span').html(response.error);
                  $('#globalError').show();
                  setTimeout(function(){
                    $('#globalError').hide();
                  }, 5000);
				// $('#messageLabel').text($site == 's2' ? "Message sent successfully" : "Сообщение успешно отправлено").css('color', '#23BF33');
				// $textar.remove();
				// $('.verification, #feedBackSend').remove();
                }else{
                  $('.contact-us__form').removeClass('contact-us__form-active');
                  $('.contact-us__form-success').addClass('contact-us__form-success-active');
                }
			});
		} else {
			//Показать сообщение об ошибке
		}
	})
});