$(function(){
  $("span.value.phone input[name='form_text_3']").each(function(){
	$(this).mask("+7 (999) 999-99-99");
  });
});