$(document).ready(function(){ 

	// console.log( location.pathname );
	
	if ( location.pathname == '/end/' || location.pathname == 'fra/' || location.pathname == '/deu/' || location.pathname == '/esp/' ) {
		$('.headerNew').css('height', '200px');
	}

	if ( $(window).width() < 801 ) {

		// console.log( location.pathname );
		if (location.pathname == '/about/mission/' ||
			 location.pathname == '/about/partners/' ||
			 location.pathname == '/about/reviews/' ||
			 location.pathname == '/about/vacancies/' ||
			 location.pathname == '/about/contacts/' ||
			 location.pathname == '/eng/about/mission/' ||
			 location.pathname == '/eng/about/partners/' ||
			 location.pathname == '/eng/about/reviews/' ||
			 location.pathname == '/eng/about/vacancies/' ||
			 location.pathname == '/eng/about/contacts/' ||
			 location.pathname == '/fra/about/mission/' ||
			 location.pathname == '/fra/about/partners/' ||
			 location.pathname == '/fra/about/reviews/' ||
			 location.pathname == '/fra/about/vacancies/' ||
			 location.pathname == '/fra/about/contacts/' ||
			 location.pathname == '/deu/about/mission/' ||
			 location.pathname == '/deu/about/partners/' ||
			 location.pathname == '/deu/about/reviews/' ||
			 location.pathname == '/deu/about/vacancies/' ||
			 location.pathname == '/deu/about/contacts/' ||
			 location.pathname == '/esp/about/mission/' ||
			 location.pathname == '/esp/about/partners/' ||
			 location.pathname == '/esp/about/reviews/' ||
			 location.pathname == '/esp/about/vacancies/' ||
			 location.pathname == '/esp/about/contacts/') {

			let aboutHeaderHeight = 230;

			$('.headerNew').css('height', aboutHeaderHeight + 'px');
			$('.header-margin-inner').attr('style', 'margin-top: '+ aboutHeaderHeight +'px !important');

			// .css('margin-top', '250px !important');;
		}
	}
	

	$(document).on('click', '.captcha .renew a', function(){

		var src = $(this).parents('.captcha').find('img').attr("src")
		$(this).parents('.captcha').find('img').attr("src", src)

		return false;
	});

	

	window.onpopstate = function(event){
		var data = JSON.stringify(event.state);



		if(data != undefined && data != null){
			data = JSON.parse(data);
			console.log(data);
			if(data != undefined && data != null){
				if(data.modalOpened){
					$('body').removeClass('show-modal');
					$('.product-features__popup').css('overflow', 'hidden');
					$('.product-features__popup').velocity('fadeOut', 400, function(){
						$('.product-features__popup').css('display', 'none');
						$(this).find('.inn').velocity({
							translateY: (100, 0),
							opacity: 0
						},
						{
							duration: 300
						});
					});
				}
			}else{
				$('body').removeClass('show-modal');
				$('.product-features__popup').css('overflow', 'hidden');
				$('.product-features__popup').velocity('fadeOut', 400, function(){
					$('.product-features__popup').css('display', 'none');
					$(this).find('.inn').velocity({
						translateY: (100, 0),
						opacity: 0
					},
					{
						duration: 300
					});
				});
			}
		}
	}

	
	$(document).on('click', '.err_popup .close', function(){
		$('.err_popup').fadeOut('fast');

		return false;
	});



	$(document).on('click', '.form__feedback .more_files', function(){
		
		var clicked = $(this)
		var shownBlocks = 1

		$('.form__feedback .fileblock').each(function(){
			if($(this).hasClass('shown')){
				shownBlocks++
			}
		});

		$('.fileblock').eq(shownBlocks-1).fadeIn('fast', function(){
			$(this).addClass('shown')

			if(shownBlocks == $('.form__feedback .fileblock').length){
				$(clicked).fadeOut('fast').remove()
			}
		});

		return false;
	})

	$(document).on('click', '.form__feedback .value.file label', function(){
		var clicked = $(this);
		clicked.parents('.value').find('input').click();
	});


	$(document).on('change', '.form__feedback input[type=file]', function(){
		
		var changed = $(this);

		if(site_id == 's1'){
			var change = 'Изменить';
			var select = 'Выберите файл';
			var empty  = 'Файл не выбран';
		}else{
			var change = 'Change';
			var select = 'Select file';
			var empty = 'File not select';
		}

		if(changed.val() && changed.val() !== '')
		{
			var tmpstr = changed.val().split('\\');
			changed.parents('.value').find('.filename').html(tmpstr[tmpstr.length - 1]);
			changed.parents('.value').find('label').html(change);
		}else{
			changed.parents('.value').find('.filename').html(empty);
			changed.parents('.value').find('label').html(select);
		}
	});



	$('body').addClass('loaded');

	$('.mp-banner').owlCarousel({
		loop: true,
		dots: true,
		nav: false,
		items: 1,
		animateOut: 'fadeOut',
    	animateIn: 'fadeIn',
    	autoplay: true,
		autoplayHoverPause: true,
    	autoplayTimeout: 3000

	})

	$(document).on('click', 'a.keystones', function(e){
		e.preventDefault();

		if($('.product__free-html').length){
			var scrollTo = $('.product__free-html').offset().top - 70;
		}else{
			var scrollTo = $('.product__features').offset().top - 70;
		}
		
		$("body,html").animate({"scrollTop":scrollTo},300);
	});

	$(document).on('click', '.menu-trigger', function(){

		var clicked = $(this);

		$('.mobile-menu').css('display', 'block');

		$('.mobile-menu').velocity({
			height: '100%',
			opacity: 1
		}, '400');

		$('body').addClass('show-modal');

		$('.mobile-menu').find('ul li').each(function(){

			var menuItem = $(this);

			menuItem.velocity(
			{
				translateY: [0, -50],
				opacity:    [1, 0],
			}, 
			{ 	delay: menuItem.index()*200, 
				duration: 400, 
				easing: "easeIn", 
				complete: function(){
					menuItem.addClass('show-border');
				}
			});
		});

		return false;
	});

	$(document).on('click', '.mobile-menu .close', function(){

		var clicked = $(this);

		$('.mobile-menu').velocity(
		{
			height: 0,
			opacity: 0
		}, 
		{
			duration: 400, 
			complete: function(){
				$('.mobile-menu').css('display', 'none');
				$('.mobile-menu ul li').css('opacity', 0);
				$('.mobile-menu ul li').removeClass('show-border');
				$('body').removeClass('show-modal');
			}
		});
	});


	



	showPageModal = function(){
		$('.product-features__popup').css('display', 'block');
		$('.product-features__popup').velocity('fadeIn', 400, function(){
			$('body').addClass('show-modal');
			$('.product-features__popup').css('overflow', 'scroll');
		});
	}

	$(document).on('click', '.product__features .item', function(e){
		e.preventDefault();
	});


	$(document).on('mousedown', '.product__features .item', function(e){
		e.preventDefault();

		var clicked = $(this);

		var data = {
			'ITEM_ID' : clicked.data('id'),
			'SITE_ID' : site_id
		} 

		showPageModal();

		window.history.pushState(null, null, '');

		$.ajax({
			type	: "POST",
			url		: "/ajax/get_feautures_info.php",
			data	: data,
			success	: function(msg){
				if(msg!="error"){
					$('.product-features__popup').find('.inn').html(msg);
					$('.product-features__popup').find('.inn').velocity({
						translateY: (0, 100),
						opacity: 1
					},
					{
						duration: 300
					});
				}else{

				}
			}
		});
	});

	$(document).on('click', '.product-features__popup .close', function(e){

		e.preventDefault();
		
		$('body').removeClass('show-modal');
		$('.product-features__popup').css('overflow', 'hidden');
		$('.product-features__popup').velocity('fadeOut', 400, function(){
			$('.product-features__popup').css('display', 'none');
			$(this).find('.inn').velocity({
				translateY: (100, 0),
				opacity: 0
			},
			{
				duration: 300
			});
		});
	});


	$(document).on('click', 'a.release_info', function(e){
		e.preventDefault();
	});


	$(document).on('mousedown', 'a.release_info', function(e){
		e.preventDefault();

		var clicked = $(this);

		var data = {
			'ITEM_ID' : clicked.data('id'),
			'SITE_ID' : site_id
		} 

		showPageModal();

		$.ajax({
			type	: "POST",
			url		: "/ajax/get_release_info.php",
			data	: data,
			success	: function(msg){
				if(msg!="error"){
					$('.product-features__popup').find('.inn').html(msg);
					$('.product-features__popup').find('.inn').velocity({
						translateY: (0, 100),
						opacity: 1
					},
					{
						duration: 300
					});
				}else{

				}
			}
		});
	});

	
	$('.popup-image').fancybox();



	if($('.mp-po').length){

		var winHeight = $(window).height();
		var element = $('.mp-po');
		var elementHeight = element.height();
		var scrollHeight = winHeight + elementHeight;
		var img = element.find('img');
		var imgDif = img.height() - elementHeight;

		$(window).resize(function(){
			
			winHeight = $(window).height();
			element = $('.mp-po');
			elementHeight = element.height();
			scrollHeight = winHeight + elementHeight;
			img = element.find('img');
			imgDif = img.height() - elementHeight;

		});

		$(window).scroll(function(){

			var winTop = $(window).scrollTop();
			var elementTop = element.offset().top;
			var elementBottom = elementTop + elementHeight;
			
			if( (elementTop < winTop + winHeight) && (elementTop + elementHeight > winTop) ){
				if(imgDif > 0){
					var percenage = (scrollHeight - (elementBottom - winTop))/scrollHeight;
					img.css('top', '-'+(imgDif*percenage)+'px');
					//img.css('transform', 'scale('+(1+0.5*percenage)+')');
					//$('.mp-po').find('.inn').css('background-position', '-'+(200*percenage)+'px top');
				}
			}
		});
	}

	$(document).on('click', '.careers__selector a', function(e){
		e.preventDefault();

		var clicked = $(this);
		var index = clicked.index();

		$('.careers__selector a.active').removeClass('active');
		clicked.addClass('active');

		$('.careers__right .tab_content.shown').css('position', 'absolute');
		$('.careers__right .tab_content.shown').css('left', '0');
		$('.careers__right .tab_content.shown').css('top', '0');

		$('.careers__right .tab_content.shown').velocity('fadeOut', 'fast', function(){
			$(this).removeClass('shown');
			$(this).css('position', 'static');
		})

		$('.careers__right .tab_content').eq(index).velocity('fadeIn', 'fast', function(){
			$(this).addClass('shown');
		});

		console.log($(window).scrollTop() + $(window).height());

		if(window.matchMedia('(max-width: 700px)').matches && $('.careers__right').offset().top > ($(window).scrollTop() + $(window).height()))
		{
			$("body,html").animate({"scrollTop":$('.careers__right').offset().top},300);
		}
	});


	var lastWait = [];
	/* non-xhr loadings */

	BX.showWait = function (node, msg)
	{
		node = BX(node) || document.body || document.documentElement;
		msg = msg || BX.message('JS_CORE_LOADING');

		var container_id = node.id || Math.random();

		var obMsg = node.bxmsg = document.body.appendChild(BX.create('DIV', {
			props: {
				id: 'wait_' + container_id,
				className: 'bx-core-waitwindow'
			},
			text: msg
		}));

		setTimeout(BX.delegate(_adjustWait, node), 10);

		$('.preloader').show();
		lastWait[lastWait.length] = obMsg;
		return obMsg;
	};

	BX.closeWait = function (node, obMsg)
	{
		$('.preloader').hide();
		if (node && !obMsg)
			obMsg = node.bxmsg;
		if (node && !obMsg && BX.hasClass(node, 'bx-core-waitwindow'))
			obMsg = node;
		if (node && !obMsg)
			obMsg = BX('wait_' + node.id);
		if (!obMsg)
			obMsg = lastWait.pop();

		if (obMsg && obMsg.parentNode)
		{
			for (var i = 0, len = lastWait.length; i < len; i++)
			{
				if (obMsg == lastWait[i])
				{
					lastWait = BX.util.deleteFromArray(lastWait, i);
					break;
				}
			}

			obMsg.parentNode.removeChild(obMsg);
			if (node)
				node.bxmsg = null;
			BX.cleanNode(obMsg, true);
		}
	};

	function _adjustWait()
	{
		if (!this.bxmsg)
			return;

		var arContainerPos = BX.pos(this),
			div_top = arContainerPos.top;

		if (div_top < BX.GetDocElement().scrollTop)
			div_top = BX.GetDocElement().scrollTop + 5;

		this.bxmsg.style.top = (div_top + 5) + 'px';

		if (this == BX.GetDocElement())
		{
			this.bxmsg.style.right = '5px';
		}
		else
		{
			this.bxmsg.style.left = (arContainerPos.right - this.bxmsg.offsetWidth - 5) + 'px';
		}
	}
    
});


(function () {
 if (document.querySelector('.search')){
 	for (let i = 0; i < document.querySelectorAll('.search-navigation-text p').length; i++){
        if (!document.querySelectorAll('.search-navigation-text p')[i].querySelector('a')){
            document.querySelectorAll('.search-navigation-text p')[i].classList.add('not-active')
		}
	}
 }
}())