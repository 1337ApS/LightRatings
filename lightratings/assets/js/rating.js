var lr_rating_handler = (function($){
	var submit_rating = function( rating, post_id, $elem ){		
		$.ajax({
			type:	    'post',
			dataType:	'json',
			url:	    launchportal.ajaxurl,
			data:	    {action: 'lr_submit_rating', rating: rating, post_id: post_id},
			success:	function(data){
				console.log(data);
				if(data.error){
					// Could not store rating
					set_rating_stars( $elem, data.average, data.rating );
				}else{
					// Rating stored!
					$elem.attr('data-average', data.average);
					set_rating_stars( $elem, data.average, data.rating );
					$elem.removeClass('lr-can-rate');
					$('.lr-star', $elem).off();
				}
			},
			error: function(a, b, c){
				console.log("Error!", a, b, c);
			}
		});
	};
	
	var set_rating_stars = function( $container, rating, stroked ){
		if(isNaN(stroked))
			stroked = 0;
		
		$('.lr-star', $container).removeClass('lr-star-half lr-star-empty lr-star-shaded lr-star-stroke');
		
		var full_stars = Math.floor(rating);
		var empty_stars = 5 - Math.ceil(rating);
		var half_star = (rating - full_stars > 0)
		
		for(var i = full_stars + 1; i <= 5; i++)
			$('.lr-star:nth-child(' + i + ')', $container).addClass('lr-star-empty');
		
		if(half_star)
			$('.lr-star:nth-child(' + (full_stars + 1) + ')', $container).addClass('lr-star-half');
		
		for(var i = 1; i <= stroked; i++)
			$('.lr-star:nth-child(' + i + ')', $container).addClass('lr-star-stroke');
	}
	
	var hover_handler_in = function(){
		var average = $(this).parent().attr('data-average');
		var rating = $(this).prevAll('.lr-star').length + 1;
		
		$(this).prevAll('.lr-star').removeClass('lr-star-half lr-star-empty lr-star-shaded');
		$(this).removeClass('lr-star-half lr-star-empty lr-star-shaded');
		$(this).nextAll('.lr-star').removeClass('lr-star-half').addClass('lr-star-empty');
		
		var diff = average - rating;
		if(diff > 0){
			for(var i = 0; i < diff; i++){
				$('.lr-star:nth-child(' + (rating + i + 1) + ')', $(this).parent() ).addClass('lr-star-shaded');
			}
			
			// Check if the last one should be a half star
			var lower = Math.floor(diff);
			if(diff - lower > 0) 
				$('.lr-star:nth-child(' + (rating + lower + 1) + ')', $(this).parent() ).addClass('lr-star-half');
		}
	};
	
	var hover_handler_out = function(){		
		var $parent = $(this).parent();
		
		var average = $parent.attr('data-average');
		set_rating_stars( $parent, average );
	};
	
	var click_stars = function(){
		var $parent = $(this).parent();
		
		var rating = $('.lr-star:not(.lr-star-empty)').length;
		var post_id = parseInt($parent.attr('data-postid'));
		
		submit_rating(rating, post_id, $parent);
		return false;
	};
	
	return {
		initialize: function(){
			$('.lr-rating-stars.lr-can-rate .lr-star').hover(hover_handler_in, hover_handler_out);
			$('.lr-rating-stars.lr-can-rate .lr-star').click(click_stars);
		}	
	};
})(jQuery);

jQuery(document).ready(lr_rating_handler.initialize);