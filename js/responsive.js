/**
  * File responsive.js.
  * Author:  Dejan Batanjac
  * License: GPLv2
 */

(function ($) {

$(window).resize(function() { safescale(); } );

$(document).ready(function() { 
	$(window).trigger("resize"); // two times because of the oddities of some browsers
	$(window).trigger("resize");
});

function scale(factor_x){
	$("html").css("transform","scale("+ factor_x+","+ factor_x+")");
	$("html").css("-moz-transform","scale("+factor_x+","+factor_x+")");
	$("html").css("-webkit-transform","scale("+factor_x+","+factor_x+")");
	$("html").css("-o-transform","scale("+factor_x+","+factor_x+")");
}

function safescale(){
	// calculates ratio that will be used only if width is less then 800px
	factor_x = $(window).width() / $("body").width() ;
	
	if($(window).width() < 800){ // by design this function has hardeded width of 800 px
		$("html").css({"position": "absolute", "margin": "0px", "padding": "0px"});
		$("body").css({"position": "absolute", "margin": "0px", "padding": "0px"});
		scale(factor_x);
	}else{
		
		$("html").css({"position": "static"});
		$("body").css({"position": "static"}); 
		scale(1);
	}
}

})(jQuery);


