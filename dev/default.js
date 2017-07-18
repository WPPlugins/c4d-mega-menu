(function($){
	"use strict";
	function c4d_mega_menu(){
		var ww = $(window).width();
		$('.c4d-mega-menu > .sub-menu').each(function(index, el){
			$(el).css({'left': '0'});
			var elw = $(el).width(),
			pos = $(el).offset(),
			left = pos.left - ((ww - elw) / 2);
			$(el).css({'left': '-' + left + 'px'});
		});
	};
	$(document).ready(function(){
		c4d_mega_menu();
		$(window).on('resize', function(){
			c4d_mega_menu();
		});
	});
})(jQuery);