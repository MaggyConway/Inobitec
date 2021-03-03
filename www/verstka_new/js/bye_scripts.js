$(document).ready(function(){

	$(".js-basket_top_button").click(function(){
		if ($("body").width() > 880){
			if ($(".sub_basket").hasClass("openned")) {
				$(".sub_basket").toggleClass("openned");
				setTimeout(function(){
					$(".sub_basket").toggle("fast");
				},200);
			}else{
				$(".sub_basket").toggle("fast");
				setTimeout(function(){
					$(".sub_basket").toggleClass("openned");
				},200);
			}

			return false;
		}
	});

	//добавление элемента LITE
	$(".js-add_lite").click(function(){
		if (!$(this).hasClass("disabled")){
			$(".page_buy_p_lite_elements").append('<div class="page_buy_p_lite_element new"><div class="page_buy_p_lite_element_content"><div class="page_buy_p_lite_element_name">РЕДАКЦИЯ<span>LITE</span></div><div class="page_buy_p_lite_element_description">Статичная редакция с ограниченным функционалом</div><div class="page_buy_p_lite_element_price rub">8 000</div><div class="page_buy_p_lite_element_button"><div class="page_buy_button btn_n" onclick="to_basket(this);">В Корзину</div></div></div></div>');

			$(".page_buy_p_lite_elements .page_buy_p_lite_element.new").toggle('fast').removeClass("new");
			scale_price();
		}
	});

	//js input_range
	$(".input_range").each(function(i,n){
		let min = $(this).find('input').attr('min');
		let max = $(this).find('input').attr('max');

		$(this).find('.min').text(min);
		$(this).find('.max').text(max);
	});
	setInterval(function(){
		$(".input_range").each(function(i,n){
			let min = $(this).find('input').attr('min');
			let max = $(this).find('input').attr('max');
			let val = $(this).find('input').val();

			let percent = 100 / (Number(max)-1) * (Number(val)-1);

			$(this).find('.val').text(val);
			$(this).find('.val').css("left",percent+"%");
		});
	},66);




	///скрипты вывода таба по ралиокнопке/ЛК
	$(".js-tab_inputs input, .tab_nav_element").change(function(){
		if ($(".page_basket_personal_info_type_tab.active").length > 0){
			if ($(".page_basket_personal_info_type_tab.active").attr("data-target") != $(this).val()){
				$(".page_basket_personal_info_type_tab.active").toggle("fast");
				$(".page_basket_personal_info_type_tab.active").removeClass("active");

				$(".page_basket_personal_info_type_tab[data-target='"+$(this).val()+"']").toggle("fast");
				$(".page_basket_personal_info_type_tab[data-target='"+$(this).val()+"']").addClass("active");
			}
		}else{
			$(".page_basket_personal_info_type_tab[data-target='"+$(this).val()+"']").toggle("fast");
			$(".page_basket_personal_info_type_tab[data-target='"+$(this).val()+"']").addClass("active");
		}
	});
	$(".tab_nav_element:not(.link)").click(function(){
		$(".tab_nav_element.active").removeClass("active");
		$(this).addClass("active");

		if ($(".page_basket_personal_info_type_tab.active").length > 0){
			if ($(".page_basket_personal_info_type_tab.active").attr("data-target") != $(this).attr("data-target")){
				$(".page_basket_personal_info_type_tab.active").toggle("fast");
				$(".page_basket_personal_info_type_tab.active").removeClass("active");

				$(".page_basket_personal_info_type_tab[data-target='"+$(this).attr("data-target")+"']").toggle("fast");
				$(".page_basket_personal_info_type_tab[data-target='"+$(this).attr("data-target")+"']").addClass("active");
			}
		}else{
			$(".page_basket_personal_info_type_tab[data-target='"+$(this).attr("data-target")+"']").toggle("fast");
			$(".page_basket_personal_info_type_tab[data-target='"+$(this).attr("data-target")+"']").addClass("active");
		}
	});
});

function to_basket(d_this,parent){
	if (!$(d_this).hasClass("disabled") && !$(d_this).hasClass("solid")){
		let for_main = $(d_this).parent().parent();
		if (!parent){ for_main = for_main.parent(); }

		$(d_this).addClass("solid").text("В корзине");
		for_main.addClass("in_basket");

		if (!parent){
			$(d_this).parent().append('<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this);">убрать из корзины</a>');
		}else{
			$(d_this).parent().append('<a href="javascript:void(0);" class="page_buy_delete_basket" onclick="cancle_buy(this,true);">убрать из корзины</a>');
		}

		scale_price();
	}
}
function cancle_buy(d_this,parent = false){
	let for_button = $(d_this).parent().find(".btn_n");
	let for_main = $(d_this).parent().parent();
	if (!parent){ for_main = for_main.parent(); }

	for_button.removeClass("solid").text("В корзину");
	for_main.removeClass("in_basket");

	$(d_this).remove();
	scale_price();
}
function scale_price(){
	//lite
	if ($(".page_buy_p_lite_elements .page_buy_p_lite_element").length > $(".page_buy_p_lite_elements .page_buy_p_lite_element.in_basket").length){
		$(".js-add_lite").addClass("disabled");
	}else{
		$(".js-add_lite").removeClass("disabled");
	}

	//pro

	//update
	if ($(".page_buy_p_updates_elements .page_buy_p_total_element.in_basket").length > 0){
		$(".page_buy_p_updates_elements .btn_n:not('.solid')").addClass("disabled");
	}else{
		$(".page_buy_p_updates_elements .btn_n").removeClass("disabled");
	}
}
