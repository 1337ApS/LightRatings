var lr_rating_handler = (function($){
	var hover_handler_in = function(){
		$(this).prev('.lr-star').removeClass('lr-star-half lr-star-half-stroke lr-star-empty lr-star-empty-stroke');
		$(this).removeClass('lr-star-half lr-star-half-stroke lr-star-empty lr-star-empty-stroke');
		$(this).next('.lr-star').removeClass('lr-star-half lr-star-half-stroke lr-star-empty lr-star-empty-stroke').addClass('lr-star-empty');
	};
	
	var hover_handler_out = function(){
		
	};
	
	return {
		initialize: function(){
			console.log("init");
			$('.lr-rating-stars .lr-star').hover(hover_handler_in, hover_handler_out);
		}	
	};
})(jQuery);

jQuery(document).ready(lr_rating_handler.initialize);