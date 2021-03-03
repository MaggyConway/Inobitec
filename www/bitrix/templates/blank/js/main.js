$(document).ready(function(){

	// СТРАНИЦА ИНСТРУКЦИИ



	$('.manual__menu li.active').parents('li').addClass('active').find('>a').addClass('active');

	$(document).on('click', '.manual__menu .ext', function(e){

		e.preventDefault();

		var clicked = $(this);
		var parent = $(this).parents('li:first');

		if(parent.hasClass('active')){
			parent.removeClass('active');
			clicked.removeClass('active');
		}else{
			parent.addClass('active');
			clicked.addClass('active');
		}
		
	});

	ManualOnResize = function(){
		if( $('.manual__right').outerWidth() < 800){
			$('.manual__right').addClass('slim');
		}else{
			$('.manual__right').removeClass('slim');
		}

		if( $('.manual__left').outerWidth() < 500){
			$('.manual__left').addClass('slim');
		}else{
			$('.manual__left').removeClass('slim');
		}
	}

	ManualInitSize = function(){
		$('.manual__right').css("width", $(window).width() - $('.manual__left').width()); 
	}

	ManualInitSize();
	ManualOnResize();

	if($('.manual__page').length){
		var leftMin = 250;
		var leftMax = 900;
		var rightMin = 0;
		var inResize = false;
		var resizeTimer = false;

		$('.manual__resizer').mousedown(function(e){
			inResize = true;
			if(!$('.manual__close-left').hasClass('closed')){
				
			    e.preventDefault();

			    $(document).mousemove(function(e){

			        e.preventDefault();

			        var x = e.pageX - $('.manual__left').offset().left;

			        if (x > leftMin && x < leftMax && e.pageX < ($(window).width() - rightMin)) {  
			          $('.manual__left').css("width", x-10);
			          $('.manual__right').css("width", $(window).width()-x);
			        }

			        ManualOnResize();
			    })
			}

		});


		$(document).mouseup(function (e){

		    $(document).unbind('mousemove');

		    if(inResize === true){

		    	resizeTimer = setTimeout(function(){

					var data_l = {
						'SESS_PARAM' : 'LEFT_WIDTH',
						'SESS_PARAM_VALUE' : $('.manual__left').width()
					}

					$.ajax({
						type	: "POST",
						url		: "/ajax/set_session_param.php",
						data	: data_l
					});

					var data_r = {
						'SESS_PARAM' : 'RIGHT_WIDTH',
						'SESS_PARAM_VALUE' : $('.manual__right').outerWidth()
					}

					$.ajax({
						type	: "POST",
						url		: "/ajax/set_session_param.php",
						data	: data_r
					});
				}, 200);

		    	inResize = false;

		    	// Здесь нужно отправить AJAX чтобы установить ширину колонки в СЕССИЮ
		    }

		});

		$(window).resize(function(){
			ManualOnResize();
		});
	}


	$(document).on('click', '.manual__close-left', function(e){

		e.preventDefault();

		if($('.manual__close-left').hasClass('closed')){

			ManualOnResize();
			$('.manual__left').css('height', 'auto');
			$('.manual__left').velocity(
			{
				'width' : leftMin,
			}, 
				'fast',
				function(){
					$('.manual__close-left').removeClass('closed');
				}
			);
		}else{
			$('.manual__left').velocity(
			{
				'width' : 0,
				'height': 0
			}, 
				'fast',
				function(){
					$('.manual__close-left').addClass('closed');
				}
			);

			$('.manual__right').velocity({
				'width' : '100%'
			});
		}

		

	});
	

	// END :: СТРАНИЦА ИНСТРУКЦИИ
});