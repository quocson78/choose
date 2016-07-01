$(function(){

});


$(function(){
	$("input.user_login02,input.user_login03").hover(function(){
  		$(this).css("background","url(/pc/images/toppage/t_form_icon05_on.png) no-repeat");
		},
		function(){
		$(this).css("background","url(/pc/images/toppage/t_form_icon05.png) no-repeat");
	});
	
	$("input.user_login02,input.user_login03").click(function(){
  		$(this).css("background","url(/pc/images/toppage/t_form_icon05_on.png) no-repeat");
		});
	
	$(".shoplist_sub input.user_login02,.shoplist_sub input.user_login03").hover(function(){
  		$(this).css("background","url(/pc/images/toppage/t_form_icon05_on.png) no-repeat");
		},
		function(){
		$(this).css("background","url(/pc/images/toppage/t_form_icon05.png) no-repeat");
	});
	
	
	$("input.shop_user_login02").hover(function(){
  		$(this).css("background","url(/pc/images/toppage/s_form_icon01_on.gif) no-repeat");
		},
		function(){
		$(this).css("background","url(/pc/images/toppage/s_form_icon01.gif) no-repeat");
	});
	
	$("input.shop_user_login02").click(function(){
  		$(this).css("background","url(/pc/images/toppage/s_form_icon01_on.gif) no-repeat");
		});
	
	$("input.shop_user_login03").hover(function(){
  		$(this).css("background","url(/pc/images/toppage/s_form_icon02_on.gif) no-repeat");
		},
		function(){
		$(this).css("background","url(/pc/images/toppage/s_form_icon02.gif) no-repeat");
	});
	
	$("input.shop_user_login03").click(function(){
  		$(this).css("background","url(/pc/images/toppage/s_form_icon02_on.gif) no-repeat");
		});
	
		$("input.free_submit").hover(function(){
  		$(this).css("background","url(/pc/images/cat/search_button01_on.gif) no-repeat");
		},
		function(){
		$(this).css("background","url(/pc/images/cat/search_button01.gif) no-repeat");
	});
	
	



	
//	$("a img").hover(function(){
//		$(this).fadeTo("fast", 0.8); 
//		},
//		function(){
//		$(this).fadeTo("fast", 1.0); 
//	});
});

